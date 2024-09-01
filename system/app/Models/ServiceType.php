<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'services_types';

    protected $fillable = [
        'service_name',
        'value',
        'estimated_time'
    ];
}
