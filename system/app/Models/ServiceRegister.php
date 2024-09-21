<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRegister extends Model
{
    use HasFactory;

    protected $table = 'services_registers';

    protected $fillable = ['customer_id', 'service_type_id',  'barber_id'];
}
