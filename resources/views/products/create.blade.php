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

                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Descripción detallada del producto">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Precios -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-currency-exchange me-2"></i>Información de Precios</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
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
                                                    <label for="tax_rate" class="form-label">IVA (%)</label>
                                                    <select class="form-select @error('tax_rate') is-invalid @enderror" id="tax_rate" name="tax_rate">
                                                        <option value="10" {{ old('tax_rate', 10) == 10 ? 'selected' : '' }}>10%</option>
                                                        <option value="5" {{ old('tax_rate') == 5 ? 'selected' : '' }}>5%</option>
                                                        <option value="0" {{ old('tax_rate') == 0 ? 'selected' : '' }}>Exento</option>
                                                    </select>
                                                    @error('tax_rate')
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trackStockCheckbox = document.getElementById('track_stock');
            const stockFields = document.getElementById('stockFields');
            
            function toggleStockFields() {
                if (trackStockCheckbox.checked) {
                    stockFields.style.display = 'block';
                } else {
                    stockFields.style.display = 'none';
                }
            }
            
            trackStockCheckbox.addEventListener('change', toggleStockFields);
            toggleStockFields(); // Initial state
        });
    </script>
</x-app-layout>