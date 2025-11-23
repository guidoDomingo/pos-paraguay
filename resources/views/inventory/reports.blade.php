<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-graph-up me-2"></i>{{ __('Reportes de Inventario') }}
            </h2>
            <div class="btn-group">
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
                <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Estadísticas generales -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Productos</h6>
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
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Valor Total Stock</h6>
                                    <h2 class="mb-0">₲ {{ number_format($stats['total_stock_value']) }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-currency-exchange" style="font-size: 2rem;"></i>
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
                                    <h2 class="mb-0">{{ $stats['low_stock_products'] }}</h2>
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
                                    <h2 class="mb-0">{{ $stats['out_of_stock_products'] }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-x-circle" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Productos más movidos -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-arrow-up-down me-2"></i>
                                Productos más Movidos (Últimos 30 días)
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($topMovedProducts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Producto</th>
                                                <th>Código</th>
                                                <th class="text-center">Movimientos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topMovedProducts as $index => $product)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                                            <strong>{{ $product->name }}</strong>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <code class="bg-light px-2 py-1 rounded">{{ $product->code }}</code>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success fs-6">{{ $product->movements_count }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-clock-history" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">No hay movimientos en los últimos 30 días</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Movimientos recientes -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-clock-history me-2"></i>
                                Movimientos Recientes
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recentMovements->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentMovements->take(10) as $movement)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $movement->product->name }}</h6>
                                                    <p class="mb-1">
                                                        <span class="badge {{ 
                                                            $movement->type == 'in' ? 'bg-success' : 
                                                            ($movement->type == 'out' ? 'bg-danger' : 'bg-warning') 
                                                        }}">
                                                            {{ $movement->type_name }}
                                                        </span>
                                                        <span class="ms-2">{{ $movement->quantity_display }}</span>
                                                    </p>
                                                    <small class="text-muted">{{ $movement->user->name }}</small>
                                                </div>
                                                <small class="text-muted">{{ $movement->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-3 text-center">
                                    <a href="{{ route('inventory.movements') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Ver Todos los Movimientos
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-clock-history" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">No hay movimientos registrados</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Análisis de tendencias -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-bar-chart me-2"></i>
                                Análisis de Inventario
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Estado del Inventario</h6>
                                    @php
                                        $totalProducts = $stats['total_products'];
                                        $lowStockPct = $totalProducts > 0 ? ($stats['low_stock_products'] / $totalProducts) * 100 : 0;
                                        $outOfStockPct = $totalProducts > 0 ? ($stats['out_of_stock_products'] / $totalProducts) * 100 : 0;
                                        $okStockPct = 100 - $lowStockPct - $outOfStockPct;
                                    @endphp
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Stock OK</span>
                                            <strong class="text-success">{{ number_format($okStockPct, 1) }}%</strong>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: {{ $okStockPct }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Stock Bajo</span>
                                            <strong class="text-warning">{{ number_format($lowStockPct, 1) }}%</strong>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $lowStockPct }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-between">
                                            <span>Sin Stock</span>
                                            <strong class="text-danger">{{ number_format($outOfStockPct, 1) }}%</strong>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-danger" style="width: {{ $outOfStockPct }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Recomendaciones</h6>
                                    
                                    @if($stats['out_of_stock_products'] > 0)
                                        <div class="alert alert-danger">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <strong>Crítico:</strong> {{ $stats['out_of_stock_products'] }} productos sin stock. 
                                            <a href="{{ route('inventory.low-stock') }}" class="alert-link">Ver detalle</a>
                                        </div>
                                    @endif
                                    
                                    @if($stats['low_stock_products'] > 0)
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-circle me-2"></i>
                                            <strong>Atención:</strong> {{ $stats['low_stock_products'] }} productos con stock bajo. 
                                            <a href="{{ route('inventory.low-stock') }}" class="alert-link">Revisar</a>
                                        </div>
                                    @endif
                                    
                                    @if($stats['out_of_stock_products'] == 0 && $stats['low_stock_products'] == 0)
                                        <div class="alert alert-success">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <strong>Excelente:</strong> El inventario está en buen estado.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de generación del reporte -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="text-center text-muted">
                        <small>
                            Reporte generado el {{ now()->format('d/m/Y H:i:s') }} por {{ auth()->user()->name }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>