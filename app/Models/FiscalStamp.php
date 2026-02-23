<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalStamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'stamp_number',
        'valid_from',
        'valid_until',
        'establishment',
        'point_of_sale',
        'current_invoice_number',
        'max_invoice_number',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getNextInvoiceNumber(): string
    {
        $this->increment('current_invoice_number');
        
        return sprintf(
            '%s-%s-%07d',
            $this->establishment,
            $this->point_of_sale,
            $this->current_invoice_number
        );
    }

    public function isValid(): bool
    {
        return $this->is_active &&
               now()->between($this->valid_from, $this->valid_until) &&
               $this->current_invoice_number < $this->max_invoice_number;
    }

    public function getRemainingInvoices(): int
    {
        return $this->max_invoice_number - $this->current_invoice_number;
    }
}