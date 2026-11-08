<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number', 'customer_id', 'subtotal', 'discount', 'extra_charge',
        'total', 'paid_amount', 'remaining_amount', 'payment_status', 'work_status',
        'payment_method', 'estimated_finish', 'notes',
    ];

    protected $casts = [
        'estimated_finish' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'extra_charge' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}