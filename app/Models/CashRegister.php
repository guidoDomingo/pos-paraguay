<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'warehouse_id',
        'user_id',
        'opening_amount',
        'closing_amount',
        'expected_amount',
        'difference_amount',
        'opened_at',
        'closed_at',
        'status',
        'opening_notes',
        'closing_notes',
    ];

    protected $casts = [
        'opening_amount' => 'decimal:2',
        'closing_amount' => 'decimal:2',
        'expected_amount' => 'decimal:2',
        'difference_amount' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
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

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getTotalSales(): float
    {
        return $this->sales()
            ->where('status', 'COMPLETED')
            ->where('payment_method', 'CASH')
            ->sum('total_amount');
    }

    public function getSalesCount(): int
    {
        return $this->sales()
            ->where('status', 'COMPLETED')
            ->count();
    }

    public function calculateExpectedAmount(): float
    {
        return $this->opening_amount + $this->getTotalSales();
    }

    public function close(float $closingAmount, string $notes = null): void
    {
        $expectedAmount = $this->calculateExpectedAmount();
        
        $this->update([
            'closing_amount' => $closingAmount,
            'expected_amount' => $expectedAmount,
            'difference_amount' => $closingAmount - $expectedAmount,
            'closed_at' => now(),
            'status' => 'CLOSED',
            'closing_notes' => $notes,
        ]);
    }
}