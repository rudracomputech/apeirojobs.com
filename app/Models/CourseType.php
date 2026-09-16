<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseType extends Model
{
    use HasActivityLog,SoftDeletes;
    protected $guarded = ['id'];
}
