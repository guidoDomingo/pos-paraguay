<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ __('Productos con Stock Bajo') }}
            </h2>
            <div class="btn-group">
                <a href="{{ route('inventory.adjust') }}" class="btn btn-primary">
                    <i class="bi bi-sliders me-1"></i>Ajustar Stock
                </a>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Alerta informativa -->
            @if($products->count() > 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Atención:</strong> Hay {{ $products->total() }} producto(s) con stock bajo o sin stock. 
                    Es recomendable realizar reposición pronto.
                </div>
            @endif

            <!-- Lista de productos con stock bajo -->
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Productos que Requieren Reposición
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
                                        <th class="text-center">Stock Mínimo</th>
                                        <th class="text-center">Diferencia</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        @php
                                            $stock = $product->stock ?? 0;
                                            $minStock = $product->min_stock ?? 0;
                                            $difference = $minStock - $stock;
                                            $isOutOfStock = $stock <= 0;
                                        @endphp
                                        <tr class="{{ $isOutOfStock ? 'table-danger' : 'table-warning' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        @if($isOutOfStock)
                                                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 1.5rem;"></i>
                                                        @else
                                                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 1.5rem;"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <strong>{{ $product->name }}</strong>
                                                        <br><code class="bg-light px-2 py-1 rounded small">{{ $product->code }}</code>
                                                        @if($product->description)
                                                            <br><small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                                                        @endif
                                                    </div>
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
                                                <span class="badge {{ $isOutOfStock ? 'bg-danger' : 'bg-warning' }} fs-6">
                                                    {{ $stock }} {{ $product->unit ?? 'unidades' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted">{{ $minStock }} {{ $product->unit ?? 'unidades' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">
                                                    -{{ $difference }} {{ $product->unit ?? 'unidades' }}
                                                </span>
                                                @if($difference > 0)
                                                    <br><small class="text-muted">Necesita {{ $difference }} más</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($isOutOfStock)
                                                    <span class="badge bg-danger fs-6">SIN STOCK</span>
                                                    <br><small class="text-danger fw-bold">URGENTE</small>
                                                @else
                                                    <span class="badge bg-warning fs-6">STOCK BAJO</span>
                                                    <br><small class="text-warning">REPONER</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('inventory.adjust') }}?product={{ $product->id }}" 
                                                       class="btn btn-sm btn-primary" title="Ajustar stock">
                                                        <i class="bi bi-sliders"></i> Ajustar
                                                    </a>
                                                    <a href="{{ route('products.edit', $product) }}" 
                                                       class="btn btn-sm btn-outline-secondary" title="Editar producto">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('inventory.movements') }}?product_id={{ $product->id }}" 
                                                       class="btn btn-sm btn-outline-info" title="Ver movimientos">
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
                            <i class="bi bi-check-circle" style="font-size: 4rem; color: #28a745;"></i>
                            <h5 class="text-success mt-3">¡Excelente! No hay productos con stock bajo</h5>
                            <p class="text-muted">Todos los productos tienen stock suficiente según sus límites mínimos</p>
                            <a href="{{ route('inventory.index') }}" class="btn btn-success">
                                <i class="bi bi-boxes me-1"></i>Ver Inventario Completo
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($products->count() > 0)
                <!-- Resumen y acciones recomendadas -->
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-lightbulb me-2"></i>Acciones Recomendadas
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="bi bi-1-circle text-primary me-2"></i>
                                        <strong>Revisar proveedores:</strong> Contactar proveedores para productos sin stock
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-2-circle text-primary me-2"></i>
                                        <strong>Ajustar stocks mínimos:</strong> Considerar si los límites son apropiados
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-3-circle text-primary me-2"></i>
                                        <strong>Planificar compras:</strong> Preparar órdenes de compra según demanda
                                    </li>
                                    <li class="mb-0">
                                        <i class="bi bi-4-circle text-primary me-2"></i>
                                        <strong>Monitorear ventas:</strong> Ajustar según patrones de consumo
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-graph-up me-2"></i>Estadísticas
                                </h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $outOfStock = $products->where('stock', '<=', 0)->count();
                                    $lowStock = $products->count() - $outOfStock;
                                @endphp
                                <div class="row text-center">
                                    <div class="col-12 mb-3">
                                        <h3 class="text-danger">{{ $outOfStock }}</h3>
                                        <small class="text-muted">Sin stock</small>
                                    </div>
                                    <div class="col-12">
                                        <h3 class="text-warning">{{ $lowStock }}</h3>
                                        <small class="text-muted">Stock bajo</small>
                                    </div>
                                </div>
                                
                                <hr class="my-3">
                                
                                <div class="d-grid">
                                    <a href="{{ route('inventory.adjust') }}" class="btn btn-primary">
                                        <i class="bi bi-sliders me-1"></i>Ajustar Todos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>