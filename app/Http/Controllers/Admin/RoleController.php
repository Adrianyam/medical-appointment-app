<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar que se cree bien
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        //si para la validacion creara el rol
        Role::create([
            'name' => $request->name
        ]);

        //confirmacion de operacion exitosa
        session()->flash('swal',
        [
            'icon' => 'success',
            'title' => 'Rol creado exitosamente',
            'text' => 'El rol se ha creado correctamente'
        ]);

        //redireccionar a la tabla principal
        return redirect(route('admin.roles.index'))->with('success', 'Rol creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //validar que se cree bien
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        //si pasa la validacion editara el rol
        $role->update([
            'name' => $request->name
        ]); 

        //confirmacion de operacion exitosa
        session()->flash('swal',
        [
            'icon' => 'success',
            'title' => 'Rol se a actualizado exitosamente',
            'text' => 'El rol se ha actualizado correctamente'
        ]);

        //redireccion
        return redirect(route('admin.roles.edit', $role));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //Borrar el elemento
        $role->delete();

        //confirmacion
        session()->flash('swal',
        [
            'icon' => 'success',
            'title' => 'Rol se elimino exitosamente',
            'text' => 'El rol se ha eliminado correctamente'
        ]);

        //redireccionar 
        return redirect(route('admin.roles.index'));
    }
}
