<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'specialization',
        'license_number',
        'information',
        'schedule',
    ];

    protected $casts = [
        'schedule' => 'array',
    ];

    /**
     * Relación con User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con Appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
