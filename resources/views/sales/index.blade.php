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

                .btn-action {
                    border-radius: 8px;
                    padding: 0.5rem 1rem;
                    font-weight: 600;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    transition: all 0.3s ease;
                }

                .btn-primary-custom {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border: none;
                }

                .btn-success-custom {
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white;
                    border: none;
                }
            </style>

            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">
                                <i class="bi bi-receipt-cutoff"></i>
                                Ventas
                            </h1>
                            <p class="mb-0 opacity-75">Sistema POS Paraguay - Gestión de ventas</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('pos.index') }}" class="btn btn-primary-custom btn-action">
                                <i class="bi bi-plus-lg"></i>Nueva Venta
                            </a>
                            <a href="{{ route('sales.reports') }}" class="btn btn-success-custom btn-action">
                                <i class="bi bi-graph-up"></i>Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filtros -->
            <div class="filter-card">
                <h3 class="section-title">
                    <i class="bi bi-funnel me-2"></i>
                    Filtros de Búsqueda
                </h3>
                <form method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label fw-bold">Buscar</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                   placeholder="Nº factura, cliente..."
                                   class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label fw-bold">Desde</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                                   class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label fw-bold">Hasta</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                                   class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Lista de Ventas -->
            <div class="dashboard-card">
                <div class="card-body">
                    <h3 class="section-title">
                        <i class="bi bi-list-ul me-2"></i>
                        Lista de Ventas
                    </h3>
                    
                    <div class="table-responsive">
                        <table class="table data-table mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Cliente</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                <tr>
                                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold">
                                        @if($sale->invoice_number)
                                            {{ $sale->invoice_number }}
                                        @else
                                            <span class="text-muted">Sin factura</span>
                                        @endif
                                    </td>
                                    <td>{{ $sale->customer_name ?: 'Cliente general' }}</td>
                                    <td>{{ $sale->saleItems->count() }} items</td>
                                    <td class="fw-bold text-success">₲ {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($sale->status === 'COMPLETED')
                                            <span class="badge bg-success">Completada</span>
                                        @elseif($sale->status === 'PENDING')
                                            <span class="badge bg-warning">Pendiente</span>
                                        @else
                                            <span class="badge bg-danger">Cancelada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('sales.show', $sale) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($sale->invoice)
                                            <a href="{{ route('invoices.print', $sale->invoice) }}" 
                                               class="btn btn-sm btn-outline-success" target="_blank" title="Imprimir">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-3 mb-0">No hay ventas registradas</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($sales->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $sales->links() }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Bootstrap 5 JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </div>
    </div>
</x-app-layout>