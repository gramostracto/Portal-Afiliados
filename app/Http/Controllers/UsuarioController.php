<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//agregamos lo siguiente
use App\Http\Controllers\Controller;
use App\Http\Helpers\GetClientIp;
use App\Http\Helpers\sendEmailRequest;
use App\Http\Helpers\UserTracking;
use App\Models\Relationship;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;



class UsuarioController extends Controller
{
    use SoftDeletes;

    function __construct()
    {
        $this->middleware('permission:/usuario.index')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usuarios = User::with('rol.rol_nombre')
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->paginate(50);
        $roles = Role::orderBy('name')->pluck('name', 'name')->all();

        return view('usuarios.index', [
            'usuarios' => $usuarios,
            'roles' => $roles,
            'dashboard' => $this->getUsersDashboard(),
            'filters' => [
                'estado' => 'Todos',
                'role' => 'Todos',
                'name' => null,
                'number_id' => null,
                'limit' => 50,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('usuarios.crear', compact('roles'));
    }

    public function createUserAsociado(Request $request)
    {
        try {
            $user_relation = DB::table('relationship')->where('user_id', Auth::user()->id)->count();
            if ($user_relation <= 3) {

                //?Capturamos el id del user registrdo
                DB::transaction(function () use ($request) {
                    $user = User::create([
                        'name'      => $request->name,
                        'email'     => $request->email,
                        'document_type' => $request->document_type,
                        'number_id' => $request->identification,
                        'phone'     => $request->telefono,
                        'status'    => 'ASOCIADO',
                        'password'  => Hash::make($request['password']),
                    ]);
                    Relationship::create([
                        'user_id'         => Auth::user()->id,
                        'user_assigne_id' => $user->id,
                    ]);
                    //? le asignamos el rol
                    $user->roles()->sync(3);
                });

                $actions = UserTracking::actionsTracking('CUA');
                $ip = GetClientIp::getUserIpAddress();

                UserTracking::createTracking($actions, $request->identification, $ip, '');

                return response()->json(['success' => true]);
            }

            if ($user_relation == 4) {
                return response()->json(['success' => false]);
            }
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'indisposable'],
            'number_id' => ['required', 'numeric', 'unique:users',],
            'phone' => ['required', 'digits_between:7,11'],
            'document_type' => ['required'],
            'password' => 'required|same:confirm-password',
            'roles'    => 'required'
        ]);

        $input = $request->all();

        $input['status'] = $request->input('roles')[0] == 'ClienteHijo' ? 'ASOCIADO' : 'CONFIRMADO';
        $input['email_verified_at'] = now();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('usuarios.index');
    }

    public function filtros(Request $request)
    {
        $validated = $request->validate([
            'estado' => 'nullable|string',
            'role' => 'nullable|string',
            'name' => 'nullable|integer',
            'number_id' => 'nullable|string|max:30',
            'limit' => 'nullable|in:50,100,200',
        ]);

        $usuarios = User::with('rol.rol_nombre')
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc');

        // Filtro por estado
        if ($request->filled('estado') && $request->estado != 'Todos') {
            $usuarios->where('status', $request->estado);
        }

        if ($request->filled('role') && $request->role != 'Todos') {
            $usuarios->whereHas('roles', function ($query) use ($request) {
                $query->where('name', $request->role);
            });
        }

        // Filtro por número de identificación
        if ($request->filled('number_id')) {
            $usuarios->where('number_id', 'like', '%' . $request->number_id . '%');
        }

        // Filtro por nombre (si el número de identificación no está presente)
        if ($request->filled('name') && !$request->filled('number_id')) {
            $usuarios->where('id', $request->name);
        }

        // Paginación o obtener todos los resultados
        $users = $request->filled('limit') ? $usuarios->paginate(intval($request->limit)) : $usuarios->get();
        $roles = Role::orderBy('name')->pluck('name', 'name')->all();

        return view('usuarios.index', [
            'usuarios' => $users,
            'roles' => $roles,
            'dashboard' => $this->getUsersDashboard(),
            'filters' => array_merge([
                'estado' => 'Todos',
                'role' => 'Todos',
                'name' => null,
                'number_id' => null,
                'limit' => '',
            ], $validated),
        ]);
    }

    private function getUsersDashboard(): array
    {
        $statusTotals = User::select('status', DB::raw('count(*) as total'))
            ->whereNull('deleted_at')
            ->groupBy('status')
            ->pluck('total', 'status');

        $duplicateDocuments = User::withTrashed()
            ->select('number_id', DB::raw('count(*) as total'))
            ->whereNotNull('number_id')
            ->where('number_id', '!=', '')
            ->groupBy('number_id')
            ->having('total', '>', 1)
            ->orderByDesc('total')
            ->get();

        return [
            'active_total' => User::whereNull('deleted_at')->count(),
            'deleted_total' => User::onlyTrashed()->count(),
            'new_total' => $statusTotals->get('NUEVO', 0),
            'confirmed_total' => $statusTotals->get('CONFIRMADO', 0),
            'rejected_total' => $statusTotals->get('RECHAZADO', 0),
            'associated_total' => $statusTotals->get('ASOCIADO', 0),
            'without_role_total' => User::whereNull('deleted_at')->doesntHave('roles')->count(),
            'duplicate_documents_total' => $duplicateDocuments->count(),
            'duplicate_documents' => $duplicateDocuments->take(5),
        ];
    }

    public function edit($id)
    {
        $user     = User::find($id);
        $roles    = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('usuarios.editar', compact('user', 'roles', 'userRole'));
    }

    public function confirmarDatos($usuarioId, $estado)
    {
        $usuario = User::find($usuarioId);

        switch ($estado) {
            case 'aprobado':
                $usuario->update(['status' => 'CONFIRMADO']);
                break;
            case 'rechazado':
                $usuario->update(['status' => 'RECHAZADO']);
                break;
            default:
                # code...
                break;
        }

        sendEmailRequest::sendEmail($usuario->id, $estado, $usuario->email);

        return redirect('/portal/users');
        // return back();

        //? Actualizacion pendiente JOB
        // $details = [
        //     'name'   => $usuario->name,
        //     'email'  => $usuario->email,
        //     'status' => $estado
        // ];
        // dispatch(new SendRequestEmailJob($details));

        // $details = [
        //     'name' => $usuario->name,
        //     'email' => $usuario->email,
        //     // 'estado' => $estado
        // ];
        // dispatch(new SendWelcomeEmailJob($details));

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $id,
            'phone'    => 'required|numeric',
            'password' => 'same:confirm-password',
            'roles'    => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        if ($request->status != 'ASOCIADO' && $request->roles[0] == 'ClienteHijo' || $request->status == 'ASOCIADO' && $request->roles[0] != 'ClienteHijo') {

            Session::flash('message', 'store');
            return redirect()->back();
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        Session::flash('message1', 'store');
        return redirect()->route('usuario.index');
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();
        $userShilder = Relationship::where([['user_id', $request->userId], ['deleted_status', '!=', 'INACTIVE']])->select('user_assigne_id')->get();

        if (count($userShilder) > 0) {

            foreach ($userShilder as $userAsocie) {
                $id = $userAsocie->user_assigne_id;
                // return response()->json(['success' => true, 'data' => $id]);
                relationship::where('user_assigne_id', $id)->update(['deleted_status' => 'INACTIVE']);
                // relationship::find($id)->delete();
                $associatedUser = User::find($id);
                if ($associatedUser) {
                    $associatedUser->delete();
                }
            }
        }
        return response()->json(['success' => true]);
    }

    public function cambiarEstado($idUsuario)
    {
        User::where('id', $idUsuario)->update(['status' => 'CONFIRMADO']);
        return response()->json('The post successfully updated');
    }

    public function confirmarUser(Request $request)
    {
        $result = DB::table('users')->select('id', 'name')->where('number_id', $request->number_id)->get();


        if (count($result) == 0) {
            return response()->json(['success' => false, 'data' => 'No se encontro el proveedor']);
        } else if (count($result) != 0) {

            DB::table('relationship')->insert([
                'user_id' => $result[0]->id,
                'user_assigne_id' => $request->id
            ]);

            return response()->json(['success' => true, 'data' => $result]);
        }

        // dd($result);
    }

    public function test(Request $request)
    {
        return view('vendor.invoices.templates.default');
    }

    public function changePassword(Request $request): JsonResponse
    {
        try {
            # Validation
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|confirmed',
            ]);

            #Match The Old Password
            if (!Hash::check($request->old_password, auth()->user()->password)) {
                return response()->json(['error' => 'La contraseña anterior no coincide!']);
            }


            #Update the new Password
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json(['status', 'Contraseña cambiada con éxito!']);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . '. General error: ' . $th->getMessage());
        }
    }

    public function getdeletedUsers()
    {
        $deletedUsers = User::onlyTrashed()->get();
        return response()->json($deletedUsers);
    }

    public function reactivateUser(Request $request)
    {
        // Recuperar el usuario eliminado
        $user = User::withTrashed()->findOrFail($request->userId);
        // Reactivar el usuario
        $user->restore();
        return response()->json(['success' => true]);
    }
}
