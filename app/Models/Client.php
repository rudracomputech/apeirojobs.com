<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'client_name',
        'contact_number',
        'contact_person_name',
        'mobile_no',
        'email_id',
        'area',
        'city',
        'state',
        'client_details',
        'calling_status',
        'feedback',
        'vacancy_status',
        'requirement_status',
        'proposal_status',
        'empannel',
        'internship_payment',
        'final_remark',
    ];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
