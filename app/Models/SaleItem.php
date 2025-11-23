<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_code',
        'product_name',
        'quantity',
        'unit_price',
        'total_price',
        'subtotal', // Alias para total_price
        'iva_type',
        'iva_amount',
        'discount_percentage',
        'discount_amount',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'iva_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    // Accessor para compatibilidad
    public function getSubtotalAttribute()
    {
        return $this->total_price;
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateIva(): void
    {
        $ivaRate = match ($this->iva_type) {
            'IVA_5' => 5.00,
            'IVA_10' => 10.00,
            default => 0.00,
        };

        $subtotal = $this->total_price - $this->discount_amount;
        $this->iva_amount = ($subtotal * $ivaRate) / (100 + $ivaRate);
    }

    public function applyDiscount(float $percentage): void
    {
        $this->discount_percentage = $percentage;
        $this->discount_amount = ($this->total_price * $percentage) / 100;
        $this->total_price = $this->total_price - $this->discount_amount;
        
        $this->calculateIva();
    }
}