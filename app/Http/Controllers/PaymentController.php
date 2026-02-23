<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Sale;
use App\Models\Invoice;
use App\Services\FacturaSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Registrar un nuevo pago para una venta a crédito
     */
    public function store(Request $request, Sale $sale)
    {
        // Validar que la venta sea a crédito y tenga saldo pendiente
        if ($sale->sale_condition !== 'CREDITO' || $sale->balance_due <= 0) {
            return back()->with('error', 'Esta venta no acepta pagos adicionales.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $sale->balance_due,
            'payment_method' => 'required|in:CASH,CARD,CHEQUE,TRANSFER',
            'notes' => 'nullable|string|max:500',
        ], [
            'amount.max' => 'El monto no puede ser mayor al saldo pendiente.',
        ]);

        DB::beginTransaction();

        try {
            // Registrar el pago
            $payment = Payment::create([
                'sale_id' => $sale->id,
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'payment_date' => now(),
            ]);

            // Actualizar el saldo de la venta
            $newAmountPaid = $sale->amount_paid + $request->amount;
            $newBalanceDue = $sale->total_amount - $newAmountPaid;

            $sale->update([
                'amount_paid' => $newAmountPaid,
                'balance_due' => $newBalanceDue,
            ]);

            // Si el saldo llega a 0, marcar como completado
            if ($newBalanceDue <= 0) {
                $sale->update(['status' => 'COMPLETED']);

                // Generar factura si el tipo de documento es factura y aún no tiene
                if ($sale->document_type === 'factura' && !$sale->invoice) {
                    $this->generateInvoiceForSale($sale);
                }

                $message = 'Pago registrado. Venta completada y factura generada.';
            } else {
                $message = 'Pago registrado exitosamente. Saldo pendiente: Gs. ' . number_format($newBalanceDue, 0, ',', '.');
            }

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Generar factura para una venta
     */
    private function generateInvoiceForSale(Sale $sale)
    {
        // Obtener el timbrado fiscal activo
        $fiscalStamp = \App\Models\FiscalStamp::where('company_id', $sale->company_id)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$fiscalStamp) {
            throw new \Exception('No hay timbrado fiscal activo.');
        }

        // Generar número de factura
        $lastInvoice = Invoice::where('company_id', $sale->company_id)
            ->where('fiscal_stamp_id', $fiscalStamp->id)
            ->orderBy('invoice_number', 'desc')
            ->first();

        $nextNumber = $lastInvoice 
            ? $lastInvoice->invoice_number + 1 
            : $fiscalStamp->start_number;

        if ($nextNumber > $fiscalStamp->end_number) {
            throw new \Exception('Se agotaron los números de factura del timbrado.');
        }

        // Crear la factura
        $invoice = Invoice::create([
            'company_id' => $sale->company_id,
            'sale_id' => $sale->id,
            'customer_id' => $sale->customer_id,
            'fiscal_stamp_id' => $fiscalStamp->id,
            'invoice_number' => $nextNumber,
            'formatted_invoice_number' => $fiscalStamp->establishment_code . '-' . 
                                         $fiscalStamp->expedition_point . '-' . 
                                         str_pad($nextNumber, 7, '0', STR_PAD_LEFT),
            'customer_name' => $sale->customer_name,
            'customer_document' => $sale->customer_document,
            'subtotal' => $sale->subtotal,
            'tax_amount' => $sale->tax_amount,
            'discount_amount' => $sale->discount_amount,
            'total_amount' => $sale->total_amount,
            'payment_method' => $sale->payment_method,
            'invoice_condition' => 'CONTADO', // Ya está pagado
            'status' => 'ISSUED',
            'issue_date' => now(),
        ]);

        // Crear items de factura
        foreach ($sale->items as $saleItem) {
            \App\Models\InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $saleItem->product_id,
                'product_code' => $saleItem->product_code,
                'product_name' => $saleItem->product_name,
                'quantity' => $saleItem->quantity,
                'unit_price' => $saleItem->unit_price,
                'total_price' => $saleItem->total_price,
                'iva_type' => $saleItem->iva_type,
                'iva_amount' => $saleItem->iva_amount,
                'discount_percentage' => $saleItem->discount_percentage ?? 0,
                'discount_amount' => $saleItem->discount_amount ?? 0,
            ]);
        }

        return $invoice;
    }
}
