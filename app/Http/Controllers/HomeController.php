<?php

namespace App\Http\Controllers;

use App\Http\Helpers\OracleRestErp;
use App\Http\Helpers\RequestNit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $rol = Auth::User()->rol;

        if ($rol->role_id == 1) {
            return $this->homeAdmin();
        }

        if ($rol->role_id == 2 || $rol->role_id == 3) {
            return $this->homeAfiliado();
        }

        if ($rol->role_id == 4) {
            return $this->homeGestor();
        }
    }

    /**
     * Dashboard for administrators (role_id = 1).
     */
    private function homeAdmin()
    {
        $request_status = DB::table('users')
            ->where('deleted_at', null)
            ->select('status', DB::Raw('count(status) AS count'))
            ->groupBy('users.status')
            ->get();

        return view('home.admin', [
            'request_status' => $request_status,
        ]);
    }

    /**
     * Dashboard for afiliados (role_id = 2 or 3).
     */
    private function homeAfiliado()
    {
        $user = $this->getAssignedUser() ?? Auth::user();

        $number_id    = $user->number_id;
        $documentType = $user->document_type;
        $taxpayerId     = ($documentType == "NIT") ? RequestNit::getNit($number_id) : $number_id;

        $supplierNumber = $this->findSupplierNumber($taxpayerId);

        if ($supplierNumber['status'] === 404) {
            return response()->view('error_pages.proveedorNoEncontrado', [
                'number_id' => $number_id,
                'document_type' => $documentType,
            ], 404);
        }

        if ($supplierNumber['status'] !== 200) {
            Log::error(__METHOD__ . '. Error al consultar el SupplierNumber en Oracle ERP: ' . $supplierNumber['body']);
            return response()->view('error_pages.errorGeneral', [
                'number_id' => $number_id,
                'document_type' => $documentType,
            ], 500);
        }
        return view('home.afiliado', [
            'SupplierNumber' => $supplierNumber['body'],
            'number_id' => $number_id,
        ]);
    }

    /**
     * Dashboard for gestores (role_id = 4).
     */
    private function homeGestor()
    {
        return view('home.gestor');
    }

    /**
     * Get the user assigned via the relationship table, if any.
     */
    private function getAssignedUser()
    {
        return DB::table('relationship')
            ->leftJoin('users', 'users.id', '=', 'relationship.user_id')
            ->where('relationship.user_assigne_id', Auth::user()->id)
            ->where('relationship.deleted_at', '=', null)
            ->select('users.number_id', 'users.document_type')
            ->first();
    }

    /**
     * Build the list of possible TaxpayerId formats to try against Oracle ERP.
     *
     * En el ERP los documentos que no son NIT se guardan como número simple (ej. 730000),
     * mientras que los NIT (rango 800xxxxxx a 901xxxxxx) llevan dígito de verificación
     * con guion (ej. 900000000-9). Como no hay certeza de con cuál formato quedó
     * registrado el proveedor, se generan variantes (con/sin DV, con/sin guion) para
     * probarlas todas contra Oracle.
     */

    private function buildDocumentCandidates(string $document, string $documentType, string $number_id)
    {
        $documentCandidates = [(string) $document];

        if ($documentType == "NIT") {
            $normalizedNumberId = preg_replace('/\D+/', '', (string) $number_id);

            if ($normalizedNumberId !== '') {
                $withDv = RequestNit::getNit($normalizedNumberId);
                if ($withDv && !in_array($withDv, $documentCandidates, true)) {
                    $documentCandidates[] = $withDv;
                }
            }

            if (str_contains($document, '-')) {
                $withoutDv = str_replace('-', '', $document);
                if (!in_array($withoutDv, $documentCandidates, true)) {
                    $documentCandidates[] = $withoutDv;
                }
            }
        }
        return $documentCandidates;
    }

    /**
     * Look up the SupplierNumber in Oracle ERP for the given TaxpayerId candidates.
     */
    private function findSupplierNumber(string $taxpayerId)
    {
        $params = [
            'q'        => "(TaxpayerId = '{$taxpayerId}')",
            'limit'    => '1',
            'fields'   => 'SupplierNumber',
            'onlyData' => 'true',
        ];

        $response = OracleRestErp::procurementGetSuppliers($params);

        if ($response->successful()) {

            $supplier = $response->object();

            if ($supplier->count > 0) {

                return [
                    'status' => $response->status(),
                    'body' => (int) $supplier->items[0]->SupplierNumber,
                ];
            } else {
                return [
                    'status' => 404,
                    'body' => 'Supplier not found',
                ];
            }
        } else {

            return [
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }
    }
}
