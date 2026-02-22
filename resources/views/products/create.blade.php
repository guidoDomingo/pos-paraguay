<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-plus-circle me-2"></i>{{ __('Nuevo Producto') }}
            </h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-box-seam me-2"></i>
                                Información del Producto
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Información básica -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Código del Producto <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                                   id="code" name="code" value="{{ old('code') }}" 
                                                   placeholder="Ej: PROD001">
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode" class="form-label">Código de Barras</label>
                                            <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                                   id="barcode" name="barcode" value="{{ old('barcode') }}" 
                                                   placeholder="Código de barras">
                                            @error('barcode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" 
                                                   placeholder="Nombre del producto">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Categoría</label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                                <option value="">Seleccionar categoría</option>
                                                @foreach($categories ?? [] as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Descripción detallada del producto">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Imagen del Producto -->
                                <div class="mb-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-muted mb-3">
                                                <i class="bi bi-image me-2"></i>Imagen del Producto
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col-lg-8">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <label for="image" class="form-label fw-medium">Seleccionar Imagen</label>
                                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                                           id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp">
                                                    <div class="form-text mt-2">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Formatos: JPG, PNG, WebP • Tamaño máximo: 5MB
                                                    </div>
                                                    @error('image')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="image-preview-container d-none" id="imagePreviewContainer">
                                                        <label class="form-label fw-medium">Vista Previa</label>
                                                        <div class="border rounded-3 p-3 text-center bg-light position-relative" style="min-height: 120px;">
                                                            <img id="imagePreview" src="" alt="Vista previa" 
                                                                 class="img-fluid rounded-2 shadow-sm" style="max-height: 100px;">
                                                        </div>
                                                    </div>
                                                    <div class="image-placeholder" id="imagePlaceholder">
                                                        <label class="form-label fw-medium">Vista Previa</label>
                                                        <div class="border rounded-3 p-3 text-center bg-light d-flex flex-column justify-content-center align-items-center" style="min-height: 120px;">
                                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                            <small class="text-muted mt-2">No hay imagen seleccionada</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Precios -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-currency-exchange me-2"></i>Información de Precios</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="cost_price" class="form-label">Precio de Costo</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₲</span>
                                                        <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                                               id="cost_price" name="cost_price" value="{{ old('cost_price') }}" 
                                                               min="0" step="0.01" placeholder="0.00">
                                                    </div>
                                                    @error('cost_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="sale_price" class="form-label">Precio de Venta <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₲</span>
                                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" 
                                                               id="sale_price" name="sale_price" value="{{ old('sale_price') }}" 
                                                               min="0" step="0.01" placeholder="0.00">
                                                    </div>
                                                    @error('sale_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="wholesale_price" class="form-label">Precio Mayorista</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₲</span>
                                                        <input type="number" class="form-control @error('wholesale_price') is-invalid @enderror" 
                                                               id="wholesale_price" name="wholesale_price" value="{{ old('wholesale_price') }}" 
                                                               min="0" step="0.01" placeholder="0.00">
                                                    </div>
                                                    @error('wholesale_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="iva_type" class="form-label">Tipo de IVA</label>
                                                    <select class="form-select @error('iva_type') is-invalid @enderror" id="iva_type" name="iva_type">
                                                        <option value="IVA_10" {{ old('iva_type', 'IVA_10') == 'IVA_10' ? 'selected' : '' }}>IVA 10%</option>
                                                        <option value="IVA_5" {{ old('iva_type') == 'IVA_5' ? 'selected' : '' }}>IVA 5%</option>
                                                        <option value="EXENTO" {{ old('iva_type') == 'EXENTO' ? 'selected' : '' }}>Exento</option>
                                                    </select>
                                                    @error('iva_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="unit" class="form-label">Unidad de Medida</label>
                                                    <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit">
                                                        <option value="UNIDAD" {{ old('unit', 'UNIDAD') == 'UNIDAD' ? 'selected' : '' }}>Unidad</option>
                                                        <option value="KG" {{ old('unit') == 'KG' ? 'selected' : '' }}>Kilogramo</option>
                                                        <option value="LT" {{ old('unit') == 'LT' ? 'selected' : '' }}>Litro</option>
                                                        <option value="MT" {{ old('unit') == 'MT' ? 'selected' : '' }}>Metro</option>
                                                        <option value="CAJA" {{ old('unit') == 'CAJA' ? 'selected' : '' }}>Caja</option>
                                                        <option value="PAQUETE" {{ old('unit') == 'PAQUETE' ? 'selected' : '' }}>Paquete</option>
                                                    </select>
                                                    @error('unit')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Inventario -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-boxes me-2"></i>Control de Inventario</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="track_stock" name="track_stock" 
                                                           value="1" {{ old('track_stock') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="track_stock">
                                                        Controlar stock de este producto
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="stockFields" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="stock" class="form-label">Stock Inicial</label>
                                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                                               id="stock" name="stock" value="{{ old('stock', 0) }}" 
                                                               min="0" step="1">
                                                        @error('stock')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="min_stock" class="form-label">Stock Mínimo</label>
                                                        <input type="number" class="form-control @error('min_stock') is-invalid @enderror" 
                                                               id="min_stock" name="min_stock" value="{{ old('min_stock', 0) }}" 
                                                               min="0" step="1">
                                                        @error('min_stock')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="unit" class="form-label">Unidad de Medida</label>
                                                        <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit">
                                                            <option value="unidad" {{ old('unit', 'unidad') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogramo</option>
                                                            <option value="litro" {{ old('unit') == 'litro' ? 'selected' : '' }}>Litro</option>
                                                            <option value="metro" {{ old('unit') == 'metro' ? 'selected' : '' }}>Metro</option>
                                                            <option value="caja" {{ old('unit') == 'caja' ? 'selected' : '' }}>Caja</option>
                                                            <option value="paquete" {{ old('unit') == 'paquete' ? 'selected' : '' }}>Paquete</option>
                                                        </select>
                                                        @error('unit')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Producto activo
                                        </label>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Crear Producto
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border: 1px solid #e3e6f0;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transform: translateY(-2px);
        }
        
        .image-preview-container img,
        #currentImageContainer img {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .image-preview-container img:hover,
        #currentImageContainer img:hover {
            border-color: var(--bs-primary);
            transform: scale(1.02);
        }
        
        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        @media (max-width: 768px) {
            .col-lg-8, .col-lg-4 {
                margin-bottom: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .row.g-4 {
                margin: 0;
            }
            
            .col-lg-8, .col-lg-4 {
                padding: 0.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trackStockCheckbox = document.getElementById('track_stock');
            const stockFields = document.getElementById('stockFields');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const imagePlaceholder = document.getElementById('imagePlaceholder');
            
            function toggleStockFields() {
                if (trackStockCheckbox.checked) {
                    stockFields.style.display = 'block';
                } else {
                    stockFields.style.display = 'none';
                }
            }
            
            // Vista previa de imagen
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                
                if (file) {
                    // Validar tamaño (5MB máximo)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('La imagen no puede ser mayor a 5MB');
                        this.value = '';
                        showPlaceholder();
                        return;
                    }
                    
                    // Validar tipo
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Solo se permiten imágenes JPG, PNG y WebP');
                        this.value = '';
                        showPlaceholder();
                        return;
                    }
                    
                    // Mostrar vista previa
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        showPreview();
                    };
                    reader.readAsDataURL(file);
                } else {
                    showPlaceholder();
                }
            });
            
            function showPreview() {
                if (imagePreviewContainer) {
                    imagePreviewContainer.classList.remove('d-none');
                }
                if (imagePlaceholder) {
                    imagePlaceholder.classList.add('d-none');
                }
            }
            
            function showPlaceholder() {
                if (imagePreviewContainer) {
                    imagePreviewContainer.classList.add('d-none');
                }
                if (imagePlaceholder) {
                    imagePlaceholder.classList.remove('d-none');
                }
            }
            
            // Estado inicial
            showPlaceholder();
            
            trackStockCheckbox.addEventListener('change', toggleStockFields);
            toggleStockFields(); // Initial state
        });
    </script>
</x-app-layout>