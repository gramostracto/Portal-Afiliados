<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Helpers\GetClientIp;
use App\Http\Helpers\OracleRestErp;
use App\Http\Helpers\OracleRestOtm;
use App\Http\Helpers\ReporteRestOtm;
use App\Http\Helpers\RequestNit;
use App\Http\Helpers\UserTracking;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConsultarAfiliadoController extends Controller
{
    use SoftDeletes;

    function __construct()
    {
        $this->middleware('permission:/blog')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('usuarios.consultar');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function resolveOwnSupplierNumber()
    {
        $user = DB::table('relationship')
            ->leftJoin('users', 'users.id', '=', 'relationship.user_id')
            ->where('relationship.user_assigne_id',  Auth::user()->id)
            ->where('relationship.deleted_at', '=', null)
            ->select('users.number_id')
            ->first();

        $number_id = $user == null ? Auth::user()->number_id : $user->number_id;

        $params = [
            'q'        => "(TaxpayerId = '{$this->odataEscape($number_id)}')",
            'limit'    => '200',
            'fields'   => 'SupplierNumber',
            'onlyData' => 'true'
        ];

        $response = OracleRestErp::procurementGetSuppliers($params);

        $res = $response->json();

        if ($res['count'] == 0) {
            return null;
        }

        return (float) $res['items'][0]['SupplierNumber'];
    }

    /**
     * Escapa comillas simples para valores interpolados en filtros OData (q=...) hacia Oracle.
     */
    private function odataEscape($value)
    {
        return str_replace("'", "''", (string) $value);
    }

    public function suppliers(Request $request)
    {

        $statusErpOtm = "Los sistemas ERP y OTM en este momento estan fuera de servicio, reeintentelo mas tarde.";

        if (!$request->only('PaidStatus')) {
            return response()->json(['message' => 'Parametro no reconocido'], 401);
        }
        try {

            $user = DB::table('relationship')
                ->leftJoin('users', 'users.id', '=', 'relationship.user_id')
                ->where('relationship.user_assigne_id',  Auth::user()->id)
                ->where('relationship.deleted_at', '=', null)
                ->select('users.number_id')
                ->first();

            $number_id  = $user == null ? Auth::user()->number_id : $user->number_id;

            $params = [
                'q'        => "(TaxpayerId = '{$number_id}')",
                'limit'    => '200',
                'fields'   => 'SupplierNumber',
                'onlyData' => 'true'
            ];
            $response = OracleRestErp::procurementGetSuppliers($params);

            $res = $response->json();

            if ($res['count'] == 0) {
                return response()->json(['message' => 'No se encontro el proveedor'], 404);
            }

            $SupplierNumber =  (float)$res['items'][0]['SupplierNumber'];

            $params      =  [
                'limit'    => '20',
                'fields'   => 'Supplier,InvoiceId,InvoiceNumber,SupplierNumber,Description,InvoiceAmount,PaymentMethod,CanceledFlag,InvoiceDate,PaidStatus,AmountPaid,InvoiceType,ValidationStatus,AccountingDate,DocumentCategory,DocumentSequence,SupplierSite,Party,PartySite;invoiceInstallments:InstallmentNumber,UnpaidAmount,DueDate,GrossAmount,BankAccount',
                'onlyData' => 'true',
                'orderBy' => 'AccountingDate:desc'
            ];
            try {

                $params['q'] = "(SupplierNumber = '{$SupplierNumber}') and (InvoiceDate BETWEEN '{$request->startDate}' and '{$request->endDate}')";

                $invoice = OracleRestErp::getInvoiceSuppliers($params);

                if ($invoice['count'] == 0) {
                    if (!empty($request->InvoiceType)) {
                        return response()->json(['response' => 'No se encontraron facturas ' . trans('locale.' . $request->PaidStatus) . ' con el tipo de factura ' . trans('locale.' . $request->InvoiceType), 'status' => '404']);
                    } else {
                        return response()->json(['response' => 'No se encontraron facturas ' . trans('locale.' . $request->PaidStatus), 'status' => '404']);
                    }
                }

                $invoce =  $invoice->json();

                return response()->json(['response' => $invoce['items'], 'status' => '200']);
            } catch (\Throwable $th) {
                Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
                return response()->json(['response' => 'Algo fallo con la comunicacion']);
            }
            return response()->json(['response' => $res['items'], 'status' => '200']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Algo fallo con la comunicacion']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function searchInvoices(Request $request)
    {
        $limit = (int) ($request->InvoiceLimit ?: 20);
        $page  = max(1, (int) ($request->page ?: 1));
        $offset = ($page - 1) * $limit;

        $params      =  [
            'limit'    => $limit,
            'offset'   => $offset,
            'totalResults' => 'true',
            'fields'   => 'Supplier,SupplierNumber,InvoiceId,InvoiceNumber,InvoiceDate,PaidStatus,InvoiceAmount,CanceledFlag,AmountPaid;invoiceInstallments:UnpaidAmount,GrossAmount,DueDate',
            'onlyData' => 'true',
            'orderBy'  => 'InvoiceDate:desc'
        ];

        $NumberInvoice = ($request->TipoF == 'M') ? $request->TipoF . $request->InvoiceNumber : $request->InvoiceNumber;

        try {
            $isGestor = optional(optional(Auth::user())->rol)->role_id == 4;

            $conditions = [];

            if (!$isGestor) {
                $ownSupplierNumber = $this->resolveOwnSupplierNumber();
                if ($ownSupplierNumber === null) {
                    return response()->json(['success' => false, 'data' => 'No se encontro el proveedor'], 404);
                }
                $conditions[] = "(SupplierNumber = '{$ownSupplierNumber}')";
            } elseif (!empty($request->SupplierNumber)) {
                $conditions[] = "(SupplierNumber = '{$this->odataEscape($request->SupplierNumber)}')";
            }

            $core = in_array($request->core, ['=', '>', '<', '>=', '<=', '!='], true) ? $request->core : '=';

            // Solo se agrega cada condicion cuando el frontend realmente envio un valor
            if (!empty($NumberInvoice)) {
                $conditions[] = "(InvoiceNumber = '{$this->odataEscape($NumberInvoice)}')";
            }
            if (!empty($request->InvoiceDate)) {
                $conditions[] = "(InvoiceDate {$core} '{$this->odataEscape($request->InvoiceDate)}')";
            }
            if (!empty($request->CanceledFlag)) {
                $conditions[] = "(CanceledFlag = '{$this->odataEscape($request->CanceledFlag)}')";
            }
            if (!empty($request->PaidStatus)) {
                $conditions[] = "(PaidStatus = '{$this->odataEscape($request->PaidStatus)}')";
            }
            if (!empty($request->InvoiceType)) {
                $conditions[] = "(InvoiceType = '{$this->odataEscape($request->InvoiceType)}')";
            }
            if (!empty($request->ValidationStatus)) {
                $conditions[] = "(ValidationStatus = '{$this->odataEscape($request->ValidationStatus)}')";
            }
            if (!empty($request->startDate) && !empty($request->endDate)) {
                $conditions[] = "(InvoiceDate BETWEEN '{$this->odataEscape($request->startDate)}' and '{$this->odataEscape($request->endDate)}')";
            }

            $params['q'] = implode(' and ', $conditions);

            //dd($params);

            $invoice = OracleRestErp::getInvoiceSuppliers($params);
            $actions = UserTracking::actionsTracking($request->PaidStatus);
            $detail  = UserTracking::detailTracking($request->PaidStatus);

            $ip = GetClientIp::getUserIpAddress();

            UserTracking::createTracking($actions, $detail, $ip, [
                'limit'                     => $request->InvoiceLimit,
                'invoiceType_numberInvoice' => $request->TipoF . " " . $request->InvoiceNumber,
                'CanceledFlag'              => $request->CanceledFlag,
                'PaidStatus'                => $request->PaidStatus,
                'InvoiceType'               => $request->InvoiceType,
                'ValidationStatus'          => $request->ValidationStatus,
                'InvoiceDate'               => $request->startDate . " " . $request->endDate,
            ]);

            if ($invoice['count'] == 0) {

                if (!empty($request->InvoiceType)) {
                    return response()->json(['success' => false, 'data' => 'No se encontraron facturas ' . trans('locale.' . $request->PaidStatus) . ' con el tipo de factura ' . trans('locale.' . $request->InvoiceType), 'total' => 0, 'hasMore' => false]);
                } else {
                    if ($request->PaidStatus == 'Pagada parcialmente') {
                        return response()->json(['success' => false, 'data' => 'No se encontraron facturas con novedades', 'total' => 0, 'hasMore' => false]);
                    }
                    return response()->json(['success' => false, 'data' => 'No se encontraron facturas ' . $request->PaidStatus, 'total' => 0, 'hasMore' => false]);
                }
            }
            $invoce =  $invoice->json();

            $total = $invoce['totalResults'] ?? $invoce['count'] ?? count($invoce['items']);
            $hasMore = ($offset + count($invoce['items'])) < $total;

            return response()->json(['success' => true, 'data' => $invoce['items'], 'total' => $total, 'hasMore' => $hasMore]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
            $actions = UserTracking::actionsTracking($request->PaidStatus);
            return response()->json(['success' => false, 'data' => 'Algo fallo con la comunicacion']);
        }
    }

    public function totalsByStatus(Request $request)
    {
        try {

            $supplier = $this->resolveOwnSupplierNumber();

            if ($supplier === null) {
                return response()->json(['success' => false, 'data' => 'No se encontro el proveedor'], 404);
            }

            $params = [
                'q'        => "(SupplierNumber = '{$supplier}') and (CanceledFlag = false) and (invoiceInstallments.UnpaidAmount <> 0)",
                'fields'   => 'invoiceInstallments:UnpaidAmount;PaidStatus,InvoiceNumber',
                'onlyData' => 'true',
                'limit'    => '500'
            ];

            $res = OracleRestErp::getInvoiceSuppliers($params);

            $response = $res->object();

            // Group and sum UnpaidAmount by PaidStatus (negative values, e.g., -200, subtract from the group total)
            $totalsByPaidStatus = [];
            $countsByPaidStatus = [];

            foreach ($response->items as $item) {
                $status = $item->PaidStatus;

                // One invoice can have multiple installments (invoiceInstallments); sum them all
                $invoiceUnpaidAmount = 0;
                foreach ($item->invoiceInstallments as $installment) {
                    $invoiceUnpaidAmount += $installment->UnpaidAmount;
                }

                $totalsByPaidStatus[$status] = ($totalsByPaidStatus[$status] ?? 0) + $invoiceUnpaidAmount;

                // Count once per invoice (item), regardless of how many installments it has
                $countsByPaidStatus[$status] = ($countsByPaidStatus[$status] ?? 0) + 1;
            }

            $collection = [];

            foreach ($request->PaidStatus as $key => $PaidStatus) {
                $collection[$key] = [
                    $PaidStatus => $totalsByPaidStatus[$PaidStatus] ?? 0,
                    "count $PaidStatus" => $countsByPaidStatus[$PaidStatus] ?? 0,
                ];
            }

            return response()->json(['success' => true, 'data' => $collection]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
            return response()->json(['message' => 'Algo fallo con la comunicacion - Metodo totalsByStatus']);
        }
    }

    public function getSupplierNumber(Request $request)
    {
        try {
            $user = DB::table('relationship')
                ->leftJoin('users', 'users.id', '=', 'relationship.user_id')
                ->where('relationship.user_assigne_id',  Auth::user()->id)
                ->where('relationship.deleted_at', '=', null)
                ->select('users.number_id', 'users.document_type')
                ->first();

            $number_id  = $user == null ? Auth::user()->number_id : $user->number_id;
            $documentType = $user == null ? Auth::user()->document_type : $user->document_type;

            $document = ($documentType == "NIT") ? RequestNit::getNit($number_id) : $number_id;

            $documentCandidates = [(string) $document];
            $normalizedNumberId = preg_replace('/\D+/', '', (string) $number_id);

            if ($documentType == "NIT") {
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

            $SupplierNumber = null;
            foreach ($documentCandidates as $candidate) {
                $params = [
                    'q'        => "(TaxpayerId = '{$candidate}')",
                    'limit'    => '200',
                    'fields'   => 'SupplierNumber',
                    'onlyData' => 'true'
                ];
                $response = OracleRestErp::procurementGetSuppliers($params);
                $res = $response->json();

                if (is_array($res) && !empty($res['items']) && isset($res['items'][0]['SupplierNumber'])) {
                    $SupplierNumber = (float) $res['items'][0]['SupplierNumber'];
                    break;
                }
            }

            if ($SupplierNumber === null) {
                Log::warning(__METHOD__ . '. No se encontró el proveedor para el documento: ' . $document);
                session()->flash('message', 'No se encontro el proveedor');
                return back();
            }

            return response()->json(['success' => true, 'data' => $SupplierNumber]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
            return response()->json(['message' => 'Algo fallo con la comunicacion']);
        }
    }

    public function SelectSupplierNumber(Request $request)
    {
        // return response()->json(['success' => true, 'data' => $request->input('q')]);

        try {

            $params = [
                'q'        => "Supplier LIKE '%{$this->odataEscape($request->input('q'))}%' OR TaxpayerId LIKE '%{$this->odataEscape($request->input('q'))}%'", //*Filtrar por el nombre y numero de cedula
                'limit'    => '25',
                'fields'   => 'Supplier,SupplierNumber',
                'onlyData' => 'true'
            ];
            $response = OracleRestErp::procurementGetSuppliers($params);
            $res = $response->json();
            //? Validanos que nos traiga el proveedor
            if ($res['count'] == 0) {
                // return response()->json(['message' => 'No se encontro el proveedor'], 404);
                session()->flash('message', 'No se encontro el proveedor');
                return back();
            }
            // $SupplierNumber =  (float)$res['items'][0]['SupplierNumber'];

            return response()->json($res['items']);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
            return response()->json(['message' => 'Algo fallo con la comunicacion']);
        }
    }

    public function getInvoiceLines(Request $request)
    {
        $dataInvoiceFull = [];
        try {
            $params      =  [
                'fields'   => 'Supplier,InvoiceId,InvoiceNumber,SupplierNumber,Description,InvoiceAmount,PaymentMethod,CanceledFlag,InvoiceDate,PaidStatus,AmountPaid,InvoiceType,ValidationStatus,AccountingDate,DocumentCategory,DocumentSequence,SupplierSite,Party,PartySite;appliedPrepayments:InvoiceNumber,AppliedAmount;invoiceInstallments:InstallmentNumber,UnpaidAmount,DueDate,GrossAmount,BankAccount',
                'onlyData' => 'true',
            ];

            $params['q'] = "(InvoiceId = '{$this->odataEscape($request->InvoiceId)}')";
            $invoice = OracleRestErp::getInvoiceSuppliers($params);
            $invoce =  $invoice->object()->items;

            $ownSupplierNumber = $this->resolveOwnSupplierNumber();
            if (empty($invoce) || $ownSupplierNumber === null || (float) $invoce[0]->SupplierNumber !== $ownSupplierNumber) {
                return response()->json(['success' => false, 'data' => 'Algo fallo con la comunicacion'], 403);
            }

            $params = [
                'fields' => 'PaymentDate',
                'finder' => 'PaidInvoicesFinder;InvoiceNumber = ' . $invoce[0]->InvoiceNumber,
                'onlyData' => 'true',
                'limit' => '1'
            ];
            $invoiceF = OracleRestErp::getPayablesPayments($params);
            $invoceF =  $invoiceF->object()->items;

            if ($invoceF == [] && $invoce[0]->PaidStatus == "Pagadas") {

                $params = [
                    'fields' => 'PaymentDate',
                    'finder' => 'PaidInvoicesFinder;InvoiceNumber = ' . $invoce[0]->appliedPrepayments[0]->InvoiceNumber,
                    'onlyData' => 'true',
                ];
                $invoiceF = OracleRestErp::getPayablesPayments($params);
                $invoceF =  $invoiceF->object()->items;
            }
            if ($invoceF == [] && $invoce[0]->PaidStatus != "Pagadas") {

                $invoceF =  [
                    ['PaymentDate' => '0000-00-00'],
                ];
            }
            // return response()->json(['success' => true, 'data' => $invoceF[0]['PaymentDate']]);

            $params = [
                'limit'    => '200',
                'fields'   => 'LineNumber,LineAmount,AccountingDate,Description,BudgetDate,LineType',
                'onlyData' => 'true'
            ];

            $reques = OracleRestErp::getInvoicesLines($invoce[0]->InvoiceId, $params);
            $requesData = $reques->object()->items;

            $params = [
                'limit' => '10',
                'fields' => 'HoldName,HoldReason,HeldBy,HoldDate,ReleaseName',
                'onlyData' => 'true'
            ];
            $params['q'] = "(InvoiceNumber = '{$invoce[0]->InvoiceNumber}')";

            $reques = OracleRestErp::getinvoiceHolds($params);
            $retenciones = $reques->object()->items;

            $dataInvoiceFull = [
                'invoiceData'   => $invoce,
                'invoiceFechaPago' => $invoceF,
                'invoiceLines'  => $requesData,
                'holds' => array($retenciones),
            ];
            return response()->json(['success' => true, 'data' => $dataInvoiceFull]);
        } catch (Exception $e) {
            Log::error(__METHOD__ . '. General error: ' . $e->getMessage());
            return response()->json(['message' => 'Algo fallo con la comunicacion']);
        }
    }

    public function consultaOTM(Request $request, $id)
    {
        try {
            $userData = User::find($id);
            if (!$userData) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Usuario no encontrado.',
                    ], 404);
                }

                return redirect()->route('usuario.index')->with('error', 'Usuario no encontrado.');
            }
            $document = ($userData->document_type == "NIT") ? RequestNit::getNit($userData->number_id) : $userData->number_id;

            $arrayResultLocal = [
                'number_id'     => $document,
                'name'          => $userData->name,
                'email'         => $userData->email,
                'phone'         => $userData->phone,
                'estado'        => $userData->status,
            ];

            $params = [
                'limit'   => '1',
                'expand'  => 'contacts',
                'showPks' => 'true',
                'fields'  => 'locationXid,locationName,isActive,contacts'
            ];

            $response = OracleRestOtm::getLocationsCustomers($document, $params);
            if ($response->successful()) {
                $result          = $response->object();
                if (!is_object($result)) {
                    $result = null;
                }

                $result_contacts = null;
                $contactItems = $result->contacts->items ?? null;
                if (is_array($contactItems) && count($contactItems) > 0) {
                    $result_contacts = $contactItems[0];
                } elseif (is_object($contactItems)) {
                    $result_contacts = $contactItems;
                }

                $arrayResultOtm = [
                    'locationXid'  => $result->locationXid ?? null,
                    'fullName'     => $result->locationName ?? null,
                    'isActive'     => $result->isActive ?? null,
                    'emailAddress' => $result_contacts->emailAddress ?? null,
                    'phone'        => $result_contacts->phone1 ?? null,
                ];
            } else {
                Log::error(__METHOD__ . '. OTM response error.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                $arrayResultOtm =
                    [
                        'locationXid'  => null,
                        'fullName'     => null,
                        'isActive'     => null,
                        'emailAddress' => null,
                        'phone'        => null,
                    ];
            }

            $paramsErp = [
                'q'        => "(TaxpayerId = '{$this->odataEscape($document)}')",
                'limit'    => '200',
                'fields'   => 'TaxpayerId,Supplier,SupplierNumber;addresses:Email,PhoneNumber,Status',
                'onlyData' => 'true'
            ];

            $responseErp = OracleRestErp::procurementGetSuppliers($paramsErp);
            $responseDataArrayErp = $responseErp->object();
            if (is_object($responseDataArrayErp) && isset($responseDataArrayErp->count) && $responseDataArrayErp->count > 0) {
                $resultErp = data_get($responseDataArrayErp, 'items.0');
                $resultAddressErp = data_get($resultErp, 'addresses.0');
                $arrayResultErp =
                    [
                        'TaxpayerId'   => data_get($resultErp, 'TaxpayerId'),
                        'fullName'     => data_get($resultErp, 'Supplier'),
                        'isActive'     => data_get($resultAddressErp, 'Status'),
                        'emailAddress' => data_get($resultAddressErp, 'Email'),
                        'phone'        => data_get($resultAddressErp, 'PhoneNumber')
                    ];
            } else {
                $arrayResultErp =
                    [
                        'TaxpayerId'   => null,
                        'fullName'     => null,
                        'isActive'     => null,
                        'emailAddress' => null,
                        'phone'        => null
                    ];
            }
            $viewData = [
                'arrayResultLocal' => $arrayResultLocal,
                'arrayResultErp'   => $arrayResultErp,
                'arrayResultOtm'   => $arrayResultOtm
            ];

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('usuarios.partials.consulta-afiliado', $viewData)->render(),
                ]);
            }

            return view('usuarios.consultar', $viewData);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo cargar la validación del afiliado. Revise los logs para más detalle.',
                ], 500);
            }

            session()->flash('message', "Special message goes here");
            return back();
        }
    }

    public function proveedorEncargado(Request $request)
    {
        if ($request->userId != '') {

            $relationship = DB::table('relationship')
                ->where('relationship.user_assigne_id',  $request->userId)
                ->where('relationship.deleted_at', '=', null)
                ->first();

            $user = $relationship ? User::find($relationship->user_id) : null;
            return response()->json(['success' => true, 'data' => $user]);
        }
        return response()->json(['success' => false, 'data' => 'Algo fallo con la comunicacion']);
    }

    /**
     * The console command description.
     *
     * @var shipmentXid     = shipmentXid,
     * @var attribute9      = supplier_Gid,
     * @var attribute10     = placa_Gid,
     * @var attribute11     = placa_trailer_Gid,
     * @var totalActualCost = Costo total actual,
     * @var numStops        = numero de paradas,
     */

    protected function getShipmentOtm(Request $request)
    {

        try {
            $limit = (int) ($request->ShipmentsLimit ?: 20);
            $page  = max(1, (int) ($request->page ?: 1));
            $offset = ($page - 1) * $limit;

            // $params = self::parametros();
            $params = [
                'onlyData' => 'true',
                'expand' => 'statuses',
                'limit'    => $limit,
                'offset'   => $offset,
                'totalResults' => 'true',
                'orderBy' => 'insertDate:desc'
            ];
            // $params['q'] = 'specialServices.specialServiceGid eq "' . 'TCL.' . $request->number_id . '" and statuses.statusTypeGid eq "TCL.MANIFIESTO_CUMPLIDO"';
            $params['q'] = 'specialServices.specialServiceGid eq "' . 'TCL.' . $request->number_id . '" and statuses.statusTypeGid eq "TCL.MANIFIESTO_CUMPLIDO"';
            $params['fields'] = 'shipmentXid,shipmentName,totalActualCost,totalWeightedCost,numStops,attribute9,attribute10,attribute11,insertDate,statuses.statusValueGid';
            $response = OracleRestOtm::getShipments($params);

            if ($response->status() == 401) {
                return response()->json(['success' => false, 'data' => 'Algo fallo con la comunicacion']);
            }

            $actions = UserTracking::actionsTracking('FT');
            $detail = UserTracking::detailTracking('FT');
            $ip = GetClientIp::getUserIpAddress();
            UserTracking::createTracking($actions, $detail, $ip, '');

            $result = $response->json();
            $items = $result['items'] ?? [];
            $total = $result['totalResults'] ?? $result['count'] ?? count($items);
            $hasMore = ($offset + count($items)) < $total;

            return response()->json(['success' => true, 'data' => $items, 'total' => $total, 'hasMore' => $hasMore]);
        } catch (Exception $e) {
            Log::error(__METHOD__ . '. General error: ' . $e->getMessage());
            return  $e->getMessage();
        }
    }

    /**
     * The console command description.
     *
     * @var statusTypeGid  = Cabezera del estado,
     * @var statusValueGid = Estado,
     */

    // protected function getShipmentStatusOtm($shipmentGid)
    // {
    // }

    public function getShipmentDetalle(Request $request)
    {
        try {
            $shipmentXid = $request->input('invoice');
            if (empty($shipmentXid)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Identificador del manifiesto requerido.'
                ], 422);
            }

            $response = ReporteRestOtm::manifiestoSoapOtmReport($shipmentXid);

            if (is_array($response) && array_key_exists('success', $response) && $response['success'] === false) {
                return response()->json([
                    'success' => false,
                    'message' => $response['message'] ?? 'No fue posible obtener el detalle del manifiesto.'
                ], 502);
            }

            return response()->json(['success' => true, 'data' => $response]);
        } catch (Exception $e) {
            Log::error(__METHOD__ . '. General error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'No fue posible obtener el detalle del manifiesto.'
            ], 500);
        }
    }
}
