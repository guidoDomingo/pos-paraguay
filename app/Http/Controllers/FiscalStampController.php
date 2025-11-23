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
            'stamp_number' => 'required|string|unique:fiscal_stamps,stamp_number',
            'expiry_date' => 'required|date|after:today',
            'invoice_range_start' => 'required|integer|min:1',
            'invoice_range_end' => 'required|integer|gt:invoice_range_start'
        ]);
        
        FiscalStamp::create(array_merge($request->all(), [
            'company_id' => Auth::user()->company_id,
            'is_active' => true
        ]));
        
        return redirect()->route('fiscal-stamps.index')->with('success', 'Timbre fiscal creado exitosamente');
    }
    
    public function show(FiscalStamp $fiscalStamp)
    {
        return view('fiscal-stamps.show', compact('fiscalStamp'));
    }
}