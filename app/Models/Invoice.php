<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'patient_id',
        'appointment_id',
        'consultation_fee',
        'total_amount',
        'status',
        'created_by',
    ];

    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                // Simple sequential numbering - get all invoice numbers and find the max numeric value
                $allInvoices = static::pluck('invoice_number');
                $maxNumber = 0;
                
                foreach ($allInvoices as $number) {
                    // Check if the invoice number is purely numeric
                    if (is_numeric($number)) {
                        $numValue = (int)$number;
                        if ($numValue > $maxNumber) {
                            $maxNumber = $numValue;
                        }
                    }
                }
                
                $nextNumber = $maxNumber + 1;
                $invoice->invoice_number = (string)$nextNumber;
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAmountAttribute()
    {
        // Sum of payment amounts
        return $this->payments()->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
}
