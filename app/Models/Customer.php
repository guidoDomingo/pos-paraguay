<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'ruc',
        'dv',
        'ci',
        'address',
        'phone',
        'email',
        'birth_date',
        'credit_limit',
        'customer_type',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'credit_limit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getFormattedRucAttribute(): ?string
    {
        return $this->ruc ? $this->ruc . '-' . $this->dv : null;
    }

    public function getTotalPurchases(): float
    {
        return $this->sales()
            ->where('status', 'COMPLETED')
            ->sum('total_amount');
    }

    public function getLastPurchaseDate(): ?string
    {
        $lastSale = $this->sales()
            ->where('status', 'COMPLETED')
            ->orderBy('sale_date', 'desc')
            ->first();

        return $lastSale?->sale_date->format('d/m/Y');
    }
}