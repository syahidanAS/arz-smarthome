<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relay extends Model
{
    protected $table = 'relays';
    protected $fillable = [
        'relay_number',
        'status'
    ];
}
