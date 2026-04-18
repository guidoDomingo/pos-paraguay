<x-app-layout>
    <div class="py-4">
        <div class="container-fluid px-4">

            <!-- Header -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('cash.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-journal-check me-2 text-secondary"></i>
                        Detalle de Caja #{{ $cashRegister->id }}
                    </h4>
                    <small class="text-muted">
                        {{ $cashRegister->opened_at->format('d/m/Y H:i') }} —
                        @if($cashRegister->closed_at)
                            Cerrada {{ $cashRegister->closed_at->format('d/m/Y H:i') }}
                        @else
                            <span class="text-success fw-semibold">Abierta</span>
                        @endif
                    </small>
                </div>
                <span class="badge ms-auto {{ $cashRegister->status === 'OPEN' ? 'bg-success' : 'bg-secondary' }} py-2 px-3">
                    {{ $cashRegister->status === 'OPEN' ? '🟢 Abierta' : '🔒 Cerrada' }}
                </span>
            </div>

            <!-- Arqueo / Resumen financiero -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Resumen
                        </div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td class="text-muted">Cajero</td>
                                    <td class="fw-semibold">{{ $cashRegister->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Monto inicial</td>
                                    <td class="fw-semibold">₲ {{ number_format($cashRegister->opening_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total ventas</td>
                                    <td class="fw-semibold text-primary">₲ {{ number_format($cashRegister->sales->sum('total_amount'), 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Ingresos manuales</td>
                                    <td class="fw-semibold text-success">₲ {{ number_format($incomes, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Egresos manuales</td>
                                    <td class="fw-semibold text-danger">₲ {{ number_format($expenses, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="bi bi-pie-chart me-2 text-info"></i>Ventas por método
                        </div>
                        <div class="card-body">
                            @foreach(['CASH'=>['Efectivo','success'],'CARD'=>['Tarjeta','primary'],'TRANSFER'=>['Transferencia','info'],'CHEQUE'=>['Cheque','warning'],'CREDIT'=>['Crédito','secondary']] as $key=>[$label,$color])
                            @if($byMethod[$key] > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ $label }}</span>
                                <span class="fw-semibold text-{{ $color }}">₲ {{ number_format($byMethod[$key], 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($cashRegister->status === 'CLOSED')
                <div class="col-md-12 col-xl-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="bi bi-calculator me-2 text-warning"></i>Resultado del Arqueo
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Esperado en caja</span>
                                <span class="fw-bold">₲ {{ number_format($cashRegister->expected_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Contado físicamente</span>
                                <span class="fw-bold">₲ {{ number_format($cashRegister->closing_amount, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-5">Diferencia</span>
                                <span class="fw-bold fs-4 {{ $cashRegister->difference_amount == 0 ? 'text-success' : ($cashRegister->difference_amount > 0 ? 'text-warning' : 'text-danger') }}">
                                    {{ $cashRegister->difference_amount >= 0 ? '+' : '' }}₲ {{ number_format($cashRegister->difference_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            @if($cashRegister->difference_amount == 0)
                            <div class="alert alert-success mt-3 mb-0 text-center py-2">✅ Caja cuadrada</div>
                            @elseif($cashRegister->difference_amount > 0)
                            <div class="alert alert-warning mt-3 mb-0 text-center py-2">⚠️ Sobrante</div>
                            @else
                            <div class="alert alert-danger mt-3 mb-0 text-center py-2">❌ Faltante</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Tablas de ventas y movimientos -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Ventas</h6>
                            <span class="badge bg-primary">{{ $cashRegister->sales->count() }}</span>
                        </div>
                        <div class="card-body p-0">
                            @if($cashRegister->sales->isEmpty())
                            <div class="text-center py-4 text-muted"><i class="bi bi-cart-x fs-3"></i><p class="mt-2 mb-0">Sin ventas</p></div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">N°</th>
                                            <th>Cliente</th>
                                            <th>Método</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-end pe-3">Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cashRegister->sales as $sale)
                                        <tr>
                                            <td class="ps-3"><a href="{{ route('sales.show', $sale) }}" class="text-decoration-none fw-semibold">{{ $sale->sale_number }}</a></td>
                                            <td class="text-muted small">{{ $sale->customer_name ?: 'Consumidor final' }}</td>
                                            <td>
                                                @php $pm = $sale->payment_method; @endphp
                                                <span class="badge {{ $pm==='CASH'?'bg-success':($pm==='CARD'?'bg-primary':($pm==='TRANSFER'?'bg-info':'bg-secondary')) }}">
                                                    {{ match($pm){ 'CASH'=>'Efectivo','CARD'=>'Tarjeta','TRANSFER'=>'Transfer.','CHEQUE'=>'Cheque',default=>$pm } }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">₲ {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                            <td class="text-end pe-3 text-muted small">{{ $sale->sale_date->format('H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-left-right me-2 text-warning"></i>Movimientos</h6>
                        </div>
                        <div class="card-body p-0">
                            @if($cashRegister->movements->isEmpty())
                            <div class="text-center py-4 text-muted small"><i class="bi bi-inbox fs-4"></i><p class="mt-1 mb-0">Sin movimientos</p></div>
                            @else
                            @foreach($cashRegister->movements as $mov)
                            <div class="d-flex justify-content-between align-items-start px-3 py-2 border-bottom">
                                <div>
                                    <span class="badge {{ $mov->type==='INCOME'?'bg-success':($mov->type==='EXPENSE'?'bg-danger':'bg-warning text-dark') }} me-1">{{ $mov->getTypeLabel() }}</span>
                                    <div class="text-muted small mt-1">{{ $mov->description }}</div>
                                    <div class="text-muted" style="font-size:11px;">{{ $mov->created_at->format('H:i') }}</div>
                                </div>
                                <span class="fw-bold {{ $mov->type==='INCOME'?'text-success':'text-danger' }}">
                                    {{ $mov->type==='INCOME'?'+':'-' }} ₲ {{ number_format($mov->amount, 0, ',', '.') }}
                                </span>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
