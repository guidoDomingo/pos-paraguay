<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-patch-check me-2"></i>{{ __('Gestión de Timbrados Fiscales') }}
            </h2>
            <a href="{{ route('fiscal-stamps.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Nuevo Timbrado
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filtros y búsqueda -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar timbrados..." id="searchStamps">
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select" id="filterStatus">
                        <option value="">Todos los timbrados</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                        <option value="expired">Vencidos</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de timbrados -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Lista de Timbrados Fiscales ({{ $fiscalStamps->total() }} total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($fiscalStamps->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Número de Timbrado</th>
                                        <th>Establecimiento - Punto de Venta</th>
                                        <th>Vigencia</th>
                                        <th class="text-center">Facturación</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fiscalStamps as $stamp)
                                        <tr class="{{ !$stamp->is_active ? 'table-secondary' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="bi bi-patch-check text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-primary">{{ $stamp->stamp_number }}</strong>
                                                        <br><small class="text-muted">
                                                            Creado: {{ $stamp->created_at->format('d/m/Y') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $stamp->establishment }}-{{ $stamp->point_of_sale }}</strong>
                                                <br><small class="text-muted">Establecimiento - Punto de Venta</small>
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="bi bi-calendar-check text-success"></i>
                                                    <small>Desde: {{ $stamp->valid_from->format('d/m/Y') }}</small>
                                                </div>
                                                <div>
                                                    <i class="bi bi-calendar-x {{ $stamp->valid_until->isPast() ? 'text-danger' : 'text-warning' }}"></i>
                                                    <small>Hasta: {{ $stamp->valid_until->format('d/m/Y') }}</small>
                                                </div>
                                                @if($stamp->valid_until->isPast())
                                                    <span class="badge bg-danger mt-1">Vencido</span>
                                                @elseif($stamp->valid_until->diffInDays() <= 30)
                                                    <span class="badge bg-warning mt-1">Por vencer</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="mb-1">
                                                    <strong class="text-info">{{ number_format($stamp->current_invoice_number) }}</strong>
                                                    <small class="text-muted">/ {{ number_format($stamp->max_invoice_number) }}</small>
                                                </div>
                                                @php
                                                    $percentage = ($stamp->current_invoice_number / $stamp->max_invoice_number) * 100;
                                                @endphp
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar {{ $percentage > 90 ? 'bg-danger' : ($percentage > 70 ? 'bg-warning' : 'bg-success') }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $percentage }}%" 
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ number_format($percentage, 1) }}% usado</small>
                                            </td>
                                            <td>
                                                @if($stamp->is_active)
                                                    @if($stamp->valid_until->isPast())
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-exclamation-triangle"></i> Vencido
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle"></i> Activo
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-x-circle"></i> Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('fiscal-stamps.show', $stamp) }}" class="btn btn-sm btn-outline-info" title="Ver detalles">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('fiscal-stamps.edit', $stamp) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('fiscal-stamps.destroy', $stamp) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" 
                                                            onclick="return confirm('¿Está seguro de eliminar este timbrado?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        @if($fiscalStamps->hasPages())
                            <div class="card-footer">
                                {{ $fiscalStamps->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-patch-check" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay timbrados registrados</h5>
                            <p class="text-muted">Comienza creando tu primer timbrado fiscal para generar facturas</p>
                            <a href="{{ route('fiscal-stamps.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Crear Primer Timbrado
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Alertas y estadísticas -->
            @if($fiscalStamps->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Timbrados Activos</h6>
                                        <h3 class="mb-0">{{ $fiscalStamps->where('is_active', true)->where('valid_until', '>=', now())->count() }}</h3>
                                    </div>
                                    <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Por Vencer (30 días)</h6>
                                        <h3 class="mb-0">{{ $fiscalStamps->where('is_active', true)->filter(function($stamp) { return $stamp->valid_until->diffInDays() <= 30 && !$stamp->valid_until->isPast(); })->count() }}</h3>
                                    </div>
                                    <i class="bi bi-exclamation-triangle" style="font-size: 3rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Vencidos</h6>
                                        <h3 class="mb-0">{{ $fiscalStamps->filter(function($stamp) { return $stamp->valid_until->isPast(); })->count() }}</h3>
                                    </div>
                                    <i class="bi bi-x-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Búsqueda de timbrados
        document.getElementById('searchStamps')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Filtro por estado
        document.getElementById('filterStatus')?.addEventListener('change', function(e) {
            const filter = e.target.value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (filter === '') {
                    row.style.display = '';
                } else if (filter === 'active') {
                    const hasActive = row.querySelector('.badge.bg-success');
                    row.style.display = hasActive ? '' : 'none';
                } else if (filter === 'inactive') {
                    const hasInactive = row.querySelector('.badge.bg-secondary');
                    row.style.display = hasInactive ? '' : 'none';
                } else if (filter === 'expired') {
                    const hasExpired = row.querySelector('.badge.bg-danger');
                    row.style.display = hasExpired ? '' : 'none';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
