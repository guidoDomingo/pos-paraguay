<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'sale_id',
        'customer_id',
        'fiscal_stamp_id',
        'invoice_number',
        'stamp_number',
        'establishment',
        'point_of_sale',
        'sequential_number',
        'subtotal_exento',
        'subtotal_iva_5',
        'subtotal_iva_10',
        'total_iva_5',
        'total_iva_10',
        'total_iva',
        'total_amount',
        'customer_name',
        'customer_ruc',
        'customer_address',
        'condition',
        'invoice_date',
        'observations',
        'is_printed',
        'printed_at',
    ];

    protected $casts = [
        'subtotal_exento' => 'decimal:2',
        'subtotal_iva_5' => 'decimal:2',
        'subtotal_iva_10' => 'decimal:2',
        'total_iva_5' => 'decimal:2',
        'total_iva_10' => 'decimal:2',
        'total_iva' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'invoice_date' => 'date',
        'is_printed' => 'boolean',
        'printed_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function fiscalStamp(): BelongsTo
    {
        return $this->belongsTo(FiscalStamp::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function calculateTotals(): void
    {
        $subtotalExento = 0;
        $subtotalIva5 = 0;
        $subtotalIva10 = 0;
        $totalIva5 = 0;
        $totalIva10 = 0;

        foreach ($this->items as $item) {
            switch ($item->iva_type) {
                case 'EXENTO':
                    $subtotalExento += $item->total_price;
                    break;
                case 'IVA_5':
                    $subtotalIva5 += $item->total_price;
                    $totalIva5 += $item->iva_amount;
                    break;
                case 'IVA_10':
                    $subtotalIva10 += $item->total_price;
                    $totalIva10 += $item->iva_amount;
                    break;
            }
        }

        $this->update([
            'subtotal_exento' => $subtotalExento,
            'subtotal_iva_5' => $subtotalIva5,
            'subtotal_iva_10' => $subtotalIva10,
            'total_iva_5' => $totalIva5,
            'total_iva_10' => $totalIva10,
            'total_iva' => $totalIva5 + $totalIva10,
            'total_amount' => $subtotalExento + $subtotalIva5 + $subtotalIva10 + $totalIva5 + $totalIva10,
        ]);
    }

    public function markAsPrinted(): void
    {
        $this->update([
            'is_printed' => true,
            'printed_at' => now(),
        ]);
    }
}