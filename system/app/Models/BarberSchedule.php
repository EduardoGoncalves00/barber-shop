<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberSchedule extends Model
{
    use HasFactory;

    protected $table = 'barbers_schedules';

    protected $fillable = ['barber_id', 'service_register_id', 'customer_id',  'selected_day_and_time', 'observation'];

    public function serviceRegister()
    {
        return $this->belongsTo(ServiceRegister::class, 'service_register_id');
    }
}
