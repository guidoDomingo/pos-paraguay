<x-app-layout>
    <div class="py-4">
        <div class="container-fluid px-4">

            @foreach(['success','error'] as $type)
            @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show mb-3">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @endforeach

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>Roles</h4>
                    <p class="text-muted small mb-0">Define los permisos de cada rol</p>
                </div>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle-fill me-1"></i>Nuevo Rol
                </a>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nombre interno</th>
                                    <th>Display</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Permisos</th>
                                    <th class="text-center">Usuarios</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td class="ps-4">
                                        <code class="bg-light px-2 py-1 rounded" style="font-size:12px;">{{ $role->name }}</code>
                                    </td>
                                    <td class="fw-semibold">{{ $role->display_name }}</td>
                                    <td class="text-muted small">{{ $role->description ?: '—' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">{{ count($role->permissions ?? []) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $role->users_count > 0 ? 'bg-info' : 'bg-light text-dark' }} rounded-pill">
                                            {{ $role->users_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $role->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $role->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-primary btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($role->name !== 'admin')
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline"
                                            onsubmit="return confirm('¿Eliminar rol {{ addslashes($role->display_name) }}?')">
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
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-shield-x fs-3 d-block mb-2"></i>
                                        No hay roles creados
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
