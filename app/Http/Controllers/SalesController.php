<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\Product;
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
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(total_price) as total_revenue')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.company_id', $companyId)
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
        
        return view('sales.reports', compact(
            'totalSales', 'salesCount', 'averageSale', 'itemsSold',
            'salesByDay', 'topProducts', 'salesByUser', 'users', 'salesDetail',
            'startDate', 'endDate', 'userId'
        ));
    }
}