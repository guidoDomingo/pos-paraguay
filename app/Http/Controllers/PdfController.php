<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\InvoiceSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generateInvoice($saleId)
    {
        $sale = Sale::with(['items', 'invoice.fiscalStamp', 'company', 'user'])->findOrFail($saleId);
        $settings = InvoiceSetting::getSettings();
        
        // Obtener la factura asociada
        $invoice = $sale->invoice;
        
        if (!$invoice) {
            abort(404, 'No se encontró la factura asociada a esta venta');
        }

        // Cargar items de la factura si no están cargados
        $invoice->load(['items', 'fiscalStamp']);

        $paperSize = ($settings->paper_size && $settings->paper_size !== 'Ticket')
            ? $settings->paper_size
            : 'letter';

        $pdf = Pdf::loadView('pdf.invoice', compact('sale', 'invoice', 'settings'))
            ->setPaper($paperSize, $settings->orientation ?? 'portrait')
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0);

        return $pdf->download('factura_' . $invoice->invoice_number . '.pdf');
    }

    public function generateTicket($saleId)
    {
        $sale = Sale::with('items')->findOrFail($saleId);
        $settings = InvoiceSetting::getSettings();
        
        // Actualizar número de ticket
        $ticketNumber = $settings->getNextTicketNumber();
        $sale->update(['ticket_number' => $ticketNumber]);

        $pdf = Pdf::loadView('pdf.ticket', compact('sale', 'settings'))
            ->setPaper([0, 0, 283.46, 841.89], 'portrait') // Tamaño ticket 100mm ancho x 297mm alto
            ->setOption('margin-top', 5)
            ->setOption('margin-bottom', 5)
            ->setOption('margin-left', 5)
            ->setOption('margin-right', 5);

        return $pdf->download('ticket_' . $ticketNumber . '.pdf');
    }

    public function previewInvoice($saleId)
    {
        $sale = Sale::with(['items', 'invoice.fiscalStamp', 'invoice.items', 'company', 'user'])->findOrFail($saleId);
        $settings = InvoiceSetting::getSettings();

        // Obtener la factura asociada
        $invoice = $sale->invoice;

        if (!$invoice) {
            abort(404, 'No se encontró la factura asociada a esta venta');
        }

        $invoice->load(['items', 'fiscalStamp']);

        return view('pdf.invoice', compact('sale', 'invoice', 'settings'));
    }

    public function previewTicket($saleId)
    {
        $sale = Sale::with('items')->findOrFail($saleId);
        $settings = InvoiceSetting::getSettings();
        
        return view('pdf.ticket', compact('sale', 'settings'));
    }
}
