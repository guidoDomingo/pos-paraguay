<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::where('company_id', Auth::user()->company_id)
            ->with(['sale.user', 'fiscalStamp'])
            ->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_ruc', 'like', "%{$search}%");
            });
        }

        if ($condition = $request->input('condition')) {
            $query->where('condition', $condition);
        }

        if ($request->input('electronic') === 'electronic') {
            $query->where('is_electronic', true);
        } elseif ($request->input('electronic') === 'normal') {
            $query->where('is_electronic', false);
        }

        $invoices = $query->paginate(20)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }
    
    public function show(Invoice $invoice)
    {
        $invoice->load(['sale.saleItems.product', 'sale.user', 'fiscalStamp']);
        return view('invoices.show', compact('invoice'));
    }
    
    public function print(Invoice $invoice)
    {
        return redirect()->route('pdf.invoice', $invoice->sale_id);
    }

    public function printTicket(Sale $sale)
    {
        return view('tickets.thermal-format', compact('sale'));
    }
}