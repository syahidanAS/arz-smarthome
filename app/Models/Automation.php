<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Automation extends Model
{
    protected $table = 'automations';
    protected $fillable = [
        'user_id',
        'name',
        'topic',
        'message',
        'enabled',
        'time',
        'description'
    ];
}
