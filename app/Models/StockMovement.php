<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_id',
        'warehouse_id',
        'type',
        'quantity',
        'unit_cost',
        'previous_stock',
        'new_stock',
        'reference_type',
        'reference_id',
        'notes',
        'user_id',
        'movement_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'previous_stock' => 'decimal:2',
        'new_stock' => 'decimal:2',
        'movement_date' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeDescriptionAttribute(): string
    {
        return match ($this->type) {
            'IN' => 'Entrada',
            'OUT' => 'Salida',
            'ADJUSTMENT' => 'Ajuste',
            'TRANSFER' => 'Transferencia',
            default => 'Desconocido',
        };
    }
}