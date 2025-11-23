<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-eye me-2"></i>{{ __('Detalle de Categoría') }}
            </h2>
            <div class="btn-group">
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                <!-- Información principal -->
                <div class="col-xl-8">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información General
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-tag text-white" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-1">{{ $category->name }}</h3>
                                            <span class="badge {{ $category->is_active ?? true ? 'bg-success' : 'bg-secondary' }} fs-6">
                                                {{ $category->is_active ?? true ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($category->description)
                                        <h6 class="text-muted mb-1">Descripción</h6>
                                        <p class="mb-4">{{ $category->description }}</p>
                                    @endif
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-1">Fecha de Creación</h6>
                                            <p class="mb-3">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-1">Última Modificación</h6>
                                            <p class="mb-3">{{ $category->updated_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Productos en esta categoría -->
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-box-seam me-2"></i>
                                Productos en esta Categoría ({{ $category->products->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($category->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Precio</th>
                                                <th>Stock</th>
                                                <th>Estado</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category->products as $product)
                                                <tr>
                                                    <td>
                                                        <code class="bg-light px-2 py-1 rounded">{{ $product->code }}</code>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $product->name }}</strong>
                                                        @if($product->description)
                                                            <br><small class="text-muted">{{ Str::limit($product->description, 40) }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong class="text-success">₲ {{ number_format($product->sale_price) }}</strong>
                                                    </td>
                                                    <td>
                                                        @if($product->track_stock)
                                                            @if($product->stock <= $product->min_stock)
                                                                <span class="badge bg-danger">{{ $product->stock ?? 0 }}</span>
                                                            @else
                                                                <span class="badge bg-success">{{ $product->stock ?? 0 }}</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">No controlado</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($product->is_active)
                                                            <span class="badge bg-success">Activo</span>
                                                        @else
                                                            <span class="badge bg-secondary">Inactivo</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" title="Ver producto">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Editar producto">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                    <h5 class="text-muted mt-3">No hay productos en esta categoría</h5>
                                    <p class="text-muted">Esta categoría está vacía. Puedes asignar productos editándolos desde el módulo de productos.</p>
                                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-1"></i>Crear Producto
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="col-xl-4">
                    <!-- Estadísticas -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-graph-up me-2"></i>
                                Estadísticas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-12 mb-3">
                                    <h2 class="display-4 text-primary mb-1">{{ $category->products->count() }}</h2>
                                    <p class="text-muted mb-0">Productos Total</p>
                                </div>
                            </div>
                            
                            @if($category->products->count() > 0)
                                <hr>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="text-success mb-1">{{ $category->products->where('is_active', true)->count() }}</h4>
                                        <small class="text-muted">Activos</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-secondary mb-1">{{ $category->products->where('is_active', false)->count() }}</h4>
                                        <small class="text-muted">Inactivos</small>
                                    </div>
                                </div>
                                
                                @if($category->products->where('track_stock', true)->count() > 0)
                                    <hr>
                                    <div class="row text-center">
                                        <div class="col-12">
                                            <h4 class="text-info mb-1">{{ $category->products->where('track_stock', true)->sum('stock') }}</h4>
                                            <small class="text-muted">Unidades en Stock</small>
                                        </div>
                                    </div>
                                    <div class="row text-center mt-2">
                                        <div class="col-6">
                                            <small class="text-success">
                                                {{ $category->products->where('track_stock', true)->filter(function($p) { return $p->stock > $p->min_stock; })->count() }} OK
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-danger">
                                                {{ $category->products->where('track_stock', true)->filter(function($p) { return $p->stock <= $p->min_stock; })->count() }} Bajo
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-lightning me-2"></i>
                                Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-2"></i>Editar Categoría
                                </a>
                                
                                <a href="{{ route('products.create') }}?category={{ $category->id }}" class="btn btn-success">
                                    <i class="bi bi-plus-square me-2"></i>Agregar Producto
                                </a>
                                
                                @if($category->products->count() > 0)
                                    <a href="{{ route('products.index') }}?category={{ $category->id }}" class="btn btn-info">
                                        <i class="bi bi-filter me-2"></i>Ver Productos
                                    </a>
                                @endif
                                
                                @if($category->products->count() == 0)
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100" 
                                                onclick="return confirm('¿Eliminar esta categoría?')">
                                            <i class="bi bi-trash me-2"></i>Eliminar Categoría
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-outline-secondary w-100" disabled title="No se puede eliminar (tiene productos)">
                                        <i class="bi bi-lock me-2"></i>No se puede eliminar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>