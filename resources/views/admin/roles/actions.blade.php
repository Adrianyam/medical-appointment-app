<div class="flex space-x-2">
    <a href="{{ route('admin.roles.edit', $role) }}" class="p-2 bg-blue-500 text-white rounded-md">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>

    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="p-2 bg-red-500 text-white rounded-md">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>