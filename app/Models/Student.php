<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Traits\HasActivityLog;
use App\Models\Traits\HasOwnership;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes, HasActivityLog, HasOwnership;

    public function getOwnershipColumn(): string
    {
        return 'created_by';
    }

    protected static function booted(): void
    {
        static::creating(function ($student) {
            if (empty($student->created_by) && auth()->check()) {
                $student->created_by = auth()->id();
            }
        });
    }

    protected $guarded = ['id'];

    protected $casts = [
        'dob' => 'date',
        'admission_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function getNameAttribute(): string
    {
        return trim($this->attributes['name'] ?? "{$this->first_name} {$this->last_name}");
    }

    public function payments()
    {
        return $this->hasMany(Payments::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot(['id', 'batch_id', 'enrollment_date', 'fee_amount', 'discount_amount', 'status'])
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function qualifications()
    {
        return $this->hasMany(StudentQualification::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function admitCards()
    {
        return $this->hasMany(AdmitCard::class);
    }

    public function diplomas()
    {
        return $this->hasMany(Diploma::class);
    }

    public function idCards()
    {
        return $this->hasMany(IdCard::class);
    }

    public function marksheets()
    {
        return $this->hasMany(Marksheet::class);
    }

    public function migrationCertificates()
    {
        return $this->hasMany(MigrationCertificate::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
  
}
