<?php

namespace App\Models;

use App\Models\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryState extends Model
{
    use HasActivityLog,SoftDeletes;
    protected $guarded = ['id'];
}
