<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_id',
        'user_id',
        'type',
        'quantity',
        'previous_stock',
        'new_stock',
        'unit_cost',
        'reason',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'unit_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // Accessors
    public function getTypeNameAttribute()
    {
        $types = [
            'in' => 'Entrada',
            'out' => 'Salida',
            'adjustment' => 'Ajuste',
            'sale' => 'Venta',
            'purchase' => 'Compra',
            'return' => 'Devolución'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getQuantityDisplayAttribute()
    {
        // Si la cantidad ya tiene el signo correcto, respetarlo
        if ($this->type === 'adjustment') {
            return $this->quantity >= 0 ? '+' . $this->quantity : $this->quantity;
        }
        
        // Para otros tipos, aplicar reglas tradicionales
        $sign = in_array($this->type, ['in', 'purchase', 'return']) ? '+' : '-';
        return $sign . abs($this->quantity);
    }
}
