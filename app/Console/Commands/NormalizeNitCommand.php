<?php

namespace App\Console\Commands;

use App\Http\Helpers\RequestNit;
use App\Models\User;
use Illuminate\Console\Command;

class NormalizeNitCommand extends Command
{
    protected $signature = 'users:normalize-nit {--apply : Aplica los cambios (sin esta opción solo simula)}';

    protected $description = 'Normaliza users.number_id de los NIT al formato base-DV (ej. 900500200-7)';

    public function handle()
    {
        $apply = $this->option('apply');
        $this->info($apply ? 'MODO APLICAR: se actualizará la base de datos.' : 'MODO SIMULACIÓN: no se modifica nada (usa --apply para aplicar).');

        $changes = [];
        $review  = [];
        $ok      = 0;

        User::withTrashed()->where('document_type', 'NIT')->orderBy('id')->each(function (User $user) use (&$changes, &$review, &$ok) {
            $current  = trim((string) $user->number_id);
            $proposed = RequestNit::normalize('NIT', $current);

            // 10 dígitos: base + DV sin guion (9999999999)
            if ($proposed === null && preg_match('/^(\d{9})(\d)$/', $current, $m) && RequestNit::getNit($m[1]) === "{$m[1]}-{$m[2]}") {
                $proposed = "{$m[1]}-{$m[2]}";
            }

            if ($proposed === null) {
                $review[] = [$user->id, $user->email, $current, 'Formato/DV no reconocido'];
                return;
            }

            if ($proposed === $current) {
                $ok++;
                return;
            }

            if (User::withTrashed()->where('number_id', $proposed)->where('id', '!=', $user->id)->exists()) {
                $review[] = [$user->id, $user->email, $current, "Duplicado: ya existe $proposed"];
                return;
            }

            $changes[] = [$user->id, $user->email, $current, $proposed];
        });

        $this->line("Ya correctos: $ok");

        if ($changes) {
            $this->info('Cambios ' . ($apply ? 'aplicados' : 'propuestos') . ':');
            $this->table(['ID', 'Email', 'Actual', 'Nuevo'], $changes);
        }
        if ($review) {
            $this->warn('Requieren revisión manual (no se modifican):');
            $this->table(['ID', 'Email', 'Actual', 'Motivo'], $review);
        }

        if ($apply) {
            foreach ($changes as [$id, , , $new]) {
                User::withTrashed()->where('id', $id)->update(['number_id' => $new]);
            }
            $this->info(count($changes) . ' usuarios actualizados.');
        } else {
            $this->line(count($changes) . ' cambios propuestos, ' . count($review) . ' para revisión.');
        }

        return 0;
    }
}
