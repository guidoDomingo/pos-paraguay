<x-app-layout>
    <div class="py-4">
        <div class="container-fluid px-4" style="max-width:700px;">

            <div class="mb-4 d-flex justify-content-between align-items-start">
                <div>
                    <a href="{{ route('admin.users.index') }}" class="text-muted text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i>Volver a Usuarios
                    </a>
                    <h4 class="fw-bold mt-2 mb-0"><i class="bi bi-person-badge-fill me-2 text-primary"></i>Perfil de Usuario</h4>
                </div>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm mt-3">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
            </div>

            <div class="card border-0 shadow-sm mb-3" style="border-radius:15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                            style="width:56px;height:56px;background:{{ ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444'][crc32($user->name)%6] }};font-size:22px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $user->name }}</h5>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>
                        <div class="ms-auto">
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3">
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Rol</div>
                            <div class="fw-semibold">
                                @if($user->role)
                                <span class="badge rounded-pill" style="background:#6366f1;">{{ $user->role->display_name }}</span>
                                @else
                                <span class="text-muted">Sin rol asignado</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Código de empleado</div>
                            <div class="fw-semibold">{{ $user->employee_code ?: '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Teléfono</div>
                            <div class="fw-semibold">{{ $user->phone ?: '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Miembro desde</div>
                            <div class="fw-semibold">{{ $user->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-receipt me-2 text-primary"></i>Actividad de ventas</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded-3">
                                <div class="fs-3 fw-bold text-primary">{{ $salesCount }}</div>
                                <div class="text-muted small">Ventas realizadas</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-3 bg-light rounded-3">
                                <div class="fs-6 fw-bold text-muted">
                                    @if($lastSale)
                                    {{ $lastSale->sale_date->format('d/m/Y H:i') }}
                                    @else
                                    —
                                    @endif
                                </div>
                                <div class="text-muted small">Última venta</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
