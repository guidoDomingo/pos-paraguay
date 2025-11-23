<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('company_id', Auth::user()->company_id)
            ->with('category')
            ->orderBy('name')
            ->paginate(20);
            
        return view('products.index', compact('products'));
    }
    
    public function create()
    {
        $categories = Category::where('company_id', Auth::user()->company_id)->get();
        return view('products.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:products,code',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'track_stock' => 'nullable|boolean',
            'stock' => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean'
        ]);
        
        $data = $request->all();
        $data['company_id'] = Auth::user()->company_id;
        $data['track_stock'] = $request->has('track_stock');
        $data['is_active'] = $request->has('is_active');
        
        // Set default values
        if (!$data['track_stock']) {
            $data['stock'] = null;
            $data['min_stock'] = null;
        }
        
        Product::create($data);
        
        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente');
    }
    
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
    
    public function edit(Product $product)
    {
        $categories = Category::where('company_id', Auth::user()->company_id)->get();
        return view('products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:products,code,' . $product->id,
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'track_stock' => 'nullable|boolean',
            'stock' => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean'
        ]);
        
        $data = $request->all();
        $data['track_stock'] = $request->has('track_stock');
        $data['is_active'] = $request->has('is_active');
        
        // Set default values
        if (!$data['track_stock']) {
            $data['stock'] = null;
            $data['min_stock'] = null;
        }
        
        $product->update($data);
        
        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente');
    }
    
    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);
        
        return redirect()->route('products.index')->with('success', 'Producto desactivado exitosamente');
    }
}