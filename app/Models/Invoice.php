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
        // Campos de facturación electrónica FacturaSend
        'is_electronic',
        'facturasend_id',
        'cdc',
        'electronic_status',
        'electronic_sent_at',
        'electronic_approved_at',
        'xml_data',
        'qr_data',
        'lote_id',
        'electronic_error',
        'numero_electronico',
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
        // Casts para facturación electrónica
        'is_electronic' => 'boolean',
        'electronic_sent_at' => 'datetime',
        'electronic_approved_at' => 'datetime',
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
        $totalConIva = 0;

        foreach ($this->items as $item) {
            switch ($item->iva_type) {
                case 'EXENTO':
                    $subtotalExento += $item->total_price;
                    break;
                case 'IVA_5':
                    // El total_price incluye el IVA, calculamos la base imponible
                    $baseImponible = $item->total_price - $item->iva_amount;
                    $subtotalIva5 += $baseImponible;
                    $totalIva5 += $item->iva_amount;
                    break;
                case 'IVA_10':
                    // El total_price incluye el IVA, calculamos la base imponible
                    $baseImponible = $item->total_price - $item->iva_amount;
                    $subtotalIva10 += $baseImponible;
                    $totalIva10 += $item->iva_amount;
                    break;
            }
            $totalConIva += $item->total_price;
        }

        $this->update([
            'subtotal_exento' => $subtotalExento,
            'subtotal_iva_5' => $subtotalIva5,
            'subtotal_iva_10' => $subtotalIva10,
            'total_iva_5' => $totalIva5,
            'total_iva_10' => $totalIva10,
            'total_iva' => $totalIva5 + $totalIva10,
            'total_amount' => $totalConIva,
        ]);
    }

    public function markAsPrinted(): void
    {
        $this->update([
            'is_printed' => true,
            'printed_at' => now(),
        ]);
    }

    // =====================================================
    // MÉTODOS DE FACTURACIÓN ELECTRÓNICA FACTURASEND
    // =====================================================

    /**
     * Verificar si la factura es electrónica
     */
    public function isElectronic(): bool
    {
        return $this->is_electronic;
    }

    /**
     * Verificar si la factura electrónica está aprobada
     */
    public function isElectronicApproved(): bool
    {
        return $this->electronic_status === 'approved';
    }

    /**
     * Verificar si la factura electrónica tiene error
     */
    public function hasElectronicError(): bool
    {
        return $this->electronic_status === 'error';
    }

    /**
     * Obtener el estado de la factura electrónica en formato legible
     */
    public function getElectronicStatusText(): string
    {
        return match($this->electronic_status) {
            'pending' => 'Pendiente',
            'generated' => 'Generado',
            'approved' => 'Aprobado por SET',
            'rejected' => 'Rechazado por SET',
            'error' => 'Error',
            default => 'No definido'
        };
    }

    /**
     * Marcar como enviado a FacturaSend
     */
    public function markAsElectronicSent(string $loteId): void
    {
        $this->update([
            'is_electronic' => true,
            'electronic_status' => 'generated',
            'electronic_sent_at' => now(),
            'lote_id' => $loteId,
        ]);
    }

    /**
     * Marcar como aprobado por SET
     */
    public function markAsElectronicApproved(): void
    {
        $this->update([
            'electronic_status' => 'approved',
            'electronic_approved_at' => now(),
        ]);
    }

    /**
     * Marcar como rechazado por SET
     */
    public function markAsElectronicRejected(string $error): void
    {
        $this->update([
            'electronic_status' => 'rejected',
            'electronic_error' => $error,
        ]);
    }

    /**
     * Obtener URL del KuDE (representación impresa)
     */
    public function getKudeUrl(): ?string
    {
        if (!$this->cdc) {
            return null;
        }

        // FacturaSend usa la misma URL para test y production
        $baseUrl = 'https://api.facturasend.com.py';

        return $baseUrl . '/' . config('facturasend.tenant_id') . '/de/kude/' . $this->cdc;
    }

    /**
     * Verificar si puede enviarse como factura electrónica
     */
    public function canSendElectronic(): bool
    {
        // Debe tener cliente con RUC para facturación electrónica
        if (!$this->customer || !$this->customer->ruc) {
            return false;
        }

        // No debe estar ya enviada
        if ($this->is_electronic) {
            return false;
        }

        // Debe tener items
        if ($this->items->isEmpty()) {
            return false;
        }

        return true;
    }

    /**
     * Scope para facturas electrónicas
     */
    public function scopeElectronic($query)
    {
        return $query->where('is_electronic', true);
    }

    /**
     * Scope para facturas electrónicas pendientes
     */
    public function scopeElectronicPending($query)
    {
        return $query->where('is_electronic', true)
                     ->where('electronic_status', 'pending');
    }

    /**
     * Scope para facturas electrónicas aprobadas
     */
    public function scopeElectronicApproved($query)
    {
        return $query->where('is_electronic', true)
                     ->where('electronic_status', 'approved');
    }
}