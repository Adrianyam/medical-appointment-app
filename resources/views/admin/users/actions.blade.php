<div class="flex space-x-2">
    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 bg-blue-500 text-white rounded-md">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>

    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" data-swal-confirm="true">
        @csrf
        @method('DELETE')
        <button type="submit" class="p-2 bg-red-500 text-white rounded-md">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>