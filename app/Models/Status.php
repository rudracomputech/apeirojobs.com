<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['key', 'label', 'type', 'description', 'active'];
    public $timestamps = false;

    protected $casts = [
        'active' => 'boolean',
    ];
}
