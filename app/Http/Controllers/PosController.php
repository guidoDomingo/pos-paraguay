<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index()
    {
        $openRegister = CashRegister::getOpenRegister(Auth::user()->company_id);

        if (!$openRegister) {
            return redirect()->route('cash.open')
                ->with('warning', 'Debes abrir una caja antes de usar el Terminal POS.');
        }

        return view('pos.index');
    }
}
