<x-app-layout>
    <div class="py-4">
        <div class="container-fluid px-4">

            @foreach(['success','error','warning','info'] as $type)
            @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show mb-3" role="alert">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @endforeach

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="bi bi-cash-coin me-2 text-success"></i>Caja Actual</h4>
                    <div class="text-muted small">
                        Abierta por <strong>{{ $register->user->name }}</strong>
                        el {{ $register->opened_at->format('d/m/Y') }} a las {{ $register->opened_at->format('H:i') }}
                        &nbsp;·&nbsp; Monto inicial: <strong>₲ {{ number_format($register->opening_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#movementModal" data-type="INCOME">
                        <i class="bi bi-plus-circle-fill me-1"></i>Ingreso
                    </button>
                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#movementModal" data-type="EXPENSE">
                        <i class="bi bi-dash-circle-fill me-1"></i>Egreso
                    </button>
                    <a href="{{ route('cash.close') }}" class="btn btn-danger btn-sm fw-bold">
                        <i class="bi bi-lock-fill me-1"></i>Corte de Caja
                    </a>
                    <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-cash-stack me-1"></i>Ir al POS
                    </a>
                </div>
            </div>

            <!-- Tarjetas resumen -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-success fs-5 fw-bold">₲ {{ number_format($byMethod['CASH'], 0, ',', '.') }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-cash me-1"></i>Efectivo</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-primary fs-5 fw-bold">₲ {{ number_format($byMethod['CARD'], 0, ',', '.') }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-credit-card me-1"></i>Tarjeta</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-info fs-5 fw-bold">₲ {{ number_format($byMethod['TRANSFER'], 0, ',', '.') }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-bank me-1"></i>Transferencia</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-warning fs-5 fw-bold">₲ {{ number_format($byMethod['CHEQUE'], 0, ',', '.') }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-journal-check me-1"></i>Cheque</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-success fs-5 fw-bold">₲ {{ number_format($incomes, 0, ',', '.') }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-plus-circle me-1"></i>Ingresos</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-danger fs-5 fw-bold">₲ {{ number_format($expenses, 0, ',', '.') }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-dash-circle me-1"></i>Egresos</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100 border-success" style="border-radius:12px; border:2px solid #28a745 !important;">
                        <div class="text-success fs-5 fw-bold">₲ {{ number_format($expected, 0, ',', '.') }}</div>
                        <div class="text-muted small mt-1"><i class="bi bi-wallet2 me-1"></i>Esperado en caja</div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Ventas de la sesión -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3" style="border-radius:15px 15px 0 0;">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Ventas de esta sesión</h6>
                            <span class="badge bg-primary">{{ $register->sales->count() }}</span>
                        </div>
                        <div class="card-body p-0">
                            @if($register->sales->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-3"></i>
                                <p class="mt-2 mb-0">Aún no hay ventas en esta sesión</p>
                            </div>
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
                                        @foreach($register->sales as $sale)
                                        <tr>
                                            <td class="ps-3">
                                                <a href="{{ route('sales.show', $sale) }}" class="text-decoration-none fw-semibold">
                                                    {{ $sale->sale_number }}
                                                </a>
                                            </td>
                                            <td class="text-muted small">{{ $sale->customer_name ?: 'Consumidor final' }}</td>
                                            <td>
                                                @php $pm = $sale->payment_method; @endphp
                                                <span class="badge {{ $pm==='CASH'?'bg-success':($pm==='CARD'?'bg-primary':($pm==='TRANSFER'?'bg-info':'bg-secondary')) }}">
                                                    {{ match($pm){ 'CASH'=>'Efectivo','CARD'=>'Tarjeta','TRANSFER'=>'Transfer.','CHEQUE'=>'Cheque',default=>$pm } }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">₲ {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                            <td class="text-end pe-3 text-muted small">
                                                {{ $sale->sale_date->format('H:i') }}
                                                @if($sale->status === 'PENDING')
                                                <br><span class="badge bg-warning text-dark" style="font-size:10px;">Pendiente</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="ps-3 fw-bold">Total ventas</td>
                                            <td class="text-end fw-bold text-success">₲ {{ number_format($register->sales->sum('total_amount'), 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Movimientos manuales -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="card-header bg-white py-3" style="border-radius:15px 15px 0 0;">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-left-right me-2 text-warning"></i>Movimientos manuales</h6>
                        </div>
                        <div class="card-body p-0">
                            @if($register->movements->isEmpty())
                            <div class="text-center py-4 text-muted small">
                                <i class="bi bi-inbox fs-4"></i>
                                <p class="mt-1 mb-0">Sin movimientos</p>
                            </div>
                            @else
                            <div style="max-height:340px; overflow-y:auto;">
                                @foreach($register->movements as $mov)
                                <div class="d-flex justify-content-between align-items-start px-3 py-2 border-bottom">
                                    <div>
                                        <span class="badge {{ $mov->type==='INCOME'?'bg-success':($mov->type==='EXPENSE'?'bg-danger':'bg-warning text-dark') }} me-1">
                                            {{ $mov->getTypeLabel() }}
                                        </span>
                                        <div class="text-muted small mt-1">{{ $mov->description }}</div>
                                        <div class="text-muted" style="font-size:11px;">{{ $mov->created_at->format('H:i') }}</div>
                                    </div>
                                    <span class="fw-bold {{ $mov->type==='INCOME'?'text-success':'text-danger' }}">
                                        {{ $mov->type==='INCOME'?'+':'-' }} ₲ {{ number_format($mov->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ingreso / Egreso -->
    <div class="modal fade" id="movementModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:15px;">
                <form method="POST" action="{{ route('cash.movement') }}">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="movementModalTitle">Registrar Movimiento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="type" id="movementType" value="INCOME">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo</label>
                            <div class="d-flex gap-2">
                                <button type="button" id="btnIncome" class="btn btn-success flex-fill" onclick="setType('INCOME')">
                                    <i class="bi bi-plus-circle me-1"></i>Ingreso
                                </button>
                                <button type="button" id="btnExpense" class="btn btn-outline-danger flex-fill" onclick="setType('EXPENSE')">
                                    <i class="bi bi-dash-circle me-1"></i>Egreso
                                </button>
                                <button type="button" id="btnRefund" class="btn btn-outline-warning flex-fill" onclick="setType('REFUND')">
                                    <i class="bi bi-arrow-return-left me-1"></i>Devolución
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Monto <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₲</span>
                                <input type="number" name="amount" class="form-control" min="1" step="1" placeholder="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                            <input type="text" name="description" class="form-control" placeholder="Motivo del movimiento..." required maxlength="500">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success fw-bold" id="movementSubmitBtn">
                            <i class="bi bi-check-circle me-1"></i>Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setType(type) {
            document.getElementById('movementType').value = type;
            const labels = { INCOME: 'Ingreso', EXPENSE: 'Egreso', REFUND: 'Devolución' };
            document.getElementById('movementModalTitle').textContent = 'Registrar ' + labels[type];
            document.getElementById('btnIncome').className  = type === 'INCOME'  ? 'btn btn-success flex-fill'           : 'btn btn-outline-success flex-fill';
            document.getElementById('btnExpense').className = type === 'EXPENSE' ? 'btn btn-danger flex-fill'            : 'btn btn-outline-danger flex-fill';
            document.getElementById('btnRefund').className  = type === 'REFUND'  ? 'btn btn-warning flex-fill text-dark' : 'btn btn-outline-warning flex-fill';
            document.getElementById('movementSubmitBtn').className = type === 'EXPENSE' || type === 'REFUND'
                ? 'btn btn-danger fw-bold' : 'btn btn-success fw-bold';
        }

        document.getElementById('movementModal').addEventListener('show.bs.modal', function(e) {
            const type = e.relatedTarget ? e.relatedTarget.getAttribute('data-type') : 'INCOME';
            setType(type || 'INCOME');
        });
    </script>
</x-app-layout>
