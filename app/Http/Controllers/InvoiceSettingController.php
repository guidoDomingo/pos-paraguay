<?php

namespace App\Http\Controllers;

use App\Models\InvoiceSetting;
use Illuminate\Http\Request;

class InvoiceSettingController extends Controller
{
    public function index()
    {
        $settings = InvoiceSetting::getSettings();
        return view('settings.invoice', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_ruc' => 'nullable|string|max:20',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'invoice_prefix' => 'required|string|max:10',
            'invoice_suffix' => 'nullable|string|max:10',
            'invoice_counter' => 'required|integer|min:1',
            'ticket_prefix' => 'required|string|max:10',
            'ticket_suffix' => 'nullable|string|max:10',
            'ticket_counter' => 'required|integer|min:1',
            'paper_size' => 'required|in:A4,Letter,Ticket',
            'orientation' => 'required|in:portrait,landscape',
            'default_iva_rate' => 'required|numeric|min:0|max:100',
            'default_printer' => 'nullable|string|max:255',
            'ticket_printer' => 'nullable|string|max:255',
            'invoice_printer' => 'nullable|string|max:255',
            'footer_text' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
        ]);

        $settings = InvoiceSetting::getSettings();
        
        $data = $request->all();
        $data['invoice_auto_increment'] = $request->has('invoice_auto_increment');
        $data['ticket_auto_increment'] = $request->has('ticket_auto_increment');
        $data['auto_print_tickets'] = $request->has('auto_print_tickets');
        $data['auto_print_invoices'] = $request->has('auto_print_invoices');

        // Manejar logo si se sube
        if ($request->hasFile('company_logo')) {
            $logoPath = $request->file('company_logo')->store('logos', 'public');
            $data['company_logo'] = $logoPath;
        }

        $settings->update($data);

        return redirect()->back()->with('success', 'Configuraciones actualizadas correctamente');
    }
}
