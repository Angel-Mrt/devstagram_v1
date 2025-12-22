<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('perfil.index');
    }
    public function store(Request $request)
    {
        // Modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);
        $this->validate($request, [
            'username' => ['required', 'min:3', 'max:20', 'unique:users,username,'.auth()->user()->id, 'not_in:twitter,editar-perfil'],
            'email' => ['required', 'email', 'max:60', 'unique:users,email,'.auth()->user()->id],
            'password' => ['nullable', 'required_with:nuevo_password', 'current_password'],
            'nuevo_password' => ['nullable', 'min:6', 'confirmed']
        ],
        [
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'El nombre de usuario ya está en uso',
            'username.min' => 'El nombre de usuario debe tener al menos 3 caracteres',
            'username.max' => 'El nombre de usuario no debe exceder los 20 caracteres',
            'username.not_in' => 'El nombre de usuario no está permitido',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ingresar un correo electrónico válido',
            'email.unique' => 'El correo electrónico ya está en uso',
            'email.max' => 'El correo electrónico no debe exceder los 60 caracteres',
            'password.current_password' => 'La contraseña actual es incorrecta',
            'nuevo_password.min' => 'La nueva contraseña debe tener al menos 6 caracteres',
            'nuevo_password.confirmed' => 'La confirmación de la nueva contraseña no coincide'
        ]);
        if($request->imagen) {
            $imagen = $request->file('imagen');
            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000, 1000);

            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);

        }
        $nuevo_password = Hash::make($request->nuevo_password);
        // Guardar los cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? '';
        $usuario->password = $nuevo_password ?? auth()->user()->password;
        $usuario->save();
        //redireccionar
        return redirect()->route('posts.index', $usuario->username);
    }
}
