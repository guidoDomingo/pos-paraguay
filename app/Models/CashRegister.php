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
        'opening_amount'   => 'decimal:2',
        'closing_amount'   => 'decimal:2',
        'expected_amount'  => 'decimal:2',
        'difference_amount'=> 'decimal:2',
        'opened_at'        => 'datetime',
        'closed_at'        => 'datetime',
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

    public function movements(): HasMany
    {
        return $this->hasMany(CashMovement::class);
    }

    // ── Totales por método de pago ──────────────────────────────────────────

    public function getTotalByPaymentMethod(): array
    {
        $sales = $this->sales()->where('status', '!=', 'CANCELLED')->get();

        $totals = [
            'CASH'     => 0,
            'CARD'     => 0,
            'TRANSFER' => 0,
            'CHEQUE'   => 0,
            'CREDIT'   => 0,
        ];

        foreach ($sales as $sale) {
            $method = $sale->payment_method;
            if (isset($totals[$method])) {
                $totals[$method] += (float) $sale->total_amount;
            }
        }

        return $totals;
    }

    public function getTotalSales(): float
    {
        return (float) $this->sales()
            ->where('status', '!=', 'CANCELLED')
            ->sum('total_amount');
    }

    public function getSalesCount(): int
    {
        return $this->sales()->where('status', '!=', 'CANCELLED')->count();
    }

    public function getTotalIncomes(): float
    {
        return (float) $this->movements()->where('type', 'INCOME')->sum('amount');
    }

    public function getTotalExpenses(): float
    {
        return (float) $this->movements()->whereIn('type', ['EXPENSE', 'REFUND'])->sum('amount');
    }

    // Efectivo esperado en caja: apertura + ventas en efectivo + ingresos - egresos
    public function calculateExpectedAmount(): float
    {
        $cashSales = (float) $this->sales()
            ->where('status', 'COMPLETED')
            ->where('payment_method', 'CASH')
            ->where('sale_condition', 'CONTADO')
            ->sum('total_amount');

        return (float) $this->opening_amount
            + $cashSales
            + $this->getTotalIncomes()
            - $this->getTotalExpenses();
    }

    public function close(float $closingAmount, string $notes = null): void
    {
        $expectedAmount = $this->calculateExpectedAmount();

        $this->update([
            'closing_amount'   => $closingAmount,
            'expected_amount'  => $expectedAmount,
            'difference_amount'=> $closingAmount - $expectedAmount,
            'closed_at'        => now(),
            'status'           => 'CLOSED',
            'closing_notes'    => $notes,
        ]);
    }

    // ── Helper estático ─────────────────────────────────────────────────────

    public static function getOpenRegister(int $companyId): ?self
    {
        return self::where('company_id', $companyId)
            ->where('status', 'OPEN')
            ->latest()
            ->first();
    }
}
