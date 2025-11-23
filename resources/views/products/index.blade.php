<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-box-seam me-2"></i>{{ __('Gestión de Productos') }}
            </h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Nuevo Producto
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Filtros y búsqueda -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar productos..." id="searchProducts">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterCategory">
                        <option value="">Todas las categorías</option>
                        <!-- Aquí se cargarían las categorías -->
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterStatus">
                        <option value="">Todos los estados</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de productos -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Lista de Productos ({{ $products->total() }} total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio Venta</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <code class="bg-light px-2 py-1 rounded">{{ $product->code }}</code>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $product->name }}</strong>
                                                    @if($product->description)
                                                        <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($product->category)
                                                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                                @else
                                                    <span class="text-muted">Sin categoría</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="text-success">₲ {{ number_format($product->sale_price) }}</strong>
                                                @if($product->cost_price > 0)
                                                    <br><small class="text-muted">Costo: ₲ {{ number_format($product->cost_price) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->track_stock)
                                                    @if($product->stock <= $product->min_stock)
                                                        <span class="badge bg-danger">{{ $product->stock ?? 0 }}</span>
                                                        <br><small class="text-danger">Stock bajo</small>
                                                    @else
                                                        <span class="badge bg-success">{{ $product->stock ?? 0 }}</span>
                                                    @endif
                                                    <br><small class="text-muted">Mín: {{ $product->min_stock }}</small>
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
                                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    @if($product->is_active)
                                                        <form method="POST" action="{{ route('products.destroy', $product) }}" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Desactivar" 
                                                                onclick="return confirm('¿Desactivar este producto?')">
                                                                <i class="bi bi-pause-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        @if($products->hasPages())
                            <div class="card-footer">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-box-seam" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay productos registrados</h5>
                            <p class="text-muted">Comienza agregando tu primer producto al inventario</p>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Crear Primer Producto
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert">
                <div class="toast-header bg-success text-white">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong class="me-auto">Éxito</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif
</x-app-layout>