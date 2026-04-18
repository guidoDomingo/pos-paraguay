<x-app-layout>
    <div class="py-4">
        <div class="container-fluid px-4" style="max-width:700px;">

            <div class="mb-4">
                <a href="{{ route('admin.users.index') }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Volver a Usuarios
                </a>
                <h4 class="fw-bold mt-2 mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Editar Usuario</h4>
            </div>

            @foreach(['success','error'] as $type)
            @if(session($type))
            <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible fade show mb-3">
                {{ session($type) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @endforeach

            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required autofocus>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Código de empleado</label>
                                <input type="text" name="employee_code" class="form-control @error('employee_code') is-invalid @enderror"
                                    value="{{ old('employee_code', $user->employee_code) }}" maxlength="10">
                                @error('employee_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nueva contraseña <span class="text-muted fw-normal">(opcional)</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
                                <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar rol...</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" maxlength="20">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            @if($user->id !== auth()->id())
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                        {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">Usuario activo</label>
                                </div>
                            </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="bi bi-check-circle me-1"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
