<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-receipt me-2"></i>{{ __('Facturas Emitidas') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filtro/búsqueda -->
            <form method="GET" action="{{ route('invoices.index') }}" class="row mb-4 g-2" id="invoice-filter-form">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search"
                               placeholder="Buscar por número, cliente, RUC..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="condition">
                        <option value="">Todas las condiciones</option>
                        <option value="CONTADO" {{ request('condition') === 'CONTADO' ? 'selected' : '' }}>Contado</option>
                        <option value="CREDITO" {{ request('condition') === 'CREDITO' ? 'selected' : '' }}>Crédito</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="electronic">
                        <option value="">Todos los tipos</option>
                        <option value="electronic" {{ request('electronic') === 'electronic' ? 'selected' : '' }}>Electrónica</option>
                        <option value="normal"     {{ request('electronic') === 'normal'     ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary" id="btn-limpiar-inv2"
                       title="Limpiar filtros"
                       style="{{ request()->hasAny(['search','condition','electronic']) ? '' : 'display:none' }}">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>

            <!-- Tabla -->
            <div class="card shadow" id="invoices-results">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Lista de Facturas ({{ $invoices->total() }} total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Factura</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Condición</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px; flex-shrink: 0;">
                                                        <i class="bi bi-receipt text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-primary">{{ $invoice->invoice_number }}</strong>
                                                        @if($invoice->is_electronic)
                                                            <br><span class="badge bg-info" style="font-size: 0.7rem;">
                                                                <i class="bi bi-lightning-fill"></i> Electrónica
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $invoice->customer_name ?? 'Sin nombre' }}</strong>
                                                @if($invoice->customer_ruc)
                                                    <br><small class="text-muted">RUC: {{ $invoice->customer_ruc }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
                                                @if($invoice->sale?->user)
                                                    <br><small class="text-muted">{{ $invoice->sale->user->name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($invoice->condition === 'CONTADO')
                                                    <span class="badge bg-success">Contado</span>
                                                @elseif($invoice->condition === 'CREDITO')
                                                    <span class="badge bg-warning text-dark">Crédito</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $invoice->condition }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <strong>{{ number_format($invoice->total_amount, 0, ',', '.') }} Gs.</strong>
                                                @if($invoice->total_iva > 0)
                                                    <br><small class="text-muted">IVA: {{ number_format($invoice->total_iva, 0, ',', '.') }} Gs.</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($invoice->is_electronic)
                                                    @php $status = $invoice->electronic_status; @endphp
                                                    @if($status === 'approved')
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aprobada</span>
                                                    @elseif($status === 'rejected')
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rechazada</span>
                                                    @elseif($status === 'error')
                                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Error</span>
                                                    @else
                                                        <span class="badge bg-secondary"><i class="bi bi-clock"></i> Pendiente</span>
                                                    @endif
                                                @elseif($invoice->is_printed)
                                                    <span class="badge bg-success"><i class="bi bi-printer"></i> Impresa</span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="bi bi-clock"></i> Sin imprimir</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-info" title="Ver detalle">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-sm btn-outline-secondary" title="Imprimir" target="_blank">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($invoices->hasPages())
                            <div class="card-footer">
                                {{ $invoices->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-receipt" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay facturas registradas</h5>
                            <p class="text-muted">Las facturas aparecerán aquí una vez que se realicen ventas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm  = document.getElementById('invoice-filter-form');
            const searchInput = filterForm.querySelector('input[name="search"]');
            let searchTimer;

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                const val = this.value.trim();
                if (val.length === 0 || val.length >= 2) {
                    searchTimer = setTimeout(() => buscarFacturas(), 350);
                }
            });

            filterForm.querySelectorAll('select').forEach(sel => {
                sel.addEventListener('change', () => buscarFacturas());
            });

            function buscarFacturas() {
                const params = new URLSearchParams(new FormData(filterForm));
                const url    = filterForm.action + '?' + params.toString();
                history.pushState({}, '', url);
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const newCard = doc.getElementById('invoices-results');
                        if (newCard) document.getElementById('invoices-results').innerHTML = newCard.innerHTML;
                        const tienesFiltros = params.get('search') || params.get('condition') || params.get('electronic');
                        const btn = document.getElementById('btn-limpiar-inv2');
                        if (btn) btn.style.display = tienesFiltros ? '' : 'none';
                    });
            }
        });
    </script>
    @endpush
</x-app-layout>
