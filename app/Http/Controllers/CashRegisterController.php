<?php

namespace App\Http\Controllers;

use App\Models\CashMovement;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashRegisterController extends Controller
{
    // ── Caja actual (abierta) ───────────────────────────────────────────────

    public function current()
    {
        $register = CashRegister::getOpenRegister(Auth::user()->company_id);

        if (!$register) {
            return redirect()->route('cash.open')
                ->with('info', 'No hay una caja abierta. Por favor, abre una caja para continuar.');
        }

        $register->load([
            'user',
            'sales' => fn($q) => $q->with('saleItems')->where('status', '!=', 'CANCELLED')->orderByDesc('sale_date'),
            'movements' => fn($q) => $q->with('user')->orderByDesc('created_at'),
        ]);

        $byMethod   = $register->getTotalByPaymentMethod();
        $incomes    = $register->getTotalIncomes();
        $expenses   = $register->getTotalExpenses();
        $expected   = $register->calculateExpectedAmount();

        return view('cash.current', compact('register', 'byMethod', 'incomes', 'expenses', 'expected'));
    }

    // ── Abrir caja ──────────────────────────────────────────────────────────

    public function open()
    {
        $existing = CashRegister::getOpenRegister(Auth::user()->company_id);

        if ($existing) {
            return redirect()->route('cash.current')
                ->with('info', 'Ya hay una caja abierta.');
        }

        return view('cash.open');
    }

    public function store(Request $request)
    {
        $request->validate([
            'opening_amount' => 'required|numeric|min:0',
            'opening_notes'  => 'nullable|string|max:500',
        ]);

        $existing = CashRegister::getOpenRegister(Auth::user()->company_id);
        if ($existing) {
            return redirect()->route('cash.current')
                ->with('info', 'Ya hay una caja abierta.');
        }

        CashRegister::create([
            'company_id'     => Auth::user()->company_id,
            'warehouse_id'   => Auth::user()->warehouse_id ?? null,
            'user_id'        => Auth::id(),
            'opening_amount' => $request->opening_amount,
            'opening_notes'  => $request->opening_notes,
            'opened_at'      => now(),
            'status'         => 'OPEN',
        ]);

        return redirect()->route('cash.current')
            ->with('success', 'Caja abierta exitosamente.');
    }

    // ── Cerrar caja (arqueo) ─────────────────────────────────────────────────

    public function closeForm()
    {
        $register = CashRegister::getOpenRegister(Auth::user()->company_id);

        if (!$register) {
            return redirect()->route('cash.open')
                ->with('error', 'No hay una caja abierta.');
        }

        $register->load(['sales' => fn($q) => $q->where('status', '!=', 'CANCELLED'), 'movements']);

        $byMethod = $register->getTotalByPaymentMethod();
        $incomes  = $register->getTotalIncomes();
        $expenses = $register->getTotalExpenses();
        $expected = $register->calculateExpectedAmount();

        return view('cash.close', compact('register', 'byMethod', 'incomes', 'expenses', 'expected'));
    }

    public function closeStore(Request $request)
    {
        $request->validate([
            'closing_amount' => 'required|numeric|min:0',
            'closing_notes'  => 'nullable|string|max:500',
        ]);

        $register = CashRegister::getOpenRegister(Auth::user()->company_id);

        if (!$register) {
            return redirect()->route('cash.open')->with('error', 'No hay una caja abierta.');
        }

        if ($register->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $register->close((float) $request->closing_amount, $request->closing_notes);

        return redirect()->route('cash.show', $register)
            ->with('success', 'Caja cerrada exitosamente. Arqueo registrado.');
    }

    // ── Movimientos manuales ────────────────────────────────────────────────

    public function addMovement(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:INCOME,EXPENSE,REFUND',
            'amount'      => 'required|numeric|min:1',
            'description' => 'required|string|max:500',
        ]);

        $register = CashRegister::getOpenRegister(Auth::user()->company_id);

        if (!$register) {
            return back()->with('error', 'No hay una caja abierta.');
        }

        CashMovement::create([
            'cash_register_id' => $register->id,
            'user_id'          => Auth::id(),
            'type'             => $request->type,
            'amount'           => $request->amount,
            'description'      => $request->description,
        ]);

        $label = match($request->type) {
            'INCOME'  => 'Ingreso',
            'EXPENSE' => 'Egreso',
            'REFUND'  => 'Devolución',
        };

        return back()->with('success', "{$label} registrado exitosamente.");
    }

    // ── Historial ───────────────────────────────────────────────────────────

    public function index()
    {
        $registers = CashRegister::where('company_id', Auth::user()->company_id)
            ->with('user')
            ->orderByDesc('opened_at')
            ->paginate(20);

        return view('cash.index', compact('registers'));
    }

    // ── Detalle de una caja cerrada ─────────────────────────────────────────

    public function show(CashRegister $cashRegister)
    {
        if ($cashRegister->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $cashRegister->load([
            'user',
            'sales' => fn($q) => $q->with('saleItems')->where('status', '!=', 'CANCELLED')->orderByDesc('sale_date'),
            'movements' => fn($q) => $q->with('user')->orderByDesc('created_at'),
        ]);

        $byMethod = $cashRegister->getTotalByPaymentMethod();
        $incomes  = $cashRegister->getTotalIncomes();
        $expenses = $cashRegister->getTotalExpenses();

        return view('cash.show', compact('cashRegister', 'byMethod', 'incomes', 'expenses'));
    }
}
