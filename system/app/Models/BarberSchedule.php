<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberSchedule extends Model
{
    use HasFactory;

    protected $table = 'barbers_schedules';

    protected $fillable = ['service_register_id', 'customer_id',  'selected_date_and_time', 'observation'];
}
