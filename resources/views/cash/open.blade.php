<x-app-layout>
    <div class="py-4">
        <div class="container-fluid px-4">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6">

                    @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="card shadow-lg border-0" style="border-radius:20px; overflow:hidden;">
                        <!-- Header -->
                        <div class="card-header text-white text-center py-4" style="background:linear-gradient(135deg,#28a745,#20c997);">
                            <div style="font-size:3rem; margin-bottom:.5rem;">🏧</div>
                            <h3 class="mb-0 fw-bold">Apertura de Caja</h3>
                            <small class="opacity-75">{{ now()->format('d/m/Y H:i') }}</small>
                        </div>

                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('cash.store') }}">
                                @csrf

                                <!-- Monto inicial -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-cash-stack me-1 text-success"></i>
                                        Monto Inicial en Caja <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text fw-bold">₲</span>
                                        <input type="number" name="opening_amount" class="form-control @error('opening_amount') is-invalid @enderror"
                                               value="{{ old('opening_amount', 0) }}" min="0" step="1000"
                                               placeholder="0" autofocus>
                                        @error('opening_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Ingresa el efectivo físico con el que arranca la caja.</div>
                                </div>

                                <!-- Botones rápidos de monto -->
                                <div class="mb-4">
                                    <label class="form-label text-muted small">Montos comunes:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach([50000, 100000, 200000, 500000] as $amt)
                                        <button type="button" class="btn btn-outline-success btn-sm"
                                                onclick="document.querySelector('[name=opening_amount]').value = {{ $amt }}">
                                            ₲ {{ number_format($amt, 0, ',', '.') }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Notas -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-chat-left-text me-1 text-muted"></i>
                                        Notas de apertura
                                    </label>
                                    <textarea name="opening_notes" class="form-control" rows="2"
                                              placeholder="Observaciones opcionales...">{{ old('opening_notes') }}</textarea>
                                </div>

                                <!-- Info cajero -->
                                <div class="alert alert-light border mb-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-circle text-primary fs-5"></i>
                                        <div>
                                            <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                            <small class="text-muted">Cajero responsable</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg fw-bold py-3">
                                        <i class="bi bi-unlock-fill me-2"></i>Abrir Caja
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('cash.index') }}" class="text-muted small">
                            <i class="bi bi-clock-history me-1"></i>Ver historial de cajas
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
