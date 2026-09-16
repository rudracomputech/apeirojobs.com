<?php

namespace App\Models;

use App\Models\Traits\HasActivityLog;
use App\Models\Traits\HasOwnership;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
   use HasOwnership, HasActivityLog, SoftDeletes;
    
    protected $guarded = ['id'];

    protected $casts = [
        'next_followup_date' => 'date',
        'converted_at' => 'datetime',
    ];

    public function courseInterest()
    {
        return $this->belongsTo(Course::class, 'course_interest_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function followups()
    {
        return $this->hasMany(LeadFollowup::class, 'lead_id')->orderByDesc('followup_date');
    }

    public function getOwnershipColumn(): string
    {
        return 'assigned_to';
    }
}
