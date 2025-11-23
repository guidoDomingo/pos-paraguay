<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'warehouse_id',
        'user_id',
        'customer_id',
        'cash_register_id',
        'sale_number',
        'invoice_number',
        'ticket_number',
        'document_type',
        'customer_name',
        'customer_document',
        'sale_type',
        'subtotal',
        'subtotal_amount',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'amount_paid',
        'change_amount',
        'status',
        'notes',
        'sale_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'subtotal_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'sale_date' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // Alias para compatibilidad con las vistas
    public function saleItems(): HasMany
    {
        return $this->items();
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function calculateTotals(): void
    {
        $subtotal = 0;
        $taxAmount = 0;

        foreach ($this->items as $item) {
            $subtotal += $item->total_price;
            $taxAmount += $item->iva_amount;
        }

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $subtotal + $taxAmount - $this->discount_amount,
        ]);
    }

    public function generateSaleNumber(): string
    {
        $prefix = $this->sale_type === 'TICKET' ? 'T' : 'V';
        $lastSale = self::where('company_id', $this->company_id)
            ->where('sale_type', $this->sale_type)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastSale ? (int) substr($lastSale->sale_number, 1) + 1 : 1;

        return $prefix . str_pad($number, 8, '0', STR_PAD_LEFT);
    }

    public function canBeInvoiced(): bool
    {
        return $this->sale_type === 'TICKET' && 
               $this->status === 'COMPLETED' && 
               !$this->invoice;
    }
}