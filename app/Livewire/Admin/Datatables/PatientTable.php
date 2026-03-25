<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;

class PatientTable extends DataTableComponent
{
    //protected $model = Patient::class;

    //este metodo define el modelo
    public function builder(): Builder
    {
        //devuelve la reacion con los roles
    
        return Patient::query()
        ->with('user');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombre", "user.name")
                ->sortable(),
            Column::make("Email", "user.email")
                ->sortable(),
            Column::make("Numero de ID", "user.id_number")
                ->sortable(),
            Column::make("Telefono", "user.number_phone")
                ->sortable(),
            Column::make("Acciones")
                ->label(function($row){
                    return view('admin.patients.actions', 
                    ['patient' => $row]);
                })
        ];
    }
}
