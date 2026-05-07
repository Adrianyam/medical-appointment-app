<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AppointmentTable extends DataTableComponent
{
    public function builder(): Builder
    {
        // Hacer una query fresca sin cachés - sin usar ->query()
        return Appointment::query()
            ->with(['patient.user', 'doctor.user'])
            ->select('appointments.*');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')->sortable(),
            Column::make('Paciente', 'patient.user.name')
                ->sortable(),
            Column::make('Doctor', 'doctor.user.name')
                ->sortable(),
            Column::make('Fecha', 'date')
                ->sortable()
                ->format(function ($value, $row) {
                    if ($row->date) {
                        return $row->date->format('d/m/Y');
                    }
                    return '-';
                }),
            Column::make('Hora', 'start_time')
                ->format(function ($value, $row) {
                    if ($value && $row->end_time) {
                        return $value . ' - ' . $row->end_time;
                    }
                    return '-';
                }),
            Column::make('Estado', 'status')
                ->format(function ($value) {
                    return match ((int) $value) {
                        1 => 'Programado',
                        2 => 'En Consulta',
                        3 => 'Completado',
                        default => 'Cancelado',
                    };
                }),
            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.appointments.actions', ['appointment' => $row]);
                }),
        ];
    }
}
