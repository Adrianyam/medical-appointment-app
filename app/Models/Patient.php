<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'allergies',
        'chronic_conditions',
        'surgical_history',
        'family_history',
        'observations',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
    ];




    //relacion uno a uno con paciente
    public function user(){
        return $this->belongsTo(User::class);
    }

    //relacion uno a uno con paciente
    public function bloodType(){
        return $this->belongsTo(BloodType::class);
    }
}
