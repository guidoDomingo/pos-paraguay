<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-boxes me-2"></i>{{ __('Gestión de Inventario') }}
            </h2>
            <div class="btn-group">
                <a href="{{ route('inventory.adjust') }}" class="btn btn-primary">
                    <i class="bi bi-sliders me-1"></i>Ajustar Stock
                </a>
                <a href="{{ route('inventory.movements') }}" class="btn btn-outline-info">
                    <i class="bi bi-clock-history me-1"></i>Movimientos
                </a>
                <a href="{{ route('inventory.reports') }}" class="btn btn-outline-success">
                    <i class="bi bi-graph-up me-1"></i>Reportes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Productos con Stock</h6>
                                    <h2 class="mb-0">{{ $stats['total_products'] }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Stock Bajo</h6>
                                    <h2 class="mb-0">{{ $stats['low_stock'] }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Sin Stock</h6>
                                    <h2 class="mb-0">{{ $stats['out_of_stock'] }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-x-circle" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Valor Total</h6>
                                    <h2 class="mb-0">₲ {{ number_format($stats['total_value']) }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-currency-exchange" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <form method="GET" action="{{ route('inventory.index') }}" class="row mb-4 g-2" id="inventory-filter-form">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search"
                               placeholder="Buscar por nombre o código..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">Todos los estados</option>
                        <option value="in_stock"     {{ request('status') === 'in_stock'     ? 'selected' : '' }}>Con stock</option>
                        <option value="low_stock"    {{ request('status') === 'low_stock'    ? 'selected' : '' }}>Stock bajo</option>
                        <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Sin stock</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary" id="btn-limpiar-inv"
                       title="Limpiar filtros"
                       style="{{ request()->hasAny(['search','category','status']) ? '' : 'display:none' }}">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>

            <!-- Tabla de productos -->
            <div class="card shadow" id="inventory-results">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Inventario de Productos
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th class="text-center">Stock Actual</th>
                                        <th class="text-center">Stock Mín.</th>
                                        <th>Valor Stock</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $product->name }}</strong>
                                                    <br><code class="bg-light px-2 py-1 rounded small">{{ $product->code }}</code>
                                                </div>
                                            </td>
                                            <td>
                                                @if($product->category)
                                                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                                @else
                                                    <span class="text-muted">Sin categoría</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $stock = $product->stock ?? 0;
                                                    $minStock = $product->min_stock ?? 0;
                                                    $isLowStock = $stock <= $minStock && $minStock > 0;
                                                    $isOutOfStock = $stock <= 0;
                                                @endphp
                                                
                                                <span class="badge {{ $isOutOfStock ? 'bg-danger' : ($isLowStock ? 'bg-warning' : 'bg-success') }} fs-6">
                                                    {{ $stock }} {{ $product->unit ?? 'unidades' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted">{{ $minStock }} {{ $product->unit ?? 'unidades' }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-success">₲ {{ number_format(($product->stock ?? 0) * ($product->cost_price ?? 0)) }}</strong>
                                            </td>
                                            <td>
                                                @if($isOutOfStock)
                                                    <span class="badge bg-danger">Sin stock</span>
                                                @elseif($isLowStock)
                                                    <span class="badge bg-warning">Stock bajo</span>
                                                @else
                                                    <span class="badge bg-success">OK</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" title="Ver producto">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('inventory.adjust') }}?product={{ $product->id }}" class="btn btn-sm btn-outline-primary" title="Ajustar stock">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <a href="{{ route('inventory.movements') }}?product_id={{ $product->id }}" class="btn btn-sm btn-outline-secondary" title="Ver movimientos">
                                                        <i class="bi bi-clock-history"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($products->hasPages())
                            <div class="card-footer">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-boxes" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay productos con control de stock</h5>
                            <p class="text-muted">Los productos deben tener activado el control de stock para aparecer aquí</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="bi bi-box-seam me-1"></i>Ver Productos
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm  = document.getElementById('inventory-filter-form');
            const searchInput = filterForm.querySelector('input[name="search"]');
            let searchTimer;

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                const val = this.value.trim();
                if (val.length === 0 || val.length >= 2) {
                    searchTimer = setTimeout(() => buscarInventario(), 350);
                }
            });

            filterForm.querySelectorAll('select').forEach(sel => {
                sel.addEventListener('change', () => buscarInventario());
            });

            function buscarInventario() {
                const params = new URLSearchParams(new FormData(filterForm));
                const url    = filterForm.action + '?' + params.toString();

                history.pushState({}, '', url);

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const doc     = new DOMParser().parseFromString(html, 'text/html');
                        const newCard = doc.getElementById('inventory-results');
                        if (newCard) {
                            document.getElementById('inventory-results').innerHTML = newCard.innerHTML;
                        }
                        const tienesFiltros = params.get('search') || params.get('category') || params.get('status');
                        const btnLimpiar    = document.getElementById('btn-limpiar-inv');
                        if (btnLimpiar) btnLimpiar.style.display = tienesFiltros ? '' : 'none';
                    });
            }
        });
    </script>
</x-app-layout>