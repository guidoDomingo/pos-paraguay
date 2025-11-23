<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-plus-circle me-2"></i>{{ __('Nueva Categoría') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-tag me-2"></i>
                                Información de la Categoría
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('categories.store') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre de la Categoría <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Ej: Bebidas, Alimentos, Electrónicos..." required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        El nombre debe ser único y descriptivo.
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Describe qué tipo de productos incluye esta categoría...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        La descripción es opcional pero ayuda a identificar la categoría.
                                    </small>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Categoría activa
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Solo las categorías activas aparecerán en los formularios de productos.
                                    </small>
                                </div>

                                <!-- Vista previa -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="bi bi-eye me-2"></i>Vista Previa
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-tag text-white"></i>
                                            </div>
                                            <div>
                                                <strong id="preview-name">Nombre de la categoría</strong>
                                                <br><span class="text-muted" id="preview-description">Descripción de la categoría</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información adicional -->
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Información:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Las categorías te ayudan a organizar tus productos</li>
                                        <li>Puedes filtrar productos por categoría en el módulo de inventario</li>
                                        <li>Una vez creada, podrás asignar productos a esta categoría</li>
                                        <li>Las categorías con productos no se pueden eliminar</li>
                                    </ul>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Crear Categoría
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const descriptionInput = document.getElementById('description');
            const previewName = document.getElementById('preview-name');
            const previewDescription = document.getElementById('preview-description');
            
            function updatePreview() {
                const name = nameInput.value.trim();
                const description = descriptionInput.value.trim();
                
                previewName.textContent = name || 'Nombre de la categoría';
                previewDescription.textContent = description || 'Descripción de la categoría';
            }
            
            nameInput.addEventListener('input', updatePreview);
            descriptionInput.addEventListener('input', updatePreview);
            
            // Actualizar vista previa inicial
            updatePreview();
        });
    </script>
</x-app-layout>