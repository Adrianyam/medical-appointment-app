<div class="flex space-x-2">
    <a href="{{ route('admin.appointments.receipt', $appointment) }}" target="_blank" class="p-2 bg-amber-500 text-white rounded-md" title="Ver PDF">
        <i class="fa-solid fa-file-pdf"></i>
    </a>

    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="p-2 bg-blue-500 text-white rounded-md">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>

    <a href="{{ route('admin.appointments.consultation', $appointment) }}" class="p-2 bg-emerald-500 text-white rounded-md">
        <i class="fa-solid fa-stethoscope"></i>
    </a>

    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" data-swal-confirm>
        @csrf
        @method('DELETE')
        <button type="submit" class="p-2 bg-red-500 text-white rounded-md">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>
