<div class="flex space-x-2">
    <a href="{{ route('admin.doctors.show', $doctor) }}" class="p-2 bg-emerald-500 text-white rounded-md">
        <i class="fa-solid fa-circle-info"></i>
    </a>

    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="p-2 bg-blue-500 text-white rounded-md">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>

    <a href="{{ route('admin.doctors.schedule.edit', $doctor) }}" class="p-2 bg-yellow-500 text-white rounded-md" title="Editar horario">
        <i class="fa-solid fa-clock"></i>
    </a>
</div>