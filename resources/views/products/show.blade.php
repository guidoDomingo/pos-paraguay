<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-eye me-2"></i>{{ __('Detalle del Producto') }}
            </h2>
            <div class="btn-group">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
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
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Nombre</h6>
                                    <h4 class="mb-3">{{ $product->name }}</h4>
                                    
                                    <h6 class="text-muted mb-1">Código</h6>
                                    <p class="mb-3">
                                        <code class="bg-light px-2 py-1 rounded fs-6">{{ $product->code }}</code>
                                    </p>
                                    
                                    @if($product->barcode)
                                        <h6 class="text-muted mb-1">Código de Barras</h6>
                                        <p class="mb-3">
                                            <code class="bg-light px-2 py-1 rounded">{{ $product->barcode }}</code>
                                        </p>
                                    @endif
                                    
                                    @if($product->description)
                                        <h6 class="text-muted mb-1">Descripción</h6>
                                        <p class="mb-3">{{ $product->description }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Categoría</h6>
                                    <p class="mb-3">
                                        @if($product->category)
                                            <span class="badge bg-secondary fs-6">{{ $product->category->name }}</span>
                                        @else
                                            <span class="text-muted">Sin categoría asignada</span>
                                        @endif
                                    </p>
                                    
                                    <h6 class="text-muted mb-1">Estado</h6>
                                    <p class="mb-3">
                                        @if($product->is_active)
                                            <span class="badge bg-success fs-6">Activo</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">Inactivo</span>
                                        @endif
                                    </p>
                                    
                                    <h6 class="text-muted mb-1">Unidad de Medida</h6>
                                    <p class="mb-3">{{ ucfirst($product->unit ?? 'unidad') }}</p>
                                    
                                    <h6 class="text-muted mb-1">Fecha de Creación</h6>
                                    <p class="mb-0">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Precios -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-currency-exchange me-2"></i>
                                Información de Precios
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h6 class="text-muted mb-1">Precio de Costo</h6>
                                        <h4 class="text-info mb-0">₲ {{ number_format($product->cost_price ?? 0) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h6 class="text-muted mb-1">Precio de Venta</h6>
                                        <h4 class="text-success mb-0">₲ {{ number_format($product->sale_price) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-1">IVA</h6>
                                    <h4 class="text-warning mb-0">{{ $product->tax_rate ?? 10 }}%</h4>
                                </div>
                            </div>
                            
                            @if($product->cost_price > 0)
                                <hr>
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-1">Margen de Ganancia</h6>
                                        <h5 class="text-primary mb-0">
                                            ₲ {{ number_format($product->sale_price - $product->cost_price) }}
                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-1">% Margen</h6>
                                        <h5 class="text-primary mb-0">
                                            {{ number_format((($product->sale_price - $product->cost_price) / $product->cost_price) * 100, 1) }}%
                                        </h5>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="col-xl-4">
                    <!-- Stock -->
                    @if($product->track_stock)
                        <div class="card shadow mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-boxes me-2"></i>
                                    Control de Inventario
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 class="display-4 {{ $product->stock <= $product->min_stock ? 'text-danger' : 'text-success' }} mb-1">
                                    {{ $product->stock ?? 0 }}
                                </h2>
                                <p class="text-muted mb-3">{{ ucfirst($product->unit ?? 'unidades') }} disponibles</p>
                                
                                @if($product->min_stock > 0)
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span>Stock Mínimo:</span>
                                        <strong>{{ $product->min_stock }}</strong>
                                    </div>
                                    
                                    @if($product->stock <= $product->min_stock)
                                        <div class="alert alert-warning mt-3 mb-0">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            <strong>Stock bajo!</strong> Este producto necesita reposición.
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="card shadow mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-infinity me-2"></i>
                                    Sin Control de Stock
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <i class="bi bi-infinity" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2 mb-0">
                                    Este producto no tiene control de inventario activado.
                                </p>
                            </div>
                        </div>
                    @endif

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
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-2"></i>Editar Producto
                                </a>
                                
                                @if($product->track_stock)
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#stockModal">
                                        <i class="bi bi-plus-square me-2"></i>Ajustar Stock
                                    </button>
                                @endif
                                
                                @if($product->is_active)
                                    <form method="POST" action="{{ route('products.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning w-100" 
                                                onclick="return confirm('¿Desactivar este producto?')">
                                            <i class="bi bi-pause-circle me-2"></i>Desactivar
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-success" disabled>
                                        <i class="bi bi-check-circle me-2"></i>Producto Inactivo
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($product->track_stock)
        <!-- Modal para ajustar stock -->
        <div class="modal fade" id="stockModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajustar Stock - {{ $product->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('inventory.adjust.store') }}" id="stockForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="adjustment_type" class="form-label">Tipo de Ajuste</label>
                                <select class="form-select" id="adjustment_type" name="adjustment_type">
                                    <option value="add">Agregar Stock</option>
                                    <option value="subtract">Reducir Stock</option>
                                    <option value="set">Establecer Stock</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="0" step="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="reason" class="form-label">Motivo</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Motivo del ajuste de stock" required minlength="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Aplicar Ajuste</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>