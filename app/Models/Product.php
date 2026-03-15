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
        'check_price',
        'check_price_description',
        'credit_price', 
        'credit_price_description',
        'special_price',
        'special_price_description',
        'custom_prices',
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
        'check_price' => 'decimal:2',
        'credit_price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'custom_prices' => 'array',
        'tax_rate' => 'decimal:2',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'stock' => 'integer',
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

    /**
     * Obtiene todos los precios disponibles para el producto
     */
    public function getAllPrices(): array
    {
        $prices = [];
        
        // Precio de venta (obligatorio)
        $prices[] = [
            'type' => 'sale_price',
            'label' => 'Precio Venta',
            'value' => $this->sale_price,
            'description' => 'Precio estándar para venta minorista'
        ];
        
        // Precio mayorista
        if ($this->wholesale_price > 0) {
            $prices[] = [
                'type' => 'wholesale_price',
                'label' => 'Precio Mayorista',
                'value' => $this->wholesale_price,
                'description' => 'Precio especial para ventas al por mayor'
            ];
        }
        
        // Precio para cheques
        if ($this->check_price > 0) {
            $prices[] = [
                'type' => 'check_price',
                'label' => 'Precio Cheque',
                'value' => $this->check_price,
                'description' => $this->check_price_description ?? 'Precio especial para pagos con cheque'
            ];
        }
        
        // Precio a crédito
        if ($this->credit_price > 0) {
            $prices[] = [
                'type' => 'credit_price',
                'label' => 'Precio Crédito',
                'value' => $this->credit_price,
                'description' => $this->credit_price_description ?? 'Precio para ventas a crédito'
            ];
        }
        
        // Precio especial
        if ($this->special_price > 0) {
            $prices[] = [
                'type' => 'special_price',
                'label' => 'Precio Especial',
                'value' => $this->special_price,
                'description' => $this->special_price_description ?? 'Precio especial promocional'
            ];
        }
        
        // Precios personalizados desde JSON
        if ($this->custom_prices && is_array($this->custom_prices)) {
            foreach ($this->custom_prices as $index => $customPrice) {
                if (isset($customPrice['price']) && $customPrice['price'] > 0) {
                    $prices[] = [
                        'type' => 'custom_' . $index,
                        'label' => $customPrice['name'] ?? 'Precio Personalizado',
                        'value' => $customPrice['price'],
                        'description' => $customPrice['description'] ?? ''
                    ];
                }
            }
        }
        
        return $prices;
    }

    /**
     * Obtiene el precio por tipo
     */
    public function getPriceByType(string $type): ?float
    {
        switch ($type) {
            case 'sale_price':
                return $this->sale_price;
            case 'wholesale_price':
                return $this->wholesale_price;
            case 'check_price':
                return $this->check_price;
            case 'credit_price':
                return $this->credit_price;
            case 'special_price':
                return $this->special_price;
            default:
                // Buscar en precios personalizados
                if (str_starts_with($type, 'custom_') && $this->custom_prices) {
                    $index = str_replace('custom_', '', $type);
                    return $this->custom_prices[$index]['price'] ?? null;
                }
                return null;
        }
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