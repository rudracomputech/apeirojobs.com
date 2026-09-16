<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentQualification extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
