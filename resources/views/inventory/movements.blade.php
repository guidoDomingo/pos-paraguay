<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-clock-history me-2"></i>{{ __('Historial de Movimientos') }}
            </h2>
            <div class="btn-group">
                <a href="{{ route('inventory.adjust') }}" class="btn btn-primary">
                    <i class="bi bi-sliders me-1"></i>Nuevo Ajuste
                </a>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Filtros -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Producto</label>
                    <select class="form-select" id="productFilter">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" id="typeFilter">
                        <option value="">Todos los tipos</option>
                        <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Entrada</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Salida</option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Ajuste</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Venta</option>
                        <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Compra</option>
                        <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Devolución</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" id="dateFromFilter" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" id="dateToFilter" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-secondary" onclick="applyFilters()">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de movimientos -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Movimientos de Inventario ({{ $movements->total() }} registros)
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($movements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        <th>Tipo</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Stock Anterior</th>
                                        <th class="text-center">Stock Nuevo</th>
                                        <th>Usuario</th>
                                        <th>Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movements as $movement)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $movement->created_at->format('d/m/Y') }}</strong>
                                                    <br><small class="text-muted">{{ $movement->created_at->format('H:i:s') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $movement->product->name }}</strong>
                                                    <br><code class="bg-light px-1 rounded small">{{ $movement->product->code }}</code>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $typeClasses = [
                                                        'in' => 'bg-success',
                                                        'out' => 'bg-danger', 
                                                        'adjustment' => 'bg-warning',
                                                        'sale' => 'bg-info',
                                                        'purchase' => 'bg-primary',
                                                        'return' => 'bg-secondary'
                                                    ];
                                                @endphp
                                                <span class="badge {{ $typeClasses[$movement->type] ?? 'bg-secondary' }}">
                                                    {{ $movement->type_name }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $movement->quantity >= 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                                    {{ $movement->quantity_display }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted">{{ $movement->previous_stock }}</span>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-primary">{{ $movement->new_stock }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $movement->user->name }}</strong>
                                                    <br><small class="text-muted">{{ $movement->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($movement->reason)
                                                    <span class="text-muted">{{ Str::limit($movement->reason, 50) }}</span>
                                                    @if(strlen($movement->reason) > 50)
                                                        <button class="btn btn-sm btn-link p-0" data-bs-toggle="tooltip" title="{{ $movement->reason }}">
                                                            <i class="bi bi-info-circle"></i>
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="text-muted fst-italic">Sin motivo especificado</span>
                                                @endif
                                                
                                                @if($movement->unit_cost)
                                                    <br><small class="text-success">Costo: ₲ {{ number_format($movement->unit_cost, 2) }}</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($movements->hasPages())
                            <div class="card-footer">
                                {{ $movements->withQueryString()->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay movimientos registrados</h5>
                            <p class="text-muted">Los movimientos de inventario aparecerán aquí cuando se realicen ajustes</p>
                            <a href="{{ route('inventory.adjust') }}" class="btn btn-primary">
                                <i class="bi bi-plus-minus me-1"></i>Realizar Primer Ajuste
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Resumen estadístico -->
            @if($movements->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Movimientos</h6>
                                <h3 class="mb-0">{{ $movements->total() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Entradas</h6>
                                <h3 class="mb-0">{{ $movements->where('type', 'in')->count() + $movements->where('type', 'purchase')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Salidas</h6>
                                <h3 class="mb-0">{{ $movements->where('type', 'out')->count() + $movements->where('type', 'sale')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Ajustes</h6>
                                <h3 class="mb-0">{{ $movements->where('type', 'adjustment')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function applyFilters() {
            const product = document.getElementById('productFilter').value;
            const type = document.getElementById('typeFilter').value;
            const dateFrom = document.getElementById('dateFromFilter').value;
            const dateTo = document.getElementById('dateToFilter').value;
            
            const url = new URL(window.location.href);
            url.searchParams.set('product_id', product);
            url.searchParams.set('type', type);
            url.searchParams.set('date_from', dateFrom);
            url.searchParams.set('date_to', dateTo);
            
            window.location.href = url.toString();
        }
        
        function clearFilters() {
            window.location.href = '{{ route("inventory.movements") }}';
        }
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</x-app-layout>