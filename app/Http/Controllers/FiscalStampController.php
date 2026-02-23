<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FiscalStamp;
use Illuminate\Support\Facades\Auth;

class FiscalStampController extends Controller
{
    public function index()
    {
        $fiscalStamps = FiscalStamp::where('company_id', Auth::user()->company_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('fiscal-stamps.index', compact('fiscalStamps'));
    }
    
    public function create()
    {
        return view('fiscal-stamps.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'stamp_number' => 'required|string|max:20|unique:fiscal_stamps,stamp_number',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'establishment' => 'required|string|size:3',
            'point_of_sale' => 'required|string|size:3',
            'current_invoice_number' => 'required|integer|min:0',
            'max_invoice_number' => 'required|integer|min:1|gt:current_invoice_number',
        ]);
        
        FiscalStamp::create([
            'company_id' => Auth::user()->company_id,
            'stamp_number' => $request->stamp_number,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'establishment' => $request->establishment,
            'point_of_sale' => $request->point_of_sale,
            'current_invoice_number' => $request->current_invoice_number,
            'max_invoice_number' => $request->max_invoice_number,
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        
        return redirect()->route('fiscal-stamps.index')->with('success', 'Timbrado fiscal creado exitosamente');
    }
    
    public function show(FiscalStamp $fiscalStamp)
    {
        return view('fiscal-stamps.show', compact('fiscalStamp'));
    }
    
    public function edit(FiscalStamp $fiscalStamp)
    {
        // Verificar que el timbrado pertenece a la compañía del usuario
        if ($fiscalStamp->company_id !== Auth::user()->company_id) {
            abort(403);
        }
        
        return view('fiscal-stamps.edit', compact('fiscalStamp'));
    }
    
    public function update(Request $request, FiscalStamp $fiscalStamp)
    {
        // Verificar que el timbrado pertenece a la compañía del usuario
        if ($fiscalStamp->company_id !== Auth::user()->company_id) {
            abort(403);
        }
        
        $request->validate([
            'stamp_number' => 'required|string|max:20|unique:fiscal_stamps,stamp_number,' . $fiscalStamp->id,
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'establishment' => 'required|string|size:3',
            'point_of_sale' => 'required|string|size:3',
            'current_invoice_number' => 'required|integer|min:0',
            'max_invoice_number' => 'required|integer|min:1|gt:current_invoice_number',
        ]);
        
        $fiscalStamp->update([
            'stamp_number' => $request->stamp_number,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'establishment' => $request->establishment,
            'point_of_sale' => $request->point_of_sale,
            'current_invoice_number' => $request->current_invoice_number,
            'max_invoice_number' => $request->max_invoice_number,
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        
        return redirect()->route('fiscal-stamps.index')->with('success', 'Timbrado fiscal actualizado exitosamente');
    }
    
    public function destroy(FiscalStamp $fiscalStamp)
    {
        // Verificar que el timbrado pertenece a la compañía del usuario
        if ($fiscalStamp->company_id !== Auth::user()->company_id) {
            abort(403);
        }
        
        // Verificar si el timbrado tiene facturas asociadas
        if ($fiscalStamp->invoices()->exists()) {
            return redirect()->route('fiscal-stamps.index')
                ->with('error', 'No se puede eliminar el timbrado porque tiene facturas asociadas');
        }
        
        $fiscalStamp->delete();
        
        return redirect()->route('fiscal-stamps.index')->with('success', 'Timbrado fiscal eliminado exitosamente');
    }
}