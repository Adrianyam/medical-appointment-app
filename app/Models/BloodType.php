<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    //relacion uno a muchos con paciente
    public function patients(){
        return $this->hasMany(Patient::class);
    }
}
