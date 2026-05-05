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
        @php
            $totalCost = $sale->saleItems->sum(function($item) {
                $cost = $item->cost_price ?? optional($item->product)->cost_price ?? 0;
                return $cost * $item->quantity;
            });
            $ganancia = $sale->total_amount - $totalCost;
            $margen   = $sale->total_amount > 0 ? round(($ganancia / $sale->total_amount) * 100, 1) : 0;
        @endphp
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
                                    <th class="text-end">P. Costo</th>
                                    <th class="text-end">P. Venta</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">Total Costo</th>
                                    <th class="text-end">Total Venta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->saleItems as $item)
                                @php
                                    $itemCost      = $item->cost_price ?? optional($item->product)->cost_price ?? 0;
                                    $itemTotalCost = $itemCost * $item->quantity;
                                    $itemGanancia  = $item->total_price - $itemTotalCost;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                        @if($item->iva_type !== 'EXENTO')
                                            <small class="text-muted">{{ $item->iva_type }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="small">{{ $item->product_code }}</code>
                                    </td>
                                    <td class="text-end text-muted">
                                        ₲ {{ number_format($itemCost, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        <strong>₲ {{ number_format($item->unit_price, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light text-dark">{{ number_format($item->quantity, 0) }}</span>
                                    </td>
                                    <td class="text-end text-muted">
                                        ₲ {{ number_format($itemTotalCost, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        <strong>₲ {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                                        @if($itemCost > 0)
                                            <br><small class="{{ $itemGanancia >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $itemGanancia >= 0 ? '+' : '' }}₲ {{ number_format($itemGanancia, 0, ',', '.') }}
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                            No hay items en esta venta
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="5" class="text-end">Totales:</td>
                                    <td class="text-end text-muted">₲ {{ number_format($totalCost, 0, ',', '.') }}</td>
                                    <td class="text-end text-success">₲ {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
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
                        <span class="h5 mb-0">TOTAL VENTA:</span>
                        <span class="h4 mb-0 text-success">₲ {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <!-- Rentabilidad -->
                    <div class="border-top pt-3 mt-1">
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Total Costo:</span>
                            <span class="text-muted">₲ {{ number_format($totalCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-top mt-1">
                            <span class="fw-bold {{ $ganancia >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="bi bi-graph-up-arrow me-1"></i>Ganancia:
                            </span>
                            <span class="fw-bold fs-5 {{ $ganancia >= 0 ? 'text-success' : 'text-danger' }}">
                                ₲ {{ number_format($ganancia, 0, ',', '.') }}
                                <small class="fs-6">({{ $margen }}%)</small>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Pago -->
        <div class="col-lg-6">
            <div class="card {{ $sale->balance_due > 0 ? 'border-danger border-2' : '' }}">
                <div class="card-header {{ $sale->balance_due > 0 ? 'bg-danger' : 'bg-warning' }} text-{{ $sale->balance_due > 0 ? 'white' : 'dark' }}">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>
                        Información de Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Condición de Venta</label>
                            <p class="mb-0">
                                @if($sale->sale_condition === 'CREDITO')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-calendar-check"></i> Crédito
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-cash-coin"></i> Contado
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Método de Pago</label>
                            <p class="mb-0">
                                @if($sale->payment_method === 'CASH')
                                    <i class="bi bi-cash me-1"></i> Efectivo
                                @elseif($sale->payment_method === 'TRANSFER')
                                    <i class="bi bi-bank me-1"></i> Transferencia
                                @elseif($sale->payment_method === 'CARD')
                                    <i class="bi bi-credit-card me-1"></i> Tarjeta
                                @elseif($sale->payment_method === 'CHEQUE')
                                    <i class="bi bi-check2-square me-1"></i> Cheque
                                @else
                                    {{ $sale->payment_method }}
                                @endif
                            </p>
                        </div>
                        
                        @if($sale->sale_condition === 'CREDITO')
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="alert alert-{{ $sale->balance_due > 0 ? 'warning' : 'success' }} mb-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Total de la Venta:</strong>
                                    <strong>₲ {{ number_format($sale->total_amount, 0, ',', '.') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Monto Abonado:</span>
                                    <span class="text-primary">₲ {{ number_format($sale->amount_paid ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <strong class="text-{{ $sale->balance_due > 0 ? 'danger' : 'success' }}">Saldo Pendiente:</strong>
                                    <strong class="text-{{ $sale->balance_due > 0 ? 'danger' : 'success' }} fs-5">
                                        @if($sale->balance_due > 0)
                                            <i class="bi bi-exclamation-circle"></i>
                                        @else
                                            <i class="bi bi-check-circle"></i>
                                        @endif
                                        ₲ {{ number_format($sale->balance_due ?? 0, 0, ',', '.') }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                        
                        @if($sale->sale_condition === 'CREDITO' && $sale->balance_due > 0)
                        <!-- Formulario para Registrar Pago -->
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="mb-3">
                                <i class="bi bi-plus-circle me-1"></i>
                                Registrar Pago Adicional
                            </h6>
                            <form action="{{ route('payments.store', $sale) }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label">Monto del Pago <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₲</span>
                                            <input type="number" 
                                                   class="form-control @error('amount') is-invalid @enderror" 
                                                   id="amount" 
                                                   name="amount" 
                                                   min="1" 
                                                   max="{{ $sale->balance_due }}"
                                                   step="1" 
                                                   placeholder="0"
                                                   required>
                                        </div>
                                        <small class="text-muted">Máximo: ₲ {{ number_format($sale->balance_due, 0, ',', '.') }}</small>
                                        @error('amount')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="payment_method" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                                        <select class="form-select @error('payment_method') is-invalid @enderror" 
                                                id="payment_method" 
                                                name="payment_method" 
                                                required>
                                            <option value="">Seleccionar...</option>
                                            <option value="CASH">Efectivo</option>
                                            <option value="CARD">Tarjeta</option>
                                            <option value="CHEQUE">Cheque</option>
                                            <option value="TRANSFER">Transferencia</option>
                                        </select>
                                        @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="notes" class="form-label">Notas del Pago (opcional)</label>
                                        <textarea class="form-control" 
                                                  id="notes" 
                                                  name="notes" 
                                                  rows="2" 
                                                  maxlength="500" 
                                                  placeholder="Ej: Pago parcial acordado"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Registrar Pago
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                        @else
                        <div class="col-6">
                            <label class="form-label fw-semibold">Monto Pagado</label>
                            <p class="mb-0 text-primary">₲ {{ number_format($sale->amount_paid ?? $sale->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Cambio</label>
                            <p class="mb-0 text-success">₲ {{ number_format($sale->change_amount ?? 0, 0, ',', '.') }}</p>
                        </div>
                        @endif
                        
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

        <!-- Historial de Pagos (solo para ventas a crédito) -->
        @if($sale->sale_condition === 'CREDITO' && $sale->payments && $sale->payments->count() > 0)
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Historial de Pagos ({{ $sale->payments->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Usuario</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->payments as $payment)
                                <tr>
                                    <td>
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                        {{ $payment->payment_date->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <strong class="text-success">₲ {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @if($payment->payment_method === 'CASH')
                                            <i class="bi bi-cash text-success"></i> Efectivo
                                        @elseif($payment->payment_method === 'CARD')
                                            <i class="bi bi-credit-card text-primary"></i> Tarjeta
                                        @elseif($payment->payment_method === 'CHEQUE')
                                            <i class="bi bi-check2-square text-info"></i> Cheque
                                        @elseif($payment->payment_method === 'TRANSFER')
                                            <i class="bi bi-bank text-warning"></i> Transferencia
                                        @endif
                                    </td>
                                    <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                    <td>{{ $payment->notes ?: '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Abonado:</strong></td>
                                    <td>
                                        <strong class="text-success">₲ {{ number_format($sale->amount_paid, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

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