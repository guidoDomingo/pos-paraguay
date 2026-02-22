<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'category_id',
        'code',
        'barcode',
        'name',
        'description',
        'cost_price',
        'sale_price',
        'wholesale_price',
        'iva_type',
        'tax_rate',
        'unit',
        'min_stock',
        'max_stock',
        'stock',
        'image_path',
        'track_stock',
        'is_active',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'max_stock' => 'decimal:2',
        'stock' => 'decimal:2',
        'track_stock' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getCurrentStock(int $warehouseId = null): float
    {
        $query = $this->stockMovements();
        
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        $movements = $query->get();
        $stock = 0;

        foreach ($movements as $movement) {
            if ($movement->type === 'IN' || $movement->type === 'ADJUSTMENT') {
                $stock += $movement->quantity;
            } else {
                $stock -= $movement->quantity;
            }
        }

        return $stock;
    }

    public function getIvaRate(): float
    {
        return match ($this->iva_type) {
            'IVA_5' => 5.00,
            'IVA_10' => 10.00,
            default => 0.00,
        };
    }
    
    /**
     * Obtiene la URL de la imagen en el tamaño especificado
     */
    public function getImageUrl(string $size = 'medium'): ?string
    {
        if (!$this->image_path) {
            return null;
        }
        
        $imageService = app(\App\Services\ProductImageService::class);
        return $imageService->getImageUrl($this->image_path, $size);
    }
    
    /**
     * Obtiene todas las URLs de la imagen
     */
    public function getAllImageUrls(): array
    {
        $imageService = app(\App\Services\ProductImageService::class);
        return $imageService->getAllImageUrls($this->image_path);
    }
    
    /**
     * Verifica si el producto tiene imagen
     */
    public function hasImage(): bool
    {
        return !empty($this->image_path);
    }

    public function getPriceWithIva(): float
    {
        $ivaRate = $this->getIvaRate() / 100;
        return $this->sale_price * (1 + $ivaRate);
    }

    public function isLowStock(int $warehouseId = null): bool
    {
        return $this->getCurrentStock($warehouseId) <= $this->min_stock;
    }
}