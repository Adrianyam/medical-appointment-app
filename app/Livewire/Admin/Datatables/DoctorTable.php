<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DoctorTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Doctor::query()->with('user');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nombre', 'user.name')->sortable(),
            Column::make('Email', 'user.email')->sortable(),
            Column::make('DNI', 'user.id_number')->sortable(),
            Column::make('Telefono', 'user.number_phone')->sortable(),
            Column::make('Especialidad', 'specialization')->sortable(),
            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.doctors.actions', ['doctor' => $row]);
                }),
        ];
    }
}