<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberWorkingHour extends Model
{
    use HasFactory;

    protected $table = 'barbers_working_hours';

    protected $fillable = [
        'barber_id',
        'start_lunch',
        'end_lunch',
        'start_work',
        'end_work'
    ];
}
