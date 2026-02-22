<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $imageService;
    
    public function __construct(ProductImageService $imageService)
    {
        $this->imageService = $imageService;
    }
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
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB máximo
            'check_price' => 'nullable|numeric|min:0',
            'check_price_description' => 'nullable|string|max:255',
            'credit_price' => 'nullable|numeric|min:0',
            'credit_price_description' => 'nullable|string|max:255',
            'special_price' => 'nullable|numeric|min:0',
            'special_price_description' => 'nullable|string|max:255',
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
        
        // Procesar imagen si se subió una
        if ($request->hasFile('image')) {
            try {
                $imagePaths = $this->imageService->processAndStore($request->file('image'));
                $data['image_path'] = $imagePaths['main'];
            } catch (\Exception $e) {
                Log::error('Error procesando imagen del producto: ' . $e->getMessage());
                return back()->withErrors(['image' => 'Error al procesar la imagen: ' . $e->getMessage()])->withInput();
            }
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
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB máximo
            'remove_image' => 'nullable|boolean',
            'check_price' => 'nullable|numeric|min:0',
            'check_price_description' => 'nullable|string|max:255',
            'credit_price' => 'nullable|numeric|min:0',
            'credit_price_description' => 'nullable|string|max:255',
            'special_price' => 'nullable|numeric|min:0',
            'special_price_description' => 'nullable|string|max:255',
        ]);
        
        $data = $request->all();
        $data['track_stock'] = $request->has('track_stock');
        $data['is_active'] = $request->has('is_active');
        
        // Set default values
        if (!$data['track_stock']) {
            $data['stock'] = null;
            $data['min_stock'] = null;
        }
        
        // Manejar imagen
        if ($request->boolean('remove_image')) {
            // Eliminar imagen actual
            Log::info('Eliminando imagen del producto: ' . $product->id);
            if ($product->image_path) {
                try {
                    $this->imageService->deleteImages($product->image_path);
                    Log::info('Imagen eliminada exitosamente');
                } catch (\Exception $e) {
                    Log::error('Error eliminando imagen: ' . $e->getMessage());
                }
            }
            $data['image_path'] = null;
        } elseif ($request->hasFile('image')) {
            // Procesar nueva imagen
            Log::info('Procesando nueva imagen para producto: ' . $product->id);
            try {
                $imagePaths = $this->imageService->processAndStore(
                    $request->file('image'), 
                    $product->image_path
                );
                $data['image_path'] = $imagePaths['main'];
                Log::info('Nueva imagen procesada exitosamente. Path: ' . $data['image_path']);
            } catch (\Exception $e) {
                Log::error('Error procesando imagen del producto: ' . $e->getMessage());
                return back()->withErrors(['image' => 'Error al procesar la imagen: ' . $e->getMessage()])->withInput();
            }
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