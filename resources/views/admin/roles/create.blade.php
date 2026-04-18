<x-app-layout>
    <div class="py-4">
        <div class="container-fluid px-4" style="max-width:800px;">

            <div class="mb-4">
                <a href="{{ route('admin.roles.index') }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Volver a Roles
                </a>
                <h4 class="fw-bold mt-2 mb-0"><i class="bi bi-shield-plus me-2 text-primary"></i>Nuevo Rol</h4>
            </div>

            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf

                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Información del rol</h6>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Nombre interno <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="ej: contador" pattern="[a-z_]+" required>
                                <div class="form-text">Solo minúsculas y guiones bajos</div>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Nombre para mostrar <span class="text-danger">*</span></label>
                                <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror"
                                    value="{{ old('display_name') }}" placeholder="ej: Contador" required>
                                @error('display_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                                    value="{{ old('description') }}" placeholder="¿Para qué es este rol?" maxlength="500">
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Permisos</h6>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleAll(this)">
                                Seleccionar todos
                            </button>
                        </div>

                        @foreach($groups as $groupName => $permissions)
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-semibold small text-uppercase text-muted" style="letter-spacing:.5px;">{{ $groupName }}</span>
                                <div class="flex-fill border-top"></div>
                                <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-muted group-toggle"
                                    data-group="{{ $groupName }}" onclick="toggleGroup('{{ $groupName }}', this)">
                                    Sel. grupo
                                </button>
                            </div>
                            <div class="row g-2 ps-2">
                                @foreach($permissions as $perm)
                                <div class="col-md-4 col-6">
                                    <div class="form-check">
                                        <input class="form-check-input perm-check group-{{ Str::slug($groupName) }}" type="checkbox"
                                            name="permissions[]" value="{{ $perm }}" id="perm_{{ $perm }}"
                                            {{ in_array($perm, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="perm_{{ $perm }}">
                                            <code style="font-size:11px;">{{ $perm }}</code>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="bi bi-check-circle me-1"></i>Crear Rol
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAll(btn) {
            const checks = document.querySelectorAll('.perm-check');
            const anyUnchecked = [...checks].some(c => !c.checked);
            checks.forEach(c => c.checked = anyUnchecked);
            btn.textContent = anyUnchecked ? 'Deseleccionar todos' : 'Seleccionar todos';
        }

        function toggleGroup(groupName, btn) {
            const slug = groupName.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
            // Find all checkboxes in this group using the data attribute nearby
            const allGroupBtns = document.querySelectorAll('.group-toggle');
            let groupChecks = [];

            // Use the surrounding container
            const container = btn.closest('.mb-4');
            if (container) {
                groupChecks = [...container.querySelectorAll('.perm-check')];
            }

            const anyUnchecked = groupChecks.some(c => !c.checked);
            groupChecks.forEach(c => c.checked = anyUnchecked);
        }
    </script>
</x-app-layout>
