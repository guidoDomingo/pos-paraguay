<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-patch-check me-2"></i>{{ __('Detalles del Timbrado Fiscal') }}
            </h2>
            <div>
                <a href="{{ route('fiscal-stamps.edit', $fiscalStamp) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <a href="{{ route('fiscal-stamps.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                <!-- Información Principal -->
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información del Timbrado
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1">Número de Timbrado:</strong>
                                    <h4 class="text-primary mb-0">{{ $fiscalStamp->stamp_number }}</h4>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1">Estado:</strong>
                                    @if($fiscalStamp->is_active)
                                        @if($fiscalStamp->valid_until->isPast())
                                            <h5><span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle"></i> Vencido
                                            </span></h5>
                                        @else
                                            <h5><span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Activo
                                            </span></h5>
                                        @endif
                                    @else
                                        <h5><span class="badge bg-secondary">
                                            <i class="bi bi-x-circle"></i> Inactivo
                                        </span></h5>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1"><i class="bi bi-calendar-check text-success"></i> Vigencia Desde:</strong>
                                    <p class="mb-0">{{ $fiscalStamp->valid_from->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1"><i class="bi bi-calendar-x {{ $fiscalStamp->valid_until->isPast() ? 'text-danger' : 'text-warning' }}"></i> Vigencia Hasta:</strong>
                                    <p class="mb-0">{{ $fiscalStamp->valid_until->format('d/m/Y') }}</p>
                                    @if($fiscalStamp->valid_until->isPast())
                                        <small class="text-danger">Venció hace {{ $fiscalStamp->valid_until->diffForHumans() }}</small>
                                    @elseif($fiscalStamp->valid_until->diffInDays() <= 30)
                                        <small class="text-warning">Vence {{ $fiscalStamp->valid_until->diffForHumans() }}</small>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1"><i class="bi bi-building"></i> Establecimiento:</strong>
                                    <p class="mb-0 fs-5">{{ $fiscalStamp->establishment }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1"><i class="bi bi-shop"></i> Punto de Venta:</strong>
                                    <p class="mb-0 fs-5">{{ $fiscalStamp->point_of_sale }}</p>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Formato de factura:</strong> {{ $fiscalStamp->establishment }}-{{ $fiscalStamp->point_of_sale }}-0000001
                            </div>
                        </div>
                    </div>

                    <!-- Numeración de Facturas -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-file-text me-2"></i>
                                Numeración de Facturas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1">Número Actual:</strong>
                                    <h3 class="text-info mb-0">{{ number_format($fiscalStamp->current_invoice_number) }}</h3>
                                </div>
                                <div class="col-md-6">
                                    <strong class="text-muted d-block mb-1">Número Máximo:</strong>
                                    <h3 class="text-muted mb-0">{{ number_format($fiscalStamp->max_invoice_number) }}</h3>
                                </div>
                            </div>

                            @php
                                $percentage = ($fiscalStamp->current_invoice_number / $fiscalStamp->max_invoice_number) * 100;
                                $remaining = $fiscalStamp->max_invoice_number - $fiscalStamp->current_invoice_number;
                            @endphp

                            <div class="mb-2">
                                <strong class="text-muted">Uso del timbrado:</strong>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar {{ $percentage > 90 ? 'bg-danger' : ($percentage > 70 ? 'bg-warning' : 'bg-success') }}" 
                                         role="progressbar" 
                                         style="width: {{ $percentage }}%" 
                                         aria-valuenow="{{ $percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ number_format($percentage, 1) }}%
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <h4 class="mb-1">{{ number_format($remaining) }}</h4>
                                <p class="text-muted mb-0">Facturas disponibles</p>
                            </div>

                            @if($percentage > 90)
                                <div class="alert alert-danger mt-3">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Atención:</strong> El timbrado está casi agotado. Considere solicitar uno nuevo.
                                </div>
                            @elseif($percentage > 70)
                                <div class="alert alert-warning mt-3">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Advertencia:</strong> Se recomienda comenzar los trámites para un nuevo timbrado.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Panel Lateral -->
                <div class="col-lg-4">
                    <!-- Estadísticas -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-graph-up me-2"></i>Estadísticas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block">Facturas Emitidas</small>
                                <h3 class="mb-0 text-primary">{{ number_format($fiscalStamp->current_invoice_number) }}</h3>
                            </div>
                            <div class="mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block">Días de Vigencia Restantes</small>
                                @if($fiscalStamp->valid_until->isPast())
                                    <h3 class="mb-0 text-danger">0</h3>
                                    <small class="text-danger">Vencido</small>
                                @else
                                    <h3 class="mb-0 text-success">{{ $fiscalStamp->valid_until->diffInDays() }}</h3>
                                @endif
                            </div>
                            <div>
                                <small class="text-muted d-block">Registrado</small>
                                <p class="mb-0">{{ $fiscalStamp->created_at->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">{{ $fiscalStamp->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-gear me-2"></i>Acciones
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('fiscal-stamps.edit', $fiscalStamp) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-1"></i>Editar Timbrado
                                </a>
                                
                                @if($fiscalStamp->is_active)
                                    <button type="button" class="btn btn-warning" onclick="toggleActive(false)">
                                        <i class="bi bi-pause-circle me-1"></i>Desactivar
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success" onclick="toggleActive(true)">
                                        <i class="bi bi-play-circle me-1"></i>Activar
                                    </button>
                                @endif

                                <form id="delete-form" method="POST" action="{{ route('fiscal-stamps.destroy', $fiscalStamp) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger w-100" onclick="confirmDelete()">
                                        <i class="bi bi-trash me-1"></i>Eliminar Timbrado
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Alertas -->
                    @if($fiscalStamp->valid_until->isPast())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Timbrado Vencido</strong>
                            <p class="mb-0 mt-2">Este timbrado venció el {{ $fiscalStamp->valid_until->format('d/m/Y') }}</p>
                        </div>
                    @elseif($fiscalStamp->valid_until->diffInDays() <= 30)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Próximo a Vencer</strong>
                            <p class="mb-0 mt-2">Este timbrado vence en {{ $fiscalStamp->valid_until->diffInDays() }} días</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('¿Está seguro de eliminar este timbrado fiscal?\n\nEsta acción no se puede deshacer.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
</x-app-layout>
