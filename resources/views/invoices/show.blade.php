<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-receipt me-2"></i>Factura {{ $invoice->invoice_number }}
            </h2>
            <div class="btn-group">
                <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-outline-secondary" target="_blank">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </a>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row">

                <!-- Columna principal -->
                <div class="col-xl-8">

                    <!-- Datos del cliente y factura -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Datos de la Factura</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Cliente</h6>
                                    <p class="mb-1"><strong>{{ $invoice->customer_name ?? 'Sin nombre' }}</strong></p>
                                    @if($invoice->customer_ruc)
                                        <p class="mb-1 text-muted">RUC: {{ $invoice->customer_ruc }}</p>
                                    @endif
                                    @if($invoice->customer_address)
                                        <p class="mb-3 text-muted">{{ $invoice->customer_address }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Datos Fiscales</h6>
                                    <p class="mb-1">
                                        <strong>Timbrado:</strong> {{ $invoice->stamp_number }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Est. - Pto. Vta.:</strong>
                                        {{ $invoice->establishment }}-{{ $invoice->point_of_sale }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Fecha:</strong>
                                        {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                    </p>
                                    <p class="mb-0">
                                        <strong>Condición:</strong>
                                        @if($invoice->condition === 'CONTADO')
                                            <span class="badge bg-success">Contado</span>
                                        @elseif($invoice->condition === 'CREDITO')
                                            <span class="badge bg-warning text-dark">Crédito</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $invoice->condition }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($invoice->is_electronic)
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-muted mb-2"><i class="bi bi-lightning-fill text-info me-1"></i>Factura Electrónica</h6>
                                        <div class="d-flex flex-wrap gap-3">
                                            <div>
                                                <small class="text-muted">Estado SET</small><br>
                                                @php $es = $invoice->electronic_status; @endphp
                                                @if($es === 'approved')
                                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aprobada</span>
                                                @elseif($es === 'rejected')
                                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rechazada</span>
                                                @elseif($es === 'error')
                                                    <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Error</span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="bi bi-clock me-1"></i>Pendiente</span>
                                                @endif
                                            </div>
                                            @if($invoice->cdc)
                                                <div>
                                                    <small class="text-muted">CDC</small><br>
                                                    <code class="small">{{ $invoice->cdc }}</code>
                                                </div>
                                            @endif
                                            @if($invoice->electronic_approved_at)
                                                <div>
                                                    <small class="text-muted">Aprobada el</small><br>
                                                    <span>{{ $invoice->electronic_approved_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        @if($invoice->electronic_error)
                                            <div class="alert alert-danger mt-2 mb-0 py-2">
                                                <small><strong>Error:</strong> {{ $invoice->electronic_error }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Ítems -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Detalle de Productos</h5>
                        </div>
                        <div class="card-body p-0">
                            @if($invoice->sale && $invoice->sale->saleItems->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Producto</th>
                                                <th class="text-center">Cantidad</th>
                                                <th class="text-end">Precio Unit.</th>
                                                <th class="text-center">IVA</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice->sale->saleItems as $item)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $item->product->name ?? 'Producto eliminado' }}</strong>
                                                        @if($item->product)
                                                            <br><small class="text-muted">{{ $item->product->code }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-end">₲ {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                                    <td class="text-center">
                                                        @php $iva = $item->product->iva_type ?? 'IVA_10'; @endphp
                                                        @if($iva === 'EXENTO')
                                                            <span class="badge bg-secondary">Exento</span>
                                                        @elseif($iva === 'IVA_5')
                                                            <span class="badge bg-info">5%</span>
                                                        @else
                                                            <span class="badge bg-primary">10%</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end"><strong>₲ {{ number_format($item->total_price, 0, ',', '.') }}</strong></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size:2rem"></i>
                                    <p class="mt-2 mb-0">No hay ítems registrados.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Panel lateral -->
                <div class="col-xl-4">

                    <!-- Totales -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Totales</h5>
                        </div>
                        <div class="card-body">
                            @if($invoice->subtotal_exento > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Subtotal Exento</span>
                                    <span>₲ {{ number_format($invoice->subtotal_exento, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($invoice->subtotal_iva_5 > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Subtotal IVA 5%</span>
                                    <span>₲ {{ number_format($invoice->subtotal_iva_5, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">IVA 5%</span>
                                    <span>₲ {{ number_format($invoice->total_iva_5, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($invoice->subtotal_iva_10 > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Subtotal IVA 10%</span>
                                    <span>₲ {{ number_format($invoice->subtotal_iva_10, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">IVA 10%</span>
                                    <span>₲ {{ number_format($invoice->total_iva_10, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($invoice->total_iva > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total IVA</span>
                                    <span>₲ {{ number_format($invoice->total_iva, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong class="fs-5">Total</strong>
                                <strong class="fs-5 text-success">₲ {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Info adicional -->
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-info me-2"></i>Información Adicional</h5>
                        </div>
                        <div class="card-body">
                            @if($invoice->sale?->user)
                                <div class="mb-2">
                                    <small class="text-muted">Vendedor</small><br>
                                    <strong>{{ $invoice->sale->user->name }}</strong>
                                </div>
                            @endif
                            <div class="mb-2">
                                <small class="text-muted">Impresa</small><br>
                                @if($invoice->is_printed)
                                    <span class="badge bg-success"><i class="bi bi-printer me-1"></i>Sí</span>
                                    @if($invoice->printed_at)
                                        <small class="text-muted ms-1">{{ $invoice->printed_at->format('d/m/Y H:i') }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">No impresa</span>
                                @endif
                            </div>
                            @if($invoice->observations)
                                <div class="mb-2">
                                    <small class="text-muted">Observaciones</small><br>
                                    <span>{{ $invoice->observations }}</span>
                                </div>
                            @endif
                            <div>
                                <small class="text-muted">Registrada el</small><br>
                                <span>{{ $invoice->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="card shadow">
                        <div class="card-body d-grid gap-2">
                            <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-primary" target="_blank">
                                <i class="bi bi-printer me-2"></i>Imprimir Factura
                            </a>
                            @if($invoice->sale)
                                <a href="{{ route('sales.show', $invoice->sale) }}" class="btn btn-outline-info">
                                    <i class="bi bi-cart me-2"></i>Ver Venta Asociada
                                </a>
                            @endif
                            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Volver al Listado
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
