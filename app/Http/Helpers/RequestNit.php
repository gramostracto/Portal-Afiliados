<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Crypt;

class RequestNit
{
    public static function getNit($document)
    {
        $document = trim((string) $document);

        if ($document === '') {
            return $document;
        }

        if (str_contains($document, '-')) {
            return $document;
        }

        if (!is_numeric($document)) {
            return $document;
        }
        $arr = array(
            1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19,
            8 => 37, 11 => 47, 14 => 67, 3 => 13, 6 => 23, 9 => 41, 12 => 53, 15 => 71
        );
        $x = 0;
        $y = 0;
        $z = strlen($document);
        $dv = '';

        for ($i = 0; $i < $z; $i++) {
            $y = substr($document, $i, 1);
            $x += ($y * $arr[$z - $i]);
        }

        $y = $x % 11;

        if ($y > 1) {
            $dv = 11 - $y;
            $identificacion = $document . "-" . $dv;
        } else {
            $dv = $y;
            $identificacion = $document . "-" . $dv;
        }
        return $identificacion;
    }

    /**
     * Formatos posibles del TaxpayerId en ERP/OTM para un documento guardado solo con dígitos.
     *
     * El registro solo admite números (sin guion), así que el usuario pudo escribir el NIT
     * con DV ("9999999999" = 999999999-9) o sin DV ("999999999"). Se devuelven todas las
     * variantes para probarlas, en orden de probabilidad.
     */
    public static function candidates($document, $documentType = 'NIT'): array
    {
        $document = trim((string) $document);
        $digits   = preg_replace('/\D+/', '', $document);
        $list     = [];

        if ($documentType == 'NIT' && $digits !== '') {
            if (str_contains($document, '-')) {
                $list[] = $document;
            }

            // Último dígito ya es el DV: 9999999999 => 999999999-9
            if (strlen($digits) > 1) {
                $base = substr($digits, 0, -1);
                if (self::getNit($base) === $base . '-' . substr($digits, -1)) {
                    $list[] = $base . '-' . substr($digits, -1);
                    $list[] = $base;
                }
            }

            // Sin DV: 999999999 => 999999999-9
            $list[] = self::getNit($digits);
        }

        $list[] = $digits !== '' ? $digits : $document;

        return array_values(array_unique(array_filter($list, fn($v) => $v !== '')));
    }

    /**
     * Valor a guardar en users.number_id (formato idéntico al del ERP/OTM).
     *
     * NIT: "base-DV" (ej. 900500200-7). Acepta 9 dígitos (calcula el DV) o "9 dígitos-DV"
     * (valida el DV). Otros documentos: solo dígitos.
     * Devuelve null si el valor no es válido.
     */
    public static function normalize($documentType, $number): ?string
    {
        $number = trim((string) $number);

        if ($documentType == 'NIT') {
            if (preg_match('/^\d{9}$/', $number)) {
                return self::getNit($number);
            }
            if (preg_match('/^(\d{9})-(\d)$/', $number, $m) && self::getNit($m[1]) === $number) {
                return $number;
            }
            return null;
        }

        return preg_match('/^\d+$/', $number) ? $number : null;
    }

    /**
     * Mensaje de error de validación para un documento, o null si es válido.
     */
    public static function validationError($documentType, $number): ?string
    {
        if (self::normalize($documentType, $number) !== null) {
            return null;
        }

        return $documentType == 'NIT'
            ? 'El NIT debe tener 9 dígitos y un dígito de verificación válido (ej. 900500200-7).'
            : 'El número de identificación solo admite dígitos.';
    }

    /**
     * ¿Ya existe un usuario (incluso eliminado) con este documento en cualquiera de sus formatos?
     */
    public static function exists($documentType, $number, $ignoreId = null): bool
    {
        $normalized = self::normalize($documentType, $number);
        if ($normalized === null) {
            return false;
        }

        $query = \App\Models\User::withTrashed()
            ->whereIn('number_id', self::candidates($normalized, $documentType));

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    /**
     * Regla de validación para number_id (formato + unicidad).
     */
    public static function rule($documentTypeResolver, $ignoreId = null): \Closure
    {
        return function ($attribute, $value, $fail) use ($documentTypeResolver, $ignoreId) {
            $type = $documentTypeResolver();

            if ($error = self::validationError($type, $value)) {
                return $fail($error);
            }
            if (self::exists($type, $value, $ignoreId)) {
                return $fail('Ya existe un usuario con este número de identificación.');
            }
        };
    }
}
