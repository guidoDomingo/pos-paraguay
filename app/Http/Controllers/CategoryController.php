<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('company_id', Auth::user()->company_id)
            ->withCount('products')
            ->orderBy('name')
            ->paginate(20);
            
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
    
    public function show(Category $category)
    {
        $category->load('products');
        return view('categories.show', compact('category'));
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