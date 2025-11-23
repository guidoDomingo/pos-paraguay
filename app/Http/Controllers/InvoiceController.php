<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('company_id', Auth::user()->company_id)
            ->with(['sale.user', 'fiscalStamp'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('invoices.index', compact('invoices'));
    }
    
    public function show(Invoice $invoice)
    {
        $invoice->load(['sale.saleItems.product', 'sale.user', 'fiscalStamp']);
        return view('invoices.show', compact('invoice'));
    }
    
    public function print(Invoice $invoice)
    {
        return view('invoices.dnit-format', compact('invoice'));
    }

    public function printTicket(Sale $sale)
    {
        return view('tickets.thermal-format', compact('sale'));
    }
}