<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//agregamos lo siguiente
use App\Http\Controllers\Controller;
use App\Models\Relationship;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    use SoftDeletes;

    function __construct()
    {
        $this->middleware('permission:/profile')->only('index');
        //  $this->middleware('permission:crear-blog', ['only' => ['create','store']]);
        //  $this->middleware('permission:editar-blog', ['only' => ['edit','update']]);
        //  $this->middleware('permission:borrar-blog', ['only' => ['destroy']]);
    }

    public function index()
    {
        $user          = User::find(Auth::user()->id);
        $user_relation = DB::table('users')
            ->leftJoin('relationship', 'users.id', '=', 'relationship.user_assigne_id')
            ->where([['relationship.user_id',  $user->id], ['relationship.deleted_status', '!=', 'INACTIVE']])
            ->select(
                'users.id',
                'users.email',
                'users.document_type',
                'users.number_id',
                'users.name',
                'users.phone',
                'users.status',
                'relationship.deleted_status',
                'users.deleted_at as delete',
                'relationship.deleted_at',
            )->get();

        return view('profile.profile', [
            'user_relation' => $user_relation,
            'user'          => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //aqui trabajamos con name de las tablas de users
        $roles = Role::pluck('name', 'name')->all();
        return view('profile.profile', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles'    => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('usuarios.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('perfil.profile', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $phone = $request->pfTelefono;
        $document_id = $request->photo_id;

        if (!empty($request->photo_id)) {
            $request->validate([
                'photo_id' => 'file|mimes:pdf,jpg,jpeg,png|max:4096',
            ]);

            $pdfPath = $this->storeFile($document_id, 'documet/pdf', Auth::user()->number_id);

            User::find(Auth::user()->id)->update([
                'photo_id' => $pdfPath
            ]);
        }

        $user = User::find(Auth::user()->id)->update([
            'phone' => $phone,
        ]);

        // $infuUser->email           = $request->get('pfEmail');
        // $infuUser->phone = $request->get('pfTelefono');

        // $infuUser->save();

        // $input = $request->all();
        // if(!empty($input['password'])){
        //     $input['password'] = Hash::make($input['password']);
        // }else{
        //     $input = Arr::except($input,array('password'));
        // }

        // $user = User::find($id);
        // $user->update($input);
        // DB::table('model_has_roles')->where('model_id',$id)->delete();

        // $user->assignRole($request->input('roles'));

        return redirect()->back()->with('mensaje', 'ok');
    }

    public function photoUpdate(Request $request)
    {
        $request->validate([
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las validaciones según tus necesidades
        ]);

        if ($request->hasFile('profile_image')) {

            $profileImage = $request->file('profile_image');
            $user = Auth::user();
            $imagePath = $this->storeFile($profileImage, 'profile/image', $user->number_id);

            User::whereId($user->id)->update(['photo' => $imagePath]);

            return response()->json(['profile_image_url' => $imagePath]);
        }

        // if ($request->hasFile('profile_image')) {

        //     $user = Auth::user();

        //     // Renombrar el archivo con el número de identificación del usuario
        //     $profileImage = $request->file('profile_image');
        //     $newFileName = $user->number_id . '.' . $profileImage->getClientOriginalExtension();

        //     // Elimina la imagen anterior si existe
        //     Storage::delete($user->photo);

        //     // Almacena la nueva imagen y actualiza el campo en la base de datos
        //     $profileImagePath = $profileImage->storeAs('profile_images', $newFileName, 'public');

        //     // $profileImage = $request->file('profile_image')->store('profile_images', 'public');

        //     User::whereId($user->id)->update(['photo' => $profileImagePath]);


        //     return response()->json(['profile_image_url' => asset('storage/' . $profileImagePath)]);
        // }
        return response()->json(['error' => 'No se proporcionó una imagen válida.'], 422);
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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

       User::find($id)->delete();
        return back();
    }

    public function eliminarUserAsociado($id)
    {
        $data = DB::table('relationship')
            ->select('id')
            ->where('user_id', Auth::user()->id)
            ->where('user_assigne_id', $id)
            ->first();

        if (!$data) {
            return redirect()->route('profile')->with(['message' => 'Wrong ID!!']);
        }

        $dateDaled = DB::table('relationship')->select('deleted_at')->where('user_assigne_id', '=', $id)->get();
        if ($dateDaled->count() > 1) {
            return redirect()->route('profile')->with(['message'=> 'Wrong ID!!']);
        }
        $post = user::find($id)->delete();
        $post = relationship::find($data->id)->delete();
        if ($post != null) {
            return redirect()->route('profile');
        }
        return redirect()->route('profile');
    }

    public function reasignarUserAsociado($id)
    {
        $ownsRelation = DB::table('relationship')
            ->where('user_id', Auth::user()->id)
            ->where('user_assigne_id', $id)
            ->exists();

        if (!$ownsRelation) {
            return redirect()->route('profile')->with(['message' => 'Wrong ID!!']);
        }

        DB::table('users')
            ->where('id', $id)
            ->update(['deleted_at' => NULL]);

        DB::table('relationship')
            ->where('user_assigne_id', $id)
            ->update(['deleted_status' => "INACTIVE"]);

        DB::table('relationship')->insert([
            'user_id'         => Auth::user()->id,
            'user_assigne_id' => $id,
            'deleted_status'   => "RESIGNED"
        ]);
        return redirect()->route('profile');
    }
}
