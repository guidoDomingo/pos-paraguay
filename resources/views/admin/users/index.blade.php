<x-app-layout>
    <div class="py-4">
        <div class="container-fluid px-4">

            @foreach(['success','error','warning','info'] as $type)
            @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show mb-3">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @endforeach

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="bi bi-people-fill me-2 text-primary"></i>Usuarios</h4>
                    <p class="text-muted small mb-0">Gestiona los usuarios de tu empresa</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus-fill me-1"></i>Nuevo Usuario
                </a>
            </div>

            <!-- Filtros -->
            <form method="GET" class="row g-2 mb-4">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, email o código..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role_id" class="form-select form-select-sm">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-secondary btn-sm flex-fill">Filtrar</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>

            <!-- Tabla -->
            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Usuario</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Código</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                                style="width:36px;height:36px;background:{{ ['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444'][crc32($user->name)%6] }};font-size:14px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size:14px;">{{ $user->name }}</div>
                                                @if($user->phone)
                                                <div class="text-muted" style="font-size:11px;">{{ $user->phone }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted small">{{ $user->email }}</td>
                                    <td>
                                        @if($user->role)
                                        <span class="badge rounded-pill" style="background:#6366f1;font-size:11px;">{{ $user->role->display_name }}</span>
                                        @else
                                        <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $user->employee_code ?: '—' }}</td>
                                    <td class="text-center">
                                        @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 border-0" title="{{ $user->is_active ? 'Desactivar' : 'Activar' }}">
                                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </button>
                                        </form>
                                        @else
                                        <span class="badge bg-success">Activo</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary btn-sm" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline"
                                            onsubmit="return confirm('¿Eliminar usuario {{ addslashes($user->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-people fs-3 d-block mb-2"></i>
                                        No se encontraron usuarios
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($users->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
