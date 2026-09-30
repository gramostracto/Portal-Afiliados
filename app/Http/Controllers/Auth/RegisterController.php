<?php

namespace App\Http\Controllers\Auth;

use App\Events\MessageSentPrivate;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Helpers\RequestNit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Pusher\Pusher;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $isNit = ($data['document_type'] ?? null) === 'NIT';

        return Validator::make($data, [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users', 'indisposable'],
            'number_id'     => ['required', RequestNit::rule(fn() => $data['document_type'] ?? null)],
            'dv'            => $isNit ? ['required', 'digits:1', function ($attribute, $value, $fail) use ($data) {
                $expected = substr((string) RequestNit::normalize('NIT', $data['number_id'] ?? ''), -1);
                if ((string) $value !== $expected) {
                    $fail('El dígito de verificación no corresponde al NIT ingresado.');
                }
            }] : ['nullable'],
            'phone'         => ['required', 'digits_between:7,11'],
            'document_type' => ['required'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            //'captcha'    => ['required', 'captcha:' . request('key') . ',math']
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */


    // protected function create(array $data)
    // {

    //     $roles = Role::get();

    //     $id = User::insertGetId([
    //         'name'                  => $data['name'],
    //         'email'                 => $data['email'],
    //         'number_id'        => $data['number_id'],
    //         'document_type'          => $data['document_type'],
    //         'phone'              => $data['phone'],
    //         'status'                => 'NUEVO',
    //         'password'              => Hash::make($data['password']),
    //         'created_at' => Carbon::now(),
    //         'updated_at' => Carbon::now(),

    //     ]);

    //     $usuario = User::findOrFail($id);
    //     $usuario->roles()->sync($roles[1]->id);

    //     //? Guardamos los archivos cargados y capturamos la ruta
    //     if (!empty($data['photo'])) {

    //         $profileImage = $data['photo'];
    //         // $user = Auth::user();

    //         $folderPath = 'public/profile/image';
    //         $randomFileName = $data['number_id'] . '.' . $profileImage->getClientOriginalExtension();
    //         $imagePath = 'profile/image/' . $randomFileName;

    //         // Validar y crear directorio si no existe
    //         if (!Storage::exists($folderPath)) {
    //             Storage::makeDirectory($folderPath);
    //         }

    //         // Storage::put($imagePath, $profileImage);
    //         Storage::putFileAs($folderPath, $profileImage, $randomFileName);

    //         // $carpetaphoto = "proveedores/$id/perfil";
    //         // Storage::putFileAs("public/$carpetaphoto", $data['photo'], 'photo_perfil.' . $extensionPerfil);
    //     }

    //     if (!empty($data['photo_id'])) {

    //         $profilepdf = $data['photo_id'];

    //         $folderPath = 'public/documet/pdf';
    //         $randomFileName = $data['number_id'] . '.' . $profilepdf->getClientOriginalExtension();
    //         $pdfPath = 'profile/image/' . $randomFileName;

    //          // Validar y crear directorio si no existe
    //          if (!Storage::exists($folderPath)) {
    //             Storage::makeDirectory($folderPath);
    //         }

    //         // Storage::put($imagePath, $profileImage);
    //         Storage::putFileAs($folderPath, $profilepdf, $randomFileName);

    //         // $carpetaidentif = "proveedores/$id/identificacion";
    //         // Storage::putFileAs("public/$carpetaidentif", $data['photo_id'], 'photo_documento.' . $extensionIdentif);
    //     }

    //     //? Actualizamos el usuario para agregarle la ruta de los archivos en los campos asignados
    //     if (!empty($data['photo']) && !empty($data['photo_id'])) {
    //         User::where('id', $id)
    //             ->update([
    //                 'photo'                 => "$imagePath",
    //                 'photo_id'   => "$pdfPath",
    //             ]);
    //     }

    //     if (!empty($data['photo'])) {

    //         User::where('id', $id)
    //             ->update([
    //                 'photo'   => "$imagePath",
    //             ]);
    //     }

    //     if (!empty($data['photo_id'])) {

    //         User::where('id', $id)
    //             ->update([
    //                 'photo_id'   => "$pdfPath",
    //             ]);
    //     }
    // }

    /* protected function create(array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name'           => $data['name'],
                'email'          => $data['email'],
                'number_id'      => $data['number_id'],
                'document_type'  => $data['document_type'],
                'phone'          => $data['phone'],
                'status'         => 'NUEVO',
                'password'       => Hash::make($data['password']),
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);

            $roles = Role::get();
            $user->roles()->sync([$roles[1]->id]);

        $id = User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'number_id'     => $data['number_id'],
            'document_type' => $data['document_type'],
            'phone'         => $data['phone'],
            'password'      => Hash::make($data['password']),
            'status'        => 'NUEVO',
        ]);

        $usuario = User::findOrFail($id);
        $usuario->roles()->sync($roles[1]->id);

        if (!empty($data['photo'])) {

            $photoConfig = [
                'ext'    => $data['photo']->getClientOriginalExtension(),
                'folder' => "proveedores/$id/perfil"
            ];

            Storage::putFileAs("public/" . $photoConfig['folder'], $data['photo'], 'photo_perfil.' . $photoConfig['ext']);

            User::where('id', $id)
                ->update([
                    'photo'   => "storage/{$photoConfig['folder']}/photo_perfil.{$photoConfig['ext']}",
                ]);
        }
        if (!empty($data['document'])) {

            $documentConfig = [
                'ext'    => $data['document']->getClientOriginalExtension(),
                'folder' => "proveedores/$id/identificacion"
            ];

            Storage::putFileAs("public/" . $documentConfig['folder'], $data['photo'], 'photo_perfil.' . $documentConfig['ext']);

            User::where('id', $id)
                ->update([
                    'photo_id'   => "storage/{$documentConfig['folder']}/photo_documento.{$documentConfig['ext']}",
                ]);
        }
        DB::commit();
        return $user;
    } */

    protected function create(array $data)
    {
        DB::beginTransaction();
        
        try {
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'number_id'     => $data['number_id'],
                'document_type' => $data['document_type'],
                'phone'         => $data['phone'],
                'status'        => 'NUEVO',
                'password'      => Hash::make($data['password']),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // Asignar rol por defecto
            $roles = Role::get();
            if ($roles->count() > 1) {
                $user->roles()->sync([$roles[1]->id]);
            }

            // Guardar foto de perfil
            if (!empty($data['photo'])) {
                $photoPath = $this->storeFile(
                    $data['photo'],
                    "proveedores/{$user->id}/perfil",
                    $user->id
                );

                $user->update(['photo' => "$photoPath"]);
            }

            // Guardar documento de identificación
            if (!empty($data['photo_id'])) {
                $docPath = $this->storeFile(
                    $data['photo_id'],
                    "proveedores/{$user->id}/identificacion",
                    $user->id
                );

                $user->update(['photo_id' => "storage/{$docPath}"]);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al crear usuario: " . $e->getMessage());
            throw $e;
        }
    }



    private function storeFile($file, $folder, $userId)
    {
        if (!empty($file)) {
            $folderPath = "public/$folder";
            $extension = $file->getClientOriginalExtension();
            $randomFileName = $userId . '.' . $extension;
            $filePath = "$folder/$randomFileName";

            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }

            Storage::putFileAs($folderPath, $file, $randomFileName);

            return $filePath;
        }

        return null;
    }


    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = $request->all();
        $data['number_id'] = RequestNit::normalize($data['document_type'], $data['number_id']);

        event(new Registered($this->create($data)));

        // self::notificationActionPusher();
        // SendEmailRequestNotification::sendEmail($request->name);

        $name = $request->input('name');

        //return view('emails.register-success', compact('name'));
        return redirect()->route('login')->with('alerta-register', 'Espere a que se verifique su información, esto podría tardar unos minutos, al correo registrado le estará llegando la confirmación.');

    }

    function notificationActionPusher()
    {

        $app_id = config('broadcasting.connections.pusher.app_id');
        $app_key = config('broadcasting.connections.pusher.key');
        $app_secret = config('broadcasting.connections.pusher.secret');
        $app_cluster = config('broadcasting.connections.pusher.options.cluster');

        $pusher = new Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);

        $pusher->trigger('my-channel', 'MyEvent', 'Nuevo usuario registrado');
    }
}
