<?php

namespace App\Services;

use App\Models\Company;
use App\Models\FiscalStamp;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Exception;

class InvoiceNumberService
{
    /**
     * Genera el siguiente número de factura para una empresa
     */
    public function getNextInvoiceNumber(int $companyId): string
    {
        $fiscalStamp = $this->getActiveFiscalStamp($companyId);
        
        if (!$fiscalStamp) {
            throw new Exception('No hay timbrado fiscal activo para esta empresa');
        }

        if (!$fiscalStamp->isValid()) {
            throw new Exception('El timbrado fiscal no es válido o ha vencido');
        }

        return $fiscalStamp->getNextInvoiceNumber();
    }

    /**
     * Obtiene el timbrado fiscal activo
     */
    public function getActiveFiscalStamp(int $companyId): ?FiscalStamp
    {
        return FiscalStamp::where('company_id', $companyId)
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Valida que el timbrado fiscal sea válido (errores bloqueantes)
     */
    public function validateFiscalStamp(FiscalStamp $fiscalStamp): array
    {
        $errors = [];

        // Verificar que esté activo
        if (!$fiscalStamp->is_active) {
            $errors[] = 'El timbrado fiscal está inactivo';
        }

        // Verificar vigencia
        if (now() < $fiscalStamp->valid_from) {
            $errors[] = 'El timbrado fiscal aún no está vigente';
        }

        if (now() > $fiscalStamp->valid_until) {
            $errors[] = 'El timbrado fiscal ha vencido';
        }

        // Verificar numeración disponible - solo bloquear si está agotado
        if ($fiscalStamp->current_invoice_number >= $fiscalStamp->max_invoice_number) {
            $errors[] = 'Se ha agotado la numeración del timbrado fiscal';
        }

        return $errors;
    }

    /**
     * Obtiene advertencias sobre el timbrado fiscal (no bloqueantes)
     */
    public function getFiscalStampWarnings(FiscalStamp $fiscalStamp): array
    {
        $warnings = [];
        
        $remaining = $fiscalStamp->getRemainingInvoices();
        
        // Advertencia si quedan pocos números
        if ($remaining > 0 && $remaining <= 100) {
            $warnings[] = "Advertencia: Quedan solo {$remaining} números de factura disponibles";
        }
        
        // Advertencia si está próximo a vencer
        if ($fiscalStamp->valid_until->diffInDays() <= 30 && !$fiscalStamp->valid_until->isPast()) {
            $warnings[] = "Advertencia: El timbrado vence en {$fiscalStamp->valid_until->diffInDays()} días";
        }
        
        return $warnings;
    }

    /**
     * Crea una factura fiscal a partir de una venta
     */
    public function createInvoiceFromSale(Sale $sale, int $customerId, array $additionalData = []): Invoice
    {
        DB::beginTransaction();

        try {
            $customer = Customer::findOrFail($customerId);
            $fiscalStamp = $this->getActiveFiscalStamp($sale->company_id);

            if (!$fiscalStamp) {
                throw new Exception('No hay timbrado fiscal activo');
            }

            $validationErrors = $this->validateFiscalStamp($fiscalStamp);
            if (!empty($validationErrors)) {
                throw new Exception('Timbrado fiscal inválido: ' . implode(', ', $validationErrors));
            }

            // Generar número de factura
            $invoiceNumber = $fiscalStamp->getNextInvoiceNumber();
            $parts = explode('-', $invoiceNumber);

            // Crear la factura
            $invoice = Invoice::create([
                'company_id' => $sale->company_id,
                'sale_id' => $sale->id,
                'customer_id' => $customer->id,
                'fiscal_stamp_id' => $fiscalStamp->id,
                'invoice_number' => $invoiceNumber,
                'stamp_number' => $fiscalStamp->stamp_number,
                'establishment' => $parts[0],
                'point_of_sale' => $parts[1],
                'sequential_number' => $parts[2],
                'customer_name' => $customer->name,
                'customer_ruc' => $customer->getFormattedRucAttribute(),
                'customer_address' => $customer->address,
                'condition' => $additionalData['condition'] ?? 'CONTADO',
                'invoice_date' => $additionalData['invoice_date'] ?? now()->toDateString(),
                'observations' => $additionalData['observations'] ?? null,
                'subtotal_exento' => 0,
                'subtotal_iva_5' => 0,
                'subtotal_iva_10' => 0,
                'total_iva_5' => 0,
                'total_iva_10' => 0,
                'total_iva' => 0,
                'total_amount' => $sale->total_amount,
            ]);

            // Crear los ítems de la factura
            foreach ($sale->items as $saleItem) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $saleItem->product_id,
                    'product_code' => $saleItem->product_code,
                    'product_name' => $saleItem->product_name,
                    'quantity' => $saleItem->quantity,
                    'unit_price' => $saleItem->unit_price,
                    'total_price' => $saleItem->total_price,
                    'iva_type' => $saleItem->iva_type,
                    'iva_amount' => $saleItem->iva_amount,
                ]);
            }

            // Calcular totales de IVA
            $invoice->calculateTotals();

            // Actualizar la venta para indicar que tiene factura
            $sale->update(['sale_type' => 'INVOICE']);

            DB::commit();

            return $invoice;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Convierte un ticket en factura
     */
    public function convertTicketToInvoice(Sale $sale, int $customerId, array $additionalData = []): Invoice
    {
        if ($sale->sale_type !== 'TICKET') {
            throw new Exception('Solo se pueden convertir tickets en facturas');
        }

        if ($sale->invoice) {
            throw new Exception('Esta venta ya tiene una factura asociada');
        }

        return $this->createInvoiceFromSale($sale, $customerId, $additionalData);
    }

    /**
     * Obtiene estadísticas del timbrado fiscal
     */
    public function getFiscalStampStats(int $companyId): array
    {
        $fiscalStamp = $this->getActiveFiscalStamp($companyId);

        if (!$fiscalStamp) {
            return [
                'active' => false,
                'message' => 'No hay timbrado fiscal activo',
            ];
        }

        $usedNumbers = $fiscalStamp->current_invoice_number;
        $totalNumbers = $fiscalStamp->max_invoice_number;
        $remainingNumbers = $totalNumbers - $usedNumbers;
        $usagePercentage = ($usedNumbers / $totalNumbers) * 100;

        $daysUntilExpiry = now()->diffInDays($fiscalStamp->valid_until, false);

        return [
            'active' => true,
            'stamp_number' => $fiscalStamp->stamp_number,
            'establishment' => $fiscalStamp->establishment,
            'point_of_sale' => $fiscalStamp->point_of_sale,
            'valid_from' => $fiscalStamp->valid_from->format('d/m/Y'),
            'valid_until' => $fiscalStamp->valid_until->format('d/m/Y'),
            'used_numbers' => $usedNumbers,
            'total_numbers' => $totalNumbers,
            'remaining_numbers' => $remainingNumbers,
            'usage_percentage' => round($usagePercentage, 2),
            'days_until_expiry' => $daysUntilExpiry,
            'is_near_expiry' => $daysUntilExpiry <= 30,
            'is_near_limit' => $remainingNumbers <= 100,
            'warnings' => $this->getWarnings($fiscalStamp),
        ];
    }

    /**
     * Obtiene advertencias sobre el estado del timbrado
     */
    private function getWarnings(FiscalStamp $fiscalStamp): array
    {
        $warnings = [];

        $remaining = $fiscalStamp->getRemainingInvoices();
        $daysUntilExpiry = now()->diffInDays($fiscalStamp->valid_until, false);

        if ($remaining <= 50) {
            $warnings[] = "CRÍTICO: Solo quedan {$remaining} números de factura";
        } elseif ($remaining <= 100) {
            $warnings[] = "ATENCIÓN: Quedan {$remaining} números de factura";
        }

        if ($daysUntilExpiry <= 7) {
            $warnings[] = "CRÍTICO: El timbrado vence en {$daysUntilExpiry} días";
        } elseif ($daysUntilExpiry <= 30) {
            $warnings[] = "ATENCIÓN: El timbrado vence en {$daysUntilExpiry} días";
        }

        return $warnings;
    }

    /**
     * Crear un nuevo timbrado fiscal
     */
    public function createFiscalStamp(array $data): FiscalStamp
    {
        // Desactivar timbrados anteriores
        FiscalStamp::where('company_id', $data['company_id'])
            ->update(['is_active' => false]);

        return FiscalStamp::create($data);
    }

    /**
     * Obtiene el próximo número de factura sin incrementar el contador
     */
    public function previewNextInvoiceNumber(int $companyId): string
    {
        $fiscalStamp = $this->getActiveFiscalStamp($companyId);
        
        if (!$fiscalStamp) {
            throw new Exception('No hay timbrado fiscal activo');
        }

        $nextNumber = $fiscalStamp->current_invoice_number + 1;

        return sprintf(
            '%s-%s-%07d',
            $fiscalStamp->establishment,
            $fiscalStamp->point_of_sale,
            $nextNumber
        );
    }
}