<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all(); //obtenemos los roles para mostrarlos en el formulario de creacion
        return view('admin.users.create', compact('roles')); //pasamos los roles a la vista
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request ->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|unique:users|regex:/^[a-zA-Z0-9]+$/',
            'number_phone' => 'required|digits_between:7,15',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create($datos); //creamos el usuario con los datos validados

        $user->roles()->attach($datos['role_id']); //asignamos el rol al usuario

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado correctamente',
            'text' => 'El usuario ha sido creado correctamente',
        ]); //mensaje de exito

        return redirect(route('admin.users.index'))->with('success', 'User created successfully'); //redireccionamos a la lista de usuarios
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all(); //obtenemos los roles para mostrarlos en el formulario de edicion
        return view('admin.users.edit', compact('user', 'roles')); //para saber cual usuario se editara
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $datos = $request ->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, //validacion unica para el email, ignorando el email del usuario actual
            'id_number' => 'required|string|min:5|max:20|unique:users,id_number,' . $user->id . '|regex:/^[a-zA-Z0-9]+$/',
            'number_phone' => 'required|digits_between:7,15',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($datos); 

        //si el usuario quiere editar la contraseña
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password); //encriptamos la contraseña
            $user->save();
        }


        $user->roles()->sync($datos['role_id']); //asignamos el rol al usuario

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text' => 'El usuario ha sido actualizado correctamente',
        ]); //mensaje de exito

        return redirect()->route('admin.users.edit', $user->id); //redireccionamos a la lista de usuarios
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        //no permitir que el usuario logueado se borre asi mismo
        if($user->id == Auth::user()->id) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No puedes eliminarte a ti mismo',
                'text' => 'No puedes eliminar tu propio usuario',
            ]); //mensaje de error

            abort(403, 'No puedes borrar tu propio usuario'); //lanzamos un error 403 (Forbidden)

            return redirect()->route('admin.users.index'); //redireccionamos a la lista de usuarios
        }

        //eliminar roles asociados al usuario
        $user->roles()->detach();

        $user->delete(); //eliminar el usuario

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado correctamente',
            'text' => 'El usuario ha sido eliminado correctamente',
        ]); //mensaje de exito

        return redirect()->route('admin.users.index'); //redireccionamos a la lista de usuarios
    }
}
