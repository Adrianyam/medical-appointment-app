<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DoctorTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return User::role('Medico')
            ->with('doctor')
            ->select('users.*');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nombre', 'name')->sortable(),
            Column::make('Email', 'email')->sortable(),
            Column::make('DNI', 'id_number')->sortable(),
            Column::make('Telefono', 'number_phone')->sortable(),
            Column::make('Especialidad')
                ->label(function ($row) {
                    return $row->doctor?->specialization ?? 'Sin registro';
                }),
            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.doctors.actions', ['user' => $row]);
                }),
        ];
    }
}