<?php

namespace App\Models;

use App\Models\Student;
use App\Models\CourseType;
use App\Models\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasActivityLog,SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->attributes['name'] ?? $this->attributes['name'] ?? null;
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = $value;
       
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->discount_price !== null && $this->discount_price > 0
            ? $this->discount_price
            : (float) $this->price;
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot(['id', 'batch_id', 'enrollment_date', 'fee_amount', 'discount_amount', 'status'])
            ->withTimestamps();
    }

    public function courseType()
    {
        return $this->belongsTo(CourseType::class, 'course_type_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
