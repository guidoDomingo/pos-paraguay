<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::where('company_id', Auth::user()->company_id)
            ->withCount('products')
            ->orderBy('name');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        }

        $categories = $query->paginate(20)->withQueryString();

        return view('categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('categories.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        
        Category::create(array_merge($request->all(), [
            'company_id' => Auth::user()->company_id,
            'is_active' => $request->has('is_active')
        ]));
        
        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente');
    }
    
    public function show(Category $category, Request $request)
    {
        $products = $category->products()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'      => $category->products()->count(),
            'activos'    => $category->products()->where('is_active', true)->count(),
            'inactivos'  => $category->products()->where('is_active', false)->count(),
            'stock_total'=> $category->products()->where('track_stock', true)->sum('stock'),
            'stock_ok'   => $category->products()->where('track_stock', true)->whereRaw('stock > min_stock')->count(),
            'stock_bajo' => $category->products()->where('track_stock', true)->whereRaw('stock <= min_stock')->count(),
            'con_stock'  => $category->products()->where('track_stock', true)->count(),
        ];

        return view('categories.show', compact('category', 'products', 'stats'));
    }
    
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada exitosamente');
    }
    
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'No se puede eliminar una categoría con productos asociados');
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente');
    }
}