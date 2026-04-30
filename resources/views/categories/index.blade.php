<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-tags me-2"></i>{{ __('Gestión de Categorías') }}
            </h2>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Nueva Categoría
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Filtros y búsqueda -->
            <form method="GET" action="{{ route('categories.index') }}" class="row mb-4 g-2" id="categories-filter-form">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search"
                               placeholder="Buscar categorías..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">Todas las categorías</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Activas</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivas</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary" id="btn-limpiar-cats"
                       title="Limpiar filtros"
                       style="{{ request()->hasAny(['search','status']) ? '' : 'display:none' }}">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>

            <!-- Tabla de categorías -->
            <div class="card shadow" id="categories-results">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Lista de Categorías ({{ $categories->total() }} total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Productos</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="bi bi-tag text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $category->name }}</strong>
                                                        <br><small class="text-muted">
                                                            Creada: {{ $category->created_at->format('d/m/Y') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($category->description)
                                                    {{ Str::limit($category->description, 100) }}
                                                @else
                                                    <span class="text-muted">Sin descripción</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($category->products_count > 0)
                                                    <span class="badge bg-info fs-6">{{ $category->products_count }}</span>
                                                    <br><small class="text-muted">productos</small>
                                                @else
                                                    <span class="text-muted">0 productos</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($category->is_active ?? true)
                                                    <span class="badge bg-success">Activa</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactiva</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-info" title="Ver">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    @if($category->products_count == 0)
                                                        <form method="POST" action="{{ route('categories.destroy', $category) }}" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" 
                                                                onclick="return confirm('¿Eliminar esta categoría?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-secondary" title="No se puede eliminar (tiene productos)" disabled>
                                                            <i class="bi bi-lock"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        @if($categories->hasPages())
                            <div class="card-footer">
                                {{ $categories->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-tags" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay categorías registradas</h5>
                            <p class="text-muted">Comienza creando tu primera categoría para organizar los productos</p>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Crear Primera Categoría
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            @if($categories->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Categorías</h6>
                                        <h2 class="mb-0">{{ $categories->total() }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-tags" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Con Productos</h6>
                                        <h2 class="mb-0">{{ $categories->where('products_count', '>', 0)->count() }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Vacías</h6>
                                        <h2 class="mb-0">{{ $categories->where('products_count', 0)->count() }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Productos Total</h6>
                                        <h2 class="mb-0">{{ $categories->sum('products_count') }}</h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-archive" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm  = document.getElementById('categories-filter-form');
            const searchInput = filterForm.querySelector('input[name="search"]');
            let searchTimer;

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                const val = this.value.trim();
                if (val.length === 0 || val.length >= 2) {
                    searchTimer = setTimeout(() => buscarCategorias(), 350);
                }
            });

            filterForm.querySelectorAll('select').forEach(sel => {
                sel.addEventListener('change', () => buscarCategorias());
            });

            function buscarCategorias() {
                const params = new URLSearchParams(new FormData(filterForm));
                const url    = filterForm.action + '?' + params.toString();
                history.pushState({}, '', url);
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const newCard = doc.getElementById('categories-results');
                        if (newCard) document.getElementById('categories-results').innerHTML = newCard.innerHTML;
                        const tienesFiltros = params.get('search') || params.get('status');
                        const btn = document.getElementById('btn-limpiar-cats');
                        if (btn) btn.style.display = tienesFiltros ? '' : 'none';
                    });
            }
        });
    </script>
    @endpush
</x-app-layout>