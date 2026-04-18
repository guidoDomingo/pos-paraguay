<x-app-layout>
    <div class="py-4">
        <div class="container-fluid px-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-secondary"></i>Historial de Cajas</h4>
                <a href="{{ route('cash.current') }}" class="btn btn-success btn-sm fw-bold">
                    <i class="bi bi-cash-coin me-1"></i>Caja Actual
                </a>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-body p-0">
                    @if($registers->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2"></i>
                        <p class="mt-2">No hay registros de caja aún.</p>
                        <a href="{{ route('cash.open') }}" class="btn btn-success">Abrir primera caja</a>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Cajero</th>
                                    <th>Apertura</th>
                                    <th>Cierre</th>
                                    <th class="text-end">Monto Inicial</th>
                                    <th class="text-end">Total Ventas</th>
                                    <th class="text-end">Diferencia</th>
                                    <th class="text-center">Estado</th>
                                    <th class="pe-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registers as $reg)
                                <tr>
                                    <td class="ps-4 fw-semibold text-muted">{{ $reg->id }}</td>
                                    <td>{{ $reg->user->name }}</td>
                                    <td class="small">{{ $reg->opened_at->format('d/m/Y H:i') }}</td>
                                    <td class="small text-muted">{{ $reg->closed_at ? $reg->closed_at->format('d/m/Y H:i') : '—' }}</td>
                                    <td class="text-end">₲ {{ number_format($reg->opening_amount, 0, ',', '.') }}</td>
                                    <td class="text-end text-primary fw-semibold">₲ {{ number_format($reg->getTotalSales(), 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        @if($reg->status === 'CLOSED')
                                            @php $diff = $reg->difference_amount; @endphp
                                            <span class="fw-bold {{ $diff == 0 ? 'text-success' : ($diff > 0 ? 'text-warning' : 'text-danger') }}">
                                                {{ $diff >= 0 ? '+' : '' }}₲ {{ number_format($diff, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge py-2 px-3 {{ $reg->status === 'OPEN' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $reg->status === 'OPEN' ? '🟢 Abierta' : '🔒 Cerrada' }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <a href="{{ route('cash.show', $reg) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-3">
                        {{ $registers->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
