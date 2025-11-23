<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Invoice;
use App\Models\User;
use App\Models\FiscalStamp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        
        // Estadísticas generales
        $stats = [
            'total_products' => Product::where('company_id', $companyId)->count(),
            'active_products' => Product::where('company_id', $companyId)->where('is_active', true)->count(),
            'low_stock_products' => Product::where('company_id', $companyId)
                ->where('is_active', true)
                ->where('stock', '<=', DB::raw('min_stock'))
                ->count(),
            'total_sales_today' => Sale::where('company_id', $companyId)
                ->whereDate('created_at', today())
                ->count(),
            'revenue_today' => Sale::where('company_id', $companyId)
                ->whereDate('created_at', today())
                ->sum('total_amount'),
            'revenue_month' => Sale::where('company_id', $companyId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'total_invoices' => Invoice::where('company_id', $companyId)->count(),
            'available_fiscal_stamps' => FiscalStamp::where('company_id', $companyId)
                ->where('is_active', true)
                ->count()
        ];
        
        // Productos con stock bajo
        $lowStockProducts = Product::where('company_id', $companyId)
            ->where('is_active', true)
            ->where('stock', '<=', DB::raw('min_stock'))
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
            
        // Ventas recientes
        $recentSales = Sale::where('company_id', $companyId)
            ->with(['user', 'saleItems.product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Productos más vendidos
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.company_id', $companyId)
            ->select('products.name', 'products.code', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard', compact('stats', 'lowStockProducts', 'recentSales', 'topProducts'));
    }
}