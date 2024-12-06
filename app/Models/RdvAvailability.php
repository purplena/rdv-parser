<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RdvAvailability extends Model
{
    protected $fillable = ['rdv_service', 'availbility_count', 'error'];
}
