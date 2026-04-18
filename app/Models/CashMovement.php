<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_register_id',
        'user_id',
        'type',
        'amount',
        'description',
        'sale_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'INCOME'  => 'Ingreso',
            'EXPENSE' => 'Egreso',
            'REFUND'  => 'Devolución',
            default   => $this->type,
        };
    }
}
