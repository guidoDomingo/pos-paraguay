<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Bootstrap 5 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
            
            <style>
                .dashboard-card {
                    transition: all 0.3s ease;
                    border-radius: 15px;
                    border: none;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                }
                
                .dashboard-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                }
                
                .stats-card {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border-radius: 12px;
                    padding: 2rem;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                    transition: transform 0.3s ease;
                    text-align: center;
                }

                .stats-card:hover {
                    transform: translateY(-5px);
                }

                .stats-card.total-sales {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }

                .stats-card.sales-count {
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                }

                .stats-card.avg-sale {
                    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                }

                .stats-card.items-sold {
                    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
                    color: #2d3748;
                }

                .icon-wrapper {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    color: white;
                    margin: 0 auto 1rem auto;
                    background: rgba(255, 255, 255, 0.2);
                }

                .page-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 2rem 0;
                    margin-bottom: 2rem;
                    border-radius: 0 0 20px 20px;
                }

                .page-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                    margin: 0;
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }

                .filter-card {
                    background: white;
                    border-radius: 15px;
                    padding: 2rem;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                    margin-bottom: 2rem;
                }

                .section-title {
                    font-size: 1.5rem;
                    font-weight: 700;
                    color: #2d3748;
                    margin-bottom: 1.5rem;
                    border-bottom: 3px solid #667eea;
                    padding-bottom: 0.5rem;
                }

                .data-table {
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                }

                .data-table thead {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }

                .data-table th {
                    color: white;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-size: 0.875rem;
                }

                .data-table tbody tr:hover {
                    background-color: #f8fafc;
                }
            </style>

            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="container">
                    <h1 class="page-title">
                        <i class="bi bi-bar-chart-line"></i>
                        Reportes de Ventas
                    </h1>
                    <p class="mb-0 opacity-75">Sistema POS Paraguay - Análisis de ventas</p>
                </div>
            </div>
            <!-- Filtros de fecha -->
            <div class="filter-card">
                <h3 class="section-title">
                    <i class="bi bi-funnel me-2"></i>
                    Filtros de Búsqueda
                </h3>
                <form method="GET" action="{{ route('sales.reports') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label fw-bold">Fecha Inicio</label>
                            <input type="date" id="start_date" name="start_date" 
                                   value="{{ $startDate }}"
                                   class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label fw-bold">Fecha Fin</label>
                            <input type="date" id="end_date" name="end_date" 
                                   value="{{ $endDate }}"
                                   class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="user_id" class="form-label fw-bold">Vendedor</label>
                            <select id="user_id" name="user_id" class="form-select">
                                <option value="">Todos los vendedores</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Generar Reporte
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Estadísticas Resumidas -->
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card total-sales">
                        <div class="icon-wrapper">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="font-size: 2rem;">₲ {{ number_format($totalSales, 0, ',', '.') }}</h4>
                        <p class="mb-0 opacity-90">Ventas Total</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card sales-count">
                        <div class="icon-wrapper">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="font-size: 2rem;">{{ number_format($salesCount) }}</h4>
                        <p class="mb-0 opacity-90">Cantidad de Ventas</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="stats-card avg-sale">
                        <div class="icon-wrapper">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="font-size: 2rem;">₲ {{ number_format($averageSale, 0, ',', '.') }}</h4>
                        <p class="mb-0 opacity-90">Venta Promedio</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="stats-card items-sold">
                        <div class="icon-wrapper">
                            <i class="bi bi-box"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="font-size: 2rem;">{{ number_format($itemsSold ?? 0) }}</h4>
                        <p class="mb-0 opacity-90">Productos Vendidos</p>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Ventas por Día -->
            <div class="dashboard-card mb-4">
                <div class="card-body">
                    <h3 class="section-title">
                        <i class="bi bi-graph-up me-2"></i>
                        Ventas por Día
                    </h3>
                    <div class="bg-light rounded p-5 text-center" style="height: 250px; display: flex; align-items: center; justify-content: center;">
                        <div>
                            <i class="bi bi-bar-chart text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">Gráfico de ventas (pendiente de implementar)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos Más Vendidos y Ventas por Vendedor -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="dashboard-card h-100">
                        <div class="card-body">
                            <h3 class="section-title">
                                <i class="bi bi-trophy me-2"></i>
                                Productos Más Vendidos
                            </h3>
                            @forelse($topProducts as $product)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <p class="fw-bold mb-1 text-dark">{{ $product->product_name }}</p>
                                    <small class="text-muted">{{ number_format($product->total_quantity) }} unidades</small>
                                </div>
                                <div class="text-end">
                                    <p class="fw-bold mb-0 text-success">₲ {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3 mb-0">No hay datos disponibles</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="dashboard-card h-100">
                        <div class="card-body">
                            <h3 class="section-title">
                                <i class="bi bi-people me-2"></i>
                                Ventas por Vendedor
                            </h3>
                            @forelse($salesByUser as $userSales)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <p class="fw-bold mb-1 text-dark">{{ $userSales->user_name }}</p>
                                    <small class="text-muted">{{ $userSales->sales_count }} ventas</small>
                                </div>
                                <div class="text-end">
                                    <p class="fw-bold mb-0 text-success">₲ {{ number_format($userSales->total_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-person-x" style="font-size: 3rem;"></i>
                                <p class="mt-3 mb-0">No hay datos disponibles</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle de Ventas -->
            <div class="dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="section-title mb-0">
                            <i class="bi bi-table me-2"></i>
                            Detalle de Ventas
                        </h3>
                        <button class="btn btn-success">
                            <i class="bi bi-file-earmark-excel me-2"></i>Exportar Excel
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table data-table mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesDetail as $sale)
                                <tr>
                                    <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                    <td class="fw-bold">{{ $sale->invoice_number ?: '-' }}</td>
                                    <td>{{ $sale->customer_name ?: 'Cliente general' }}</td>
                                    <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                    <td class="fw-bold text-success">₲ {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($sale->status === 'completed')
                                            <span class="badge bg-success">Completada</span>
                                        @elseif($sale->status === 'pending')
                                            <span class="badge bg-warning">Pendiente</span>
                                        @else
                                            <span class="badge bg-danger">Cancelada</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                                        <p class="mt-3 mb-0">No hay ventas para mostrar en el período seleccionado</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bootstrap 5 JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </div>
    </div>
</x-app-layout>