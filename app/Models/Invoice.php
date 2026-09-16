<?php

namespace App\Models;

use App\Models\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasActivityLog, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payments()
    {
        return $this->hasMany(Payments::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    /**
     * Recalculate paid_amount and due_amount based on successful payments.
     */
    public function recalculateTotals(): void
    {
        $paid = $this->payments()->where('status', 'success')->sum('amount');

        $total = (float) $this->total_amount;
        $discount = (float) ($this->discount ?? 0);
        $tax = (float) ($this->tax ?? 0);

        $due = max(0.0, $total - $discount - $paid + $tax);

        $this->paid_amount = $paid;
        $this->due_amount = $due;
        $this->status = $due <= 0 ? 'paid' : ($paid > 0 ? 'partially_paid' : 'due');
        $this->save();
    }
}
