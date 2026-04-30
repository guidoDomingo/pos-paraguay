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
            <form method="GET" action="{{ route('products.index') }}" class="row mb-4 g-2">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search"
                               placeholder="Buscar por nombre, código o barcode..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">Todos los estados</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" id="btn-limpiar"
                       title="Limpiar filtros"
                       style="{{ request()->hasAny(['search','category','status']) ? '' : 'display:none' }}">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>

            <!-- Tabla de productos -->
            <div class="card shadow" id="products-results">
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
                                        <th width="80">Imagen</th>
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
                                            <td class="text-center">
                                                @if($product->hasImage())
                                                    <img src="{{ $product->getImageUrl('thumbnail') }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="product-thumbnail cursor-pointer" 
                                                         data-full-image="{{ $product->getImageUrl('medium') }}"
                                                         data-product-name="{{ $product->name }}"
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 2px solid #e3e6f0; transition: all 0.3s ease;"
                                                         title="Click para ampliar">
                                                @else
                                                    <div class="product-thumbnail-placeholder d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px;">
                                                        <i class="bi bi-image text-muted" style="font-size: 20px;"></i>
                                                    </div>
                                                @endif
                                            </td>
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

    <!-- Modal para ampliar imagen -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">
                        <i class="bi bi-image me-2"></i>
                        <span id="imageModalProductName">Imagen del Producto</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img id="modalProductImage" src="" alt="" class="img-fluid rounded shadow" style="max-height: 500px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .product-thumbnail {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .product-thumbnail:hover {
            transform: scale(1.1);
            border-color: var(--bs-primary) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10;
            position: relative;
        }
        
        .product-thumbnail-placeholder:hover {
            border-color: var(--bs-primary);
            background: #f0f0f0;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        /* Animación del modal */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }
        
        .modal.show .modal-dialog {
            transform: none;
        }
        
        /* Responsive para móvil */
        @media (max-width: 768px) {
            .product-thumbnail {
                width: 40px !important;
                height: 40px !important;
            }
            
            .product-thumbnail-placeholder {
                width: 40px !important;
                height: 40px !important;
            }
            
            .product-thumbnail-placeholder i {
                font-size: 16px !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar clicks en las miniaturas de productos
            const productThumbnails = document.querySelectorAll('.product-thumbnail');
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            const modalImage = document.getElementById('modalProductImage');
            const modalTitle = document.getElementById('imageModalProductName');
            
            productThumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const fullImageUrl = this.getAttribute('data-full-image');
                    const productName = this.getAttribute('data-product-name');
                    
                    if (fullImageUrl) {
                        // Configurar el modal
                        modalImage.src = fullImageUrl;
                        modalImage.alt = productName;
                        modalTitle.textContent = productName;
                        
                        // Mostrar el modal
                        imageModal.show();
                    }
                });
                
                // Agregar efecto de tooltip
                thumbnail.setAttribute('title', 'Click para ampliar imagen');
            });
            
            // Precargar imágenes al pasar el mouse (opcional, mejora UX)
            productThumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('mouseenter', function() {
                    const fullImageUrl = this.getAttribute('data-full-image');
                    if (fullImageUrl) {
                        const preloadImage = new Image();
                        preloadImage.src = fullImageUrl;
                    }
                });
            });
            
            // Cerrar modal con tecla Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    imageModal.hide();
                }
            });

            // Búsqueda automática al escribir (desde 2 caracteres)
            const searchInput = document.querySelector('input[name="search"]');
            const filterForm  = searchInput ? searchInput.closest('form') : null;
            let searchTimer;

            if (searchInput && filterForm) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    const val = this.value.trim();
                    if (val.length === 0 || val.length >= 2) {
                        searchTimer = setTimeout(() => buscarProductos(), 350);
                    }
                });

                // Los selects también actualizan sin recargar
                filterForm.querySelectorAll('select').forEach(sel => {
                    sel.addEventListener('change', () => buscarProductos());
                });
            }

            function buscarProductos() {
                const params = new URLSearchParams(new FormData(filterForm));
                const url    = filterForm.action + '?' + params.toString();

                history.pushState({}, '', url);

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const doc     = new DOMParser().parseFromString(html, 'text/html');
                        const newCard = doc.getElementById('products-results');
                        if (newCard) {
                            document.getElementById('products-results').innerHTML = newCard.innerHTML;
                        }
                        // Mostrar/ocultar botón limpiar sin recargar
                        actualizarBotonLimpiar(params);
                    });
            }

            function actualizarBotonLimpiar(params) {
                const tienesFiltros = params.get('search') || params.get('category') || params.get('status');
                const btnLimpiar    = document.getElementById('btn-limpiar');
                if (btnLimpiar) btnLimpiar.style.display = tienesFiltros ? '' : 'none';
            }
        });

    </script>
</x-app-layout>