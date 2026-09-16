<?php

namespace App\Models;

use App\Models\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadFollowup extends Model
{
    use SoftDeletes,HasActivityLog;

    protected $guarded = ['id'];

    protected $fillable = [
        'lead_id',
        'user_id',
        'followup_type',
        'call_duration',
        'note',
        'followup_date',
        'next_followup_date',
    ];

    protected $casts = [
        'followup_date' => 'datetime',
        'next_followup_date' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
