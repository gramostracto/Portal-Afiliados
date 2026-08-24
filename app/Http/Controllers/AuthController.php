<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\OracleRestErp;
use App\Http\Helpers\OracleRestOtm;
use App\Http\Helpers\ReporteRestOtm;
use App\Http\Helpers\RequestNit;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Knuckles\Scribe\Attributes\QueryParam;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    use ApiResponser;

    #[QueryParam("email", "string", required: true)]
    #[QueryParam("document_type", "string", required: false)]
    #[QueryParam("number_id", "integer", required: true)]
    #[QueryParam("name", "string", required: true)]
    #[QueryParam("phone", "integer", required: true)]
    #[QueryParam("Photo", "file", required: false)]
    #[QueryParam("photo_id", "file", required: false)]
    #[QueryParam("password", "string", required: true)]
    public function register(Request $request)
    {
        $request->Validator([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'indisposable'],
            'number_id' => ['required', 'numeric', 'unique:users',],
            'phone' => ['required', 'digits_between:7,11'],
            'document_type' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $roles = Role::get();

        if (!empty($request['photo'])) {
            $extensionPerfil = $request['photo']->getClientOriginalExtension();
        }
        if (!empty($request['photo_id'])) {
            $extensionIdentif = $request['photo_id']->getClientOriginalExtension();
        }
        $id = User::insertGetId([
            'name'                  => $request['name'],
            'email'                 => $request['email'],
            'number_id'        => $request['number_id'],
            'document_type'          => $request['document_type'],
            'phone'              => $request['phone'],
            'status'                => 'NUEVO',
            'password'              => Hash::make($request['password']),
        ]);


        $usuario = User::findOrFail($id);
        $usuario->roles()->sync($roles[1]->id);

        //? Guardamos los archivos cargados y capturamos la ruta
        if (!empty($data['photo'])) {
            $carpetaphoto = "proveedores/$id/perfil";
            Storage::putFileAs("public/$carpetaphoto", $data['photo'], 'photo_perfil.' . $extensionPerfil);
        }
        if (!empty($data['photo_id'])) {
            $carpetaidentif = "proveedores/$id/identificacion";
            Storage::putFileAs("public/$carpetaidentif", $data['photo_id'], 'photo_documento.' . $extensionIdentif);
        }

        //? Actualizamos el usuario para agregarle la ruta de los archivos en los campos asignados
        if (!empty($data['photo']) && !empty($data['photo_id'])) {
            User::where('id', $id)
                ->update([
                    'photo'                 => "storage/$carpetaphoto/photo_perfil.$extensionPerfil",
                    'photo_id'   => "storage/$carpetaidentif/photo_documento.$extensionIdentif",
                ]);
        }
        if (!empty($data['photo'])) {

            User::where('id', $id)
                ->update([
                    'photo'   => "storage/$carpetaphoto/photo_perfil.$extensionPerfil",
                ]);
        }
        if (!empty($data['photo_id'])) {

            User::where('id', $id)
                ->update([
                    'photo_id'   => "storage/$carpetaidentif/photo_documento.$extensionIdentif",
                ]);
        }

        return response()->json([
            'status' => '200',
            'message' => "Usuario Registrado correctamente",
            'data' => [
                'token' => $usuario->createToken('API Token')->plainTextToken
            ]
        ]);
    }

    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("id", "user", "int", required: true)]
    public function edit(Request $request)
    {
        $user     = User::find($request->id);
        $roles    = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return response()->json([
            'status' => '200',
            'response' => compact('user', 'roles', 'userRole'),
        ]);
    }

    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("id", "string", required: false)]
    #[QueryParam("email", "string", required: false)]
    #[QueryParam("document_type", "string", required: false)]
    #[QueryParam("number_id", "integer", required: false)]
    #[QueryParam("name", "string", required: false)]
    #[QueryParam("phone", "integer", required: false)]
    #[QueryParam("Photo", "file", required: false)]
    #[QueryParam("rol", "string", required: false)]
    #[QueryParam("photo_id", "file", required: false)]
    #[QueryParam("password", "string", required: false)]

    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('/usuario.index')) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $id,
            'phone'    => 'required|numeric',
            'password' => 'same:confirm-password',
            'roles'    => 'required'
        ]);

        $input = Arr::only($request->all(), ['name', 'email', 'phone', 'password']);
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return response()->json([
            'status' => '200',
            'message' => "Usuario Actualizado correctamente",
        ]);
    }

    public function profile()
    {
        return auth()->user();
    }

    public function role()
    {
        $roles = Role::pluck('name', 'name')->all();
        return response()->json([
            'response' => $roles,
        ]);
    }

    #[QueryParam("name", "", "string", required: true)]
    #[QueryParam("permission", "", "string", required: true)]
    public function createRole(Request $request)
    {
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
    }

    #[QueryParam("id", "role identifier", "string", required: true)]
    public function editRole(Request $request)
    {
        $role = Role::find($request->id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $request->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return response()->json(['success' => true, 'data' => compact('role', 'permission', 'rolePermissions')]);
    }

    #[QueryParam("name", "", "string", required: false)]
    #[QueryParam("permission", "", "string", required: false)]
    #[QueryParam("id", "role identifier", "string", required: false)]
    public function updateRole(Request $request, $id)
    {

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return response()->json([
            'status' => '200',
            'message' => "Rol Actualizado correctamente",
        ]);
    }

    public function permission()
    {
        $permission = Permission::get();
        return response()->json([
            'response' => $permission,
        ]);
    }


    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("userId", "user", "int", required: true)]
    public function delete(Request $request)
    {
        DB::table('users')->where('id', $request->userId)->delete();
        return response()->json([
            'success' => true,
            'message' => "Usuario eliminado correctamente"
        ]);
    }

    #[QueryParam("email", "string", required: true)]
    #[QueryParam("password", "password", required: true)]
    public function login(Request $request)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid login details'], 403);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $response = User::where('email', $request->email)
                ->first();


            if ($response->status == 'CONFIRMADO') {
                return response()->json([
                    'response' => $response,
                    'acces_token' => auth()->user()->createToken('API Token')->plainTextToken,
                    'token_type' => 'Bearer',
                ]);
            } else {
                return response()->json(['error' => 'Los datos del usuario aún no han sido validados'], 401);
            }
        }
    }

    #[QueryParam("Token", "Authorization", "string", required: true)]
    public function logout()
    {
        // Revoke all tokens...
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'successfully logged out']);
    }

    #[QueryParam("userId", "Kinship id, to consult the provider to which it is associated", "integer", required: true)]
    public function proveedorEncargado(Request $request)
    {
        if ($request->userId != '') {

            if ((int) $request->userId !== (int) Auth::user()->id) {
                return response()->json(['success' => false, 'data' => 'No autorizado para consultar este usuario'], 403);
            }

            $relationship = DB::table('relationship')
                ->where('relationship.user_assigne_id',  $request->userId)
                ->where('relationship.deleted_at', '=', null)
                ->first();

            $user = $relationship ? User::find($relationship->user_id) : null;

            return response()->json(['success' => true, 'data' => $user]);
        }
        return response()->json(['success' => false, 'data' => 'Algo fallo con la comunicacion']);
    }

    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("id", "user", "int", required: true)]
    public function consultaOTM(Request $request)
    {
        try {
            if ((int) $request->id !== (int) Auth::user()->id) {
                return response()->json(['success' => false, 'data' => 'No autorizado para consultar este usuario'], 403);
            }

            $userData = User::find($request->id);
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
                $result_contacts = $response->object()->contacts->items[0];
                $arrayResultOtm = [
                    'locationXid'  => $result->locationXid,
                    'fullName'     => $result->locationName,
                    'isActive'     => $result->isActive,
                    'emailAddress' => isset($result_contacts->emailAddress) ? $result_contacts->emailAddress : null,
                    'phone'        => isset($result_contacts->phone1) ? $result_contacts->phone1 : null,
                ];
            } else {
                Log::error(__METHOD__ . '. General error: ' . $response->body());
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
                'q'        => "(TaxpayerId = '{$document}')",
                'limit'    => '200',
                'fields'   => 'TaxpayerId,Supplier,SupplierNumber;addresses:Email,PhoneNumber,Status',
                'onlyData' => 'true'
            ];

            $responseErp = OracleRestErp::procurementGetSuppliers($paramsErp);
            $responseDataArrayErp = $responseErp->object();
            if ($responseDataArrayErp->count > 0) {
                $resultErp = $responseDataArrayErp->items[0];
                $resultAddressErp = $resultErp->addresses[0];
                $arrayResultErp =
                    [
                        'TaxpayerId'   => $resultErp->TaxpayerId,
                        'fullName'     => $resultErp->Supplier,
                        'isActive'     => $resultAddressErp->Status,
                        'emailAddress' => $resultAddressErp->Email,
                        'phone'        => $resultAddressErp->PhoneNumber
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
            return response()->json(['success' => true, 'data' => [
                'arrayResultLocal' => $arrayResultLocal,
                'arrayResultErp'   => $arrayResultErp,
                'arrayResultOtm'   => $arrayResultOtm
            ]]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
            session()->flash('message', "Special message goes here");
            return back();
        }
    }

    /**
     * Resuelve el SupplierNumber real del usuario autenticado en ERP, ignorando cualquier
     * SupplierNumber que llegue en el request (evita IDOR: consultar facturas de otro proveedor).
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

    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("PaidStatus", "It is to check the paid status of the invoice. [Pagadas, Impagado, Pagada parcialmente]", "string", required: true)]
    #[QueryParam("FlagStatus", "It is to check if the invoice is valid or canceled [true, false]", "string", required: true)]
    public function invoiceSuppliers(Request $request)
    {
        // return response()->json(['success' => true, 'data' => 'hola']);
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

            //? Validanos que nos traiga el proveedor
            if ($res['count'] == 0) {
                return response()->json(['message' => 'No se encontro el proveedor'], 404);
            }

            $SupplierNumber =  (float)$res['items'][0]['SupplierNumber'];

            $params      =  [
                'limit'    => '20',
                'fields'  => 'InvoiceNumber,InvoiceDate,PaidStatus,InvoiceAmount,AmountPaid;invoiceInstallments:UnpaidAmount,GrossAmount,DueDate',
                'onlyData' => 'true',
                'orderBy' => 'InvoiceDate:desc'
            ];

            try {
                $params['q'] = "(SupplierNumber = '{$SupplierNumber}') and (PaidStatus = '{$request->PaidStatus}') and (CanceledFlag = '{$request->FlagStatus}')";

                $invoice = OracleRestErp::getInvoiceSuppliers($params);

                //? Validamos que nos traiga las facturas
                if ($invoice['count'] == 0) {

                    if (!empty($request->InvoiceType)) {
                        return response()->json(['success' => false, 'data' => 'No se encontraron facturas ' . $request->PaidStatus . ' con el tipo de factura ' . $request->InvoiceType]);
                    } else {
                        return response()->json(['success' => false, 'data' => 'No se encontraron facturas ' . $request->PaidStatus]);
                    }
                }

                $invoce =  $invoice->json();
                // return response()->json(['success' => true, 'data' => $invoce]);

                return response()->json(['success' => true, 'data' => $invoce['items']]);
                // return response()->json(array('semestres' => $semestres), 200);
            } catch (\Throwable $th) {
                Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
                return response()->json(['success' => false, 'data' => 'Algo fallo con la comunicacion']);
            }
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
            return response()->json(['success' => false, 'data' => 'Algo fallo con el proveedor']);
        }

        //aqui trabajamos con name de las tablas de users
        // $roles = Role::pluck('name','name')->all();
        // return view('usuarios.crear',compact('roles'));
    }

    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("InvoiceNumber", "", "int", required: true)]
    public function getInvoiceLines(Request $request)
    {
        $dataInvoiceFull = [];
        try {
            $params      =  [
                'fields'   => 'Supplier,InvoiceId,InvoiceNumber,SupplierNumber,Description,InvoiceAmount,PaymentMethod,CanceledFlag,InvoiceDate,PaidStatus,AmountPaid,InvoiceType,ValidationStatus,AccountingDate,DocumentCategory,DocumentSequence,SupplierSite,Party,PartySite;invoiceInstallments:InstallmentNumber,UnpaidAmount,DueDate,GrossAmount,BankAccount',
                'onlyData' => 'true',
            ];

            $params['q'] = "(InvoiceNumber = '{$this->odataEscape($request->InvoiceNumber)}')";
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
            ];
            $invoiceF = OracleRestErp::getPayablesPayments($params);
            $invoceF =  $invoiceF->object()->items;

            if ($invoceF == []) {
                $invoceF = array(['PaymentDate' => 'Indefinida']);
            }

            $params = [
                'limit'    => '200',
                'fields'   => 'LineNumber,LineAmount,AccountingDate,Description,BudgetDate,LineType',
                'onlyData' => 'true'
            ];

            $reques = OracleRestErp::getInvoicesLines($invoce[0]->InvoiceId, $params);
            $requesData = $reques->object()->items;

            $dataInvoiceFull = [
                'invoiceLines'  => $requesData,
                'invoiceData'   => $invoce,
                'invoiceFechaPago' => $invoceF,
            ];
            return response()->json(['success' => true, 'data' => $dataInvoiceFull]);
        } catch (Exception $e) {
            Log::error(__METHOD__ . '. General error: ' . $e->getMessage());
            return response()->json(['message' => 'Algo fallo con la comunicacion']);
        }
    }

    protected function parametros()
    {
        $params = [
            'onlyData' => 'true',
            'limit'    => '20',
            'orderBy' => 'insertDate:desc'
        ];
        return $params;
    }

    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("number_id", "user identification number", "integer", required: true)]
    protected function getShipmentOtm(Request $request)
    {

        try {
            $params = self::parametros();
            $params['q'] = 'specialServices.specialServiceGid eq "' . 'TCL.' . $request->number_id . '" and statuses.statusValueGid eq "TCL.MANIFIESTO_CUMPL_NUEVO"';
            $params['fields'] = 'shipmentXid,shipmentName,totalActualCost,totalWeightedCost,numStops,attribute9,attribute10,attribute11,insertDate';
            $request = OracleRestOtm::getShipments($params);

            return response()->json(['success' => true, 'data' => $request->object()->items]);
        } catch (Exception $e) {
            Log::error(__METHOD__ . '. General error: ' . $e->getMessage());
            return  $e->getMessage();
        }
    }

    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("shipmentXid", "Transport invoice identifier", "int", required: true)]
    public function getShipmentDetalle(Request $request)
    {
        try {
            $shipmentXid = $request->input('shipmentXid');
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

    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("number_id", "identification number", "int", required: true)]
    public function supplierNumber(Request $request)
    {
        try {
            $params = [
                'q'        => "(TaxpayerId = '{$this->odataEscape($request->number_id)}')",
                'limit'    => '200',
                'fields'   => 'SupplierNumber',
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
            $SupplierNumber =  (float)$res['items'][0]['SupplierNumber'];

            return response()->json(['success' => true, 'data' => $SupplierNumber]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
            return response()->json(['message' => 'Algo fallo con la comunicacion']);
        }
    }

    #[QueryParam("Token", "Authorization", "string", required: true)]
    #[QueryParam("PaidStatus", "Impagado", "string", required: true)]
    #[QueryParam("SupplierNumber", "integer", required: true)]
    public function TotalAmount(Request $request)
    {

        try {
            $ownSupplierNumber = $this->resolveOwnSupplierNumber();
            if ($ownSupplierNumber === null) {
                return response()->json(['success' => false, 'data' => 'No se encontro el proveedor'], 404);
            }

            $PaidStatu = explode('"', $request->PaidStatus);
            $collection = [];
            foreach ($PaidStatu as $key => $PaidStatus) {

                $params = [
                    'q'        => "(SupplierNumber = '{$ownSupplierNumber}') and (CanceledFlag = false) and (PaidStatus ='{$this->odataEscape($PaidStatus)}')",
                    'fields'   => 'InvoiceAmount',
                    'onlyData' => 'true',
                    'limit'    => '500'

                ];
                $res = OracleRestErp::getInvoiceSuppliers($params);
                $response = $res->object();

                $total = 0;
                foreach ($response->items as $amountTotal) {
                    $total = $total + $amountTotal->InvoiceAmount;
                }
                $collection[$key] = [
                    $PaidStatus => $total,
                    "count $PaidStatus" => $response->count

                ];
            }


            return response()->json(['success' => true, 'data' => $collection]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
            return response()->json(['message' => 'Algo fallo con la comunicacion']);
        }
    }

    //Muestro el formulario para introducir el email
    public function email()
    {
        return view('auth.forgot-password');
    }

    //Genero y envío el enlace para restaurar la clave
    public function enlace(Request $request)
    {
        //Validación de email
        $request->validate([
            'email' => 'required|email|exists:usuarios',
        ]);

        //Generación de token y almacenado en la tabla password_resets
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        //Envío de email al usuario
        Mail::send('email.email', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Cambiar contraseña en CMS Laravel');
        });

        //Retorno
        return redirect('acceder')->with('success', 'Te hemos enviado un email a <strong>' . $request->email . '</strong> con un enlace para realizar el cambio de contraseña.');
    }

    //Muestro el formulario para cambiar la clave
    public function clave($token)
    {
        return view('auth.clave', ['token' => $token]);
    }

    //cambio la clave
    public function cambiar(Request $request)
    {
        //Valido datos
        $request->validate([
            'email' => 'required|email|exists:usuarios',
            'password' => 'required|min:8|max:16|confirmed',
            'password_confirmation' => 'required'
        ]);

        //Compruebo token válido
        $comprobarToken = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->first();
        if (!$comprobarToken) {
            return back()->withInput()->with('danger', 'El enlace no es válido');
        }

        //Actualizo password
        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        //Borro token para que no se pueda volver a usar
        DB::table('password_resets')->where(['email' => $request->email])->delete();

        //Retorno
        return redirect('acceder')->with('success', 'La contraseña se ha cambiado correctamente.');
    }
}
