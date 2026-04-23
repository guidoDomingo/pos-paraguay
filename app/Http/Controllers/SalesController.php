<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;
        
        $query = Sale::where('company_id', $companyId)
            ->with(['user', 'saleItems.product']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_document', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filtro por condición de venta
        if ($request->filled('sale_condition')) {
            $query->where('sale_condition', $request->sale_condition);
        }

        // Filtro solo ventas con saldo pendiente
        if ($request->has('pending_balance') && $request->pending_balance == '1') {
            $query->where('balance_due', '>', 0);
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->all());
        
        // Estadísticas de ventas a crédito
        $creditStats = Sale::where('company_id', $companyId)
            ->where('sale_condition', 'CREDITO')
            ->selectRaw('COUNT(*) as total_credit_sales')
            ->selectRaw('SUM(balance_due) as total_balance_due')
            ->selectRaw('SUM(amount_paid) as total_collected')
            ->first();

            
        return view('sales.index', compact('sales', 'creditStats'));
    }
    
    public function show(Sale $sale)
    {
        // Verificar que la venta pertenece a la compañía del usuario
        if ($sale->company_id !== Auth::user()->company_id) {
            abort(403);
        }
        
        $sale->load(['user', 'saleItems.product', 'invoice', 'payments.user']);
        return view('sales.show', compact('sale'));
    }
    
    public function reports(Request $request)
    {
        $companyId = Auth::user()->company_id;
        
        // Fechas por defecto (mes actual)
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $userId = $request->get('user_id');
        
        // Query base para el período
        $baseQuery = Sale::where('company_id', $companyId)
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
            
        if ($userId) {
            $baseQuery->where('user_id', $userId);
        }
        
        // Estadísticas generales
        $totalSales = (clone $baseQuery)->sum('total_amount');
        $salesCount = (clone $baseQuery)->count();
        $averageSale = $salesCount > 0 ? $totalSales / $salesCount : 0;
        $itemsSold = (clone $baseQuery)->withSum('saleItems', 'quantity')->get()->sum('sale_items_sum_quantity');

        // Rentabilidad (ganancia = precio venta - costo)
        $profitQuery = DB::table('sale_items as si')
            ->join('sales as s', 'si.sale_id', '=', 's.id')
            ->join('products as p', 'si.product_id', '=', 'p.id')
            ->where('s.company_id', $companyId)
            ->where('s.status', '!=', 'CANCELLED')
            ->whereBetween('s.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        if ($userId) $profitQuery->where('s.user_id', $userId);

        $totalCost   = (clone $profitQuery)->selectRaw('SUM(p.cost_price * si.quantity) as total')->value('total') ?? 0;
        $totalProfit = (clone $profitQuery)->selectRaw('SUM((si.unit_price - p.cost_price) * si.quantity) as total')->value('total') ?? 0;
        $profitMargin = $totalSales > 0 ? round(($totalProfit / $totalSales) * 100, 1) : 0;
        
        // Ventas por día
        $salesByDay = (clone $baseQuery)->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total'),
            DB::raw('COUNT(*) as count')
        )->groupBy('date')
        ->orderBy('date')
        ->get();
        
        // Productos más vendidos
        $topProducts = SaleItem::select('product_id', 'products.name as product_name')
            ->selectRaw('SUM(sale_items.quantity) as total_quantity')
            ->selectRaw('SUM(sale_items.total_price) as total_revenue')
            ->selectRaw('SUM((sale_items.unit_price - products.cost_price) * sale_items.quantity) as total_profit')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.company_id', $companyId)
            ->where('sales.status', '!=', 'CANCELLED')
            ->whereBetween('sales.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->when($userId, function($query, $userId) {
                return $query->where('sales.user_id', $userId);
            })
            ->groupBy('product_id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
            
        // Ventas por vendedor
        $salesByUser = Sale::select('user_id', 'users.name as user_name')
            ->selectRaw('COUNT(*) as sales_count')
            ->selectRaw('SUM(total_amount) as total_amount')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->where('sales.company_id', $companyId)
            ->whereBetween('sales.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->when($userId, function($query, $userId) {
                return $query->where('sales.user_id', $userId);
            })
            ->groupBy('user_id', 'users.name')
            ->orderBy('total_amount', 'desc')
            ->get();
            
        // Lista de vendedores para el filtro
        $users = User::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        // Detalle de ventas para la tabla
        $salesDetail = (clone $baseQuery)->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        // Datos pre-formateados para Chart.js
        $chartDayLabels  = $salesByDay->map(fn($r) => \Carbon\Carbon::parse($r->date)->format('d/m'))->values()->toArray();
        $chartDayTotals  = $salesByDay->pluck('total')->map(fn($v) => (float)$v)->values()->toArray();
        $chartDayCounts  = $salesByDay->pluck('count')->map(fn($v) => (int)$v)->values()->toArray();

        $chartProdLabels  = $topProducts->pluck('product_name')->values()->toArray();
        $chartProdRevenue = $topProducts->pluck('total_revenue')->map(fn($v) => (float)$v)->values()->toArray();
        $chartProdQty     = $topProducts->pluck('total_quantity')->map(fn($v) => (int)$v)->values()->toArray();
        $chartProdProfit  = $topProducts->pluck('total_profit')->map(fn($v) => (float)$v)->values()->toArray();

        $chartUserLabels = $salesByUser->pluck('user_name')->values()->toArray();
        $chartUserTotals = $salesByUser->pluck('total_amount')->map(fn($v) => (float)$v)->values()->toArray();

        return view('sales.reports', compact(
            'totalSales', 'salesCount', 'averageSale', 'itemsSold',
            'totalProfit', 'totalCost', 'profitMargin',
            'salesByDay', 'topProducts', 'salesByUser', 'users', 'salesDetail',
            'startDate', 'endDate', 'userId',
            'chartDayLabels', 'chartDayTotals', 'chartDayCounts',
            'chartProdLabels', 'chartProdRevenue', 'chartProdQty', 'chartProdProfit',
            'chartUserLabels', 'chartUserTotals'
        ));
    }
}