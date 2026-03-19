<x-admin-layout tittle="Usuarios" :breadcrumbs="[
    [
      'name' => 'Dashboard',
      'href' => route('admin.dashboard'),
    ],
    [
      'name' => 'Usuarios',
      'href' => route('admin.users.index'),
    ],
    [
      'name' => 'Crear',
    ],

]">

    
    <x-wire-card>
        <x-validation-errors class="mb-4"/>
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="grid lg:grid-cols-2 gap 4">

                
                    <x-wire-input label="Nombre" name="name" type="text" placeholder="Escriba el nombre del usuario" required :value="old('name', $user->name) " />

                    <x-wire-input label="Correo electronico" name="email" type="email" placeholder="Tu correo aqui" required autocomplete :value="old('email',$user->email) " />

                    <x-wire-input label="Contraseña" name="password" type="password" placeholder="minimo de 8 caracteres" autocomplete="new-password" />

                    <x-wire-input label="Confirmar contraseña" name="password_confirmation" type="password" placeholder="repita la contraseña" autocomplete="new-password" />

                    <x-wire-input label="Numero de ID" name="id_number" placeholder="Ej. 1234567890" autocomplete="off" required inputmode="numeric" :value=" old('id_number', $user->id_number) " />

                    <x-wire-input label="Telefono" name="number_phone" placeholder="Ej. 1234567890" autocomplete="tel" required inputmode="tel" :value=" old('number_phone', $user->number_phone) " />
                </div>

                    <x-wire-input name="address" label="Direccion" required :value=" old('address', $user->address)" placeholder="Ej. calle 90 x 23" autocomplete="Street-address"></x-wire-input>

                    <div class="flex justify-end mt-4">
                        <x-wire-native-select name="role_id" label="Rol" required>
                            <option value="">Seleccione un rol</option>
                        

                        @foreach ($roles as $role)
                        <option value="{{$role->id}}" @selected(old('role_id', $user->roles->first()->id) == $role->id)>
                            {{ $role->name }}</option>
                            
                        @endforeach

                        </x-wire-native-select>
                        <p class="text-sm text-gray-500">
                            Define los permisos y el acceso del usuario dentro del sistema
                        </p>
                    </div>


            </div>

            <div class="flex justify-end mt-4">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Actualizar
                </button>
            </div>
        </form>

    </x-wire-card>
</x-admin-layout>