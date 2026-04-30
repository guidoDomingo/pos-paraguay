<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;
        
        $products = Product::where('company_id', $companyId)
            ->where('track_stock', true)
            ->with(['category', 'inventoryMovements' => function($query) {
                $query->latest()->limit(5);
            }])
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($request->status, function($query, $status) {
                if ($status === 'low_stock') {
                    $query->whereRaw('stock <= min_stock');
                } elseif ($status === 'out_of_stock') {
                    $query->where('stock', '<=', 0);
                } elseif ($status === 'in_stock') {
                    $query->where('stock', '>', 0);
                }
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total_products' => Product::where('company_id', $companyId)->where('track_stock', true)->count(),
            'low_stock' => Product::where('company_id', $companyId)->where('track_stock', true)->whereRaw('stock <= min_stock')->count(),
            'out_of_stock' => Product::where('company_id', $companyId)->where('track_stock', true)->where('stock', '<=', 0)->count(),
            'total_value' => Product::where('company_id', $companyId)->where('track_stock', true)->sum(DB::raw('stock * cost_price'))
        ];

        $categories = \App\Models\Category::where('company_id', $companyId)->get();

        return view('inventory.index', compact('products', 'stats', 'categories'));
    }

    public function movements(Request $request)
    {
        $companyId = Auth::user()->company_id;
        
        $movements = InventoryMovement::where('company_id', $companyId)
            ->with(['product', 'user'])
            ->when($request->product_id, function($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->when($request->type, function($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->date_from, function($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->paginate(50);

        $products = Product::where('company_id', $companyId)->where('track_stock', true)->get();

        return view('inventory.movements', compact('movements', 'products'));
    }

    public function adjust(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $products = Product::where('company_id', $companyId)
            ->where('track_stock', true)
            ->orderBy('name')
            ->get();

        return view('inventory.adjust', compact('products'));
    }

    public function storeAdjustment(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|numeric|min:0',
            'reason' => 'required|string|min:3'
        ]);

        try {
            $result = DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);

                $oldStock = (float)$product->stock;
                $quantity = (float)$request->quantity;
                $type = $request->adjustment_type;

                // Calcular nuevo stock
                switch ($type) {
                    case 'add':
                        $newStock = $oldStock + $quantity;
                        $movementQuantity = $quantity;
                        break;
                    case 'subtract':
                        $newStock = max(0, $oldStock - $quantity);
                        $movementQuantity = -$quantity;
                        break;
                    case 'set':
                        $newStock = $quantity;
                        $movementQuantity = $quantity - $oldStock;
                        break;
                    default:
                        throw new \InvalidArgumentException('Tipo de ajuste inválido');
                }

                // Actualizar stock del producto
                $product->stock = $newStock;
                $product->save();

                // Crear movimiento de inventario
                $movement = InventoryMovement::create([
                    'company_id' => Auth::user()->company_id,
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'quantity' => $movementQuantity,
                    'previous_stock' => $oldStock,
                    'new_stock' => $newStock,
                    'reason' => $request->reason,
                    'user_id' => auth()->id()
                ]);
                
                return [
                    'product' => $product,
                    'movement' => $movement,
                    'old_stock' => $oldStock,
                    'new_stock' => $newStock
                ];
            });

            return redirect()
                ->route('inventory.index')
                ->with('success', "Stock ajustado correctamente. Stock anterior: {$result['old_stock']}, Stock nuevo: {$result['new_stock']}");

        } catch (\Exception $e) {
            Log::error('Error en storeAdjustment:', [
                'message' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al ajustar el stock: ' . $e->getMessage());
        }
    }

    public function lowStock()
    {
        $companyId = Auth::user()->company_id;
        
        $products = Product::where('company_id', $companyId)
            ->where('track_stock', true)
            ->whereRaw('stock <= min_stock')
            ->with('category')
            ->orderBy('stock')
            ->paginate(20);

        return view('inventory.low-stock', compact('products'));
    }

    public function reports()
    {
        $companyId = Auth::user()->company_id;

        // Estadísticas generales
        $stats = [
            'total_products' => Product::where('company_id', $companyId)->where('track_stock', true)->count(),
            'total_stock_value' => Product::where('company_id', $companyId)->where('track_stock', true)->sum(DB::raw('stock * cost_price')),
            'low_stock_products' => Product::where('company_id', $companyId)->where('track_stock', true)->whereRaw('stock <= min_stock')->count(),
            'out_of_stock_products' => Product::where('company_id', $companyId)->where('track_stock', true)->where('stock', '<=', 0)->count()
        ];

        // Productos con más movimientos (últimos 30 días)
        $topMovedProducts = DB::table('inventory_movements')
            ->join('products', 'inventory_movements.product_id', '=', 'products.id')
            ->where('inventory_movements.company_id', $companyId)
            ->where('inventory_movements.created_at', '>=', now()->subDays(30))
            ->select('products.name', 'products.code', DB::raw('COUNT(*) as movements_count'))
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderByDesc('movements_count')
            ->limit(10)
            ->get();

        // Movimientos recientes
        $recentMovements = InventoryMovement::where('company_id', $companyId)
            ->with(['product', 'user'])
            ->latest()
            ->limit(20)
            ->get();

        return view('inventory.reports', compact('stats', 'topMovedProducts', 'recentMovements'));
    }
}
