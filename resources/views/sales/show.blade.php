@extends('layouts.pos')

@section('title', 'Detalle de Venta #'.$sale->id)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">
            <i class="bi bi-receipt me-2"></i>
            Detalle de Venta #{{ $sale->id }}
        </h2>
        <div class="btn-group">
            <a href="{{ route('pos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver al POS
            </a>
            @if($sale->sale_type === 'INVOICE')
            <button class="btn btn-primary">
                <i class="bi bi-printer me-1"></i>
                Imprimir Factura
            </button>
            @else
            <button class="btn btn-outline-primary">
                <i class="bi bi-receipt me-1"></i>
                Imprimir Ticket
            </button>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Información General -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha y Hora</label>
                            <p class="mb-0">{{ $sale->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Número de Venta</label>
                            <p class="mb-0">{{ $sale->sale_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Estado</label>
                            <p class="mb-0">
                                @if($sale->status === 'COMPLETED')
                                    <span class="badge bg-success">Completada</span>
                                @elseif($sale->status === 'PENDING')
                                    <span class="badge bg-warning">Pendiente</span>
                                @else
                                    <span class="badge bg-danger">Cancelada</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Cliente</label>
                            <p class="mb-0">{{ $sale->customer_name ?: 'Cliente general' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Documento</label>
                            <p class="mb-0">{{ $sale->customer_document ?: '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Vendedor</label>
                            <p class="mb-0">{{ $sale->user->name ?? 'Administrador Sistema' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items de la Venta -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-cart-check me-2"></i>
                        Items Vendidos
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th class="text-end">Precio Unit.</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">IVA</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->saleItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-semibold">{{ $item->product_name }}</div>
                                                @if($item->iva_type !== 'EXENTO')
                                                    <small class="text-muted">{{ $item->iva_type }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="small">{{ $item->product_code }}</code>
                                    </td>
                                    <td class="text-end">
                                        <strong>₲ {{ number_format($item->unit_price, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light text-dark">{{ number_format($item->quantity, 0) }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if($item->iva_amount > 0)
                                            <span class="text-success">₲ {{ number_format($item->iva_amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">Exento</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>₲ {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                            No hay items en esta venta
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totales y Pago -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calculator me-2"></i>
                        Totales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Subtotal:</span>
                        <strong>₲ {{ number_format($sale->subtotal, 0, ',', '.') }}</strong>
                    </div>
                    @if($sale->discount_amount > 0)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Descuento:</span>
                        <span class="text-danger">- ₲ {{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>IVA (10%):</span>
                        <strong>₲ {{ number_format($sale->tax_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-3 border-top border-2 mt-2">
                        <span class="h5 mb-0">TOTAL:</span>
                        <span class="h4 mb-0 text-success">₲ {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Pago -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>
                        Información de Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Método de Pago</label>
                            <p class="mb-0">
                                @if($sale->payment_method === 'CASH')
                                    <i class="bi bi-cash me-1"></i> Efectivo
                                @elseif($sale->payment_method === 'TRANSFER')
                                    <i class="bi bi-bank me-1"></i> Transferencia
                                @elseif($sale->payment_method === 'CARD')
                                    <i class="bi bi-credit-card me-1"></i> Tarjeta
                                @else
                                    {{ $sale->payment_method }}
                                @endif
                            </p>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Monto Pagado</label>
                            <p class="mb-0 text-primary">₲ {{ number_format($sale->amount_paid, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Cambio</label>
                            <p class="mb-0 text-success">₲ {{ number_format($sale->change_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tipo de Comprobante</label>
                            <p class="mb-0">
                                @if($sale->sale_type === 'TICKET')
                                    <i class="bi bi-receipt me-1"></i> Ticket
                                @else
                                    <i class="bi bi-file-earmark-text me-1"></i> Factura
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notas -->
        @if($sale->notes)
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-chat-text me-2"></i>
                        Notas
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $sale->notes }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection