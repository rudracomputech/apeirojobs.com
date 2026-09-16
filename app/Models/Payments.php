<?php

namespace App\Models;

use App\Models\Traits\HasActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Installment;
use Illuminate\Support\Facades\DB;

class Payments extends Model
{
    use SoftDeletes,HasActivityLog;

    protected static function booted()
    {
        static::created(function ($payment) {
            if ($payment->invoice_id) {
                $inv = Invoice::find($payment->invoice_id);
                if ($inv) {
                    $inv->recalculateTotals();
                }
            }
        });

        static::updated(function ($payment) {
            if ($payment->invoice_id) {
                $inv = Invoice::find($payment->invoice_id);
                if ($inv) {
                    $inv->recalculateTotals();
                }
            }
        });

        static::deleted(function ($payment) {
            if ($payment->invoice_id) {
                $inv = Invoice::find($payment->invoice_id);
                if ($inv) {
                    $inv->recalculateTotals();
                }
            }
        });
    }

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'gateway_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
