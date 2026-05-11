<div class="flex space-x-2">
    @if($user->doctor)
        <a href="{{ route('admin.doctors.show', $user->doctor) }}" class="p-2 bg-emerald-500 text-white rounded-md">
            <i class="fa-solid fa-circle-info"></i>
        </a>

        <a href="{{ route('admin.users.edit-doctor', $user) }}" class="p-2 bg-blue-500 text-white rounded-md">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>

        <a href="{{ route('admin.doctors.schedule.edit', $user->doctor) }}" class="p-2 bg-yellow-500 text-white rounded-md" title="Editar horario">
            <i class="fa-solid fa-clock"></i>
        </a>

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" data-swal-confirm="true" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-2 bg-red-500 text-white rounded-md" title="Eliminar usuario y doctor">
                <i class="fa-solid fa-trash"></i>
            </button>
        </form>
    @else
        <a href="{{ route('admin.users.edit-doctor', $user) }}" class="p-2 bg-blue-500 text-white rounded-md" title="Registrar información del doctor">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" data-swal-confirm="true" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-2 bg-red-500 text-white rounded-md" title="Eliminar usuario">
                <i class="fa-solid fa-trash"></i>
            </button>
        </form>
    @endif
</div>