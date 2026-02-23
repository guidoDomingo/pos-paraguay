<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-plus-circle me-2"></i>{{ __('Nuevo Timbrado Fiscal') }}
            </h2>
            <a href="{{ route('fiscal-stamps.index') }}" class="btn btn-outline-secondary">
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
                                <i class="bi bi-patch-check me-2"></i>
                                Información del Timbrado
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('fiscal-stamps.store') }}">
                                @csrf

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Por favor corrija los siguientes errores:</h6>
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                
                                <!-- Número de Timbrado -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-hash me-2"></i>Datos del Timbrado</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="stamp_number" class="form-label">Número de Timbrado <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('stamp_number') is-invalid @enderror" 
                                                   id="stamp_number" name="stamp_number" value="{{ old('stamp_number') }}" 
                                                   placeholder="Ej: 12345678" required maxlength="20">
                                            @error('stamp_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Ingrese el número de timbrado otorgado por la SET.
                                            </small>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="valid_from" class="form-label">Fecha de Inicio de Vigencia <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control @error('valid_from') is-invalid @enderror" 
                                                           id="valid_from" name="valid_from" value="{{ old('valid_from') }}" required>
                                                    @error('valid_from')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="valid_until" class="form-label">Fecha de Fin de Vigencia <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control @error('valid_until') is-invalid @enderror" 
                                                           id="valid_until" name="valid_until" value="{{ old('valid_until') }}" required>
                                                    @error('valid_until')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Establecimiento y Punto de Venta -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-building me-2"></i>Establecimiento y Punto de Venta</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="establishment" class="form-label">Establecimiento <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('establishment') is-invalid @enderror" 
                                                           id="establishment" name="establishment" value="{{ old('establishment') }}" 
                                                           placeholder="001" required maxlength="3" pattern="[0-9]{3}">
                                                    @error('establishment')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Código de 3 dígitos del establecimiento.
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="point_of_sale" class="form-label">Punto de Venta/Expedición <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('point_of_sale') is-invalid @enderror" 
                                                           id="point_of_sale" name="point_of_sale" value="{{ old('point_of_sale') }}" 
                                                           placeholder="001" required maxlength="3" pattern="[0-9]{3}">
                                                    @error('point_of_sale')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Código de 3 dígitos del punto de expedición.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle me-2"></i>
                                            <strong>Formato de factura:</strong> <span id="preview-format">001-001-0000001</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Numeración de Facturas -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-file-text me-2"></i>Numeración de Facturas</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="current_invoice_number" class="form-label">Número de Factura Inicial <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control @error('current_invoice_number') is-invalid @enderror" 
                                                           id="current_invoice_number" name="current_invoice_number" 
                                                           value="{{ old('current_invoice_number', 0) }}" 
                                                           min="0" required>
                                                    @error('current_invoice_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Normalmente se inicia en 0. Si ya emitió facturas con este timbrado, ingrese el último número usado.
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="max_invoice_number" class="form-label">Número Máximo de Facturas <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control @error('max_invoice_number') is-invalid @enderror" 
                                                           id="max_invoice_number" name="max_invoice_number" 
                                                           value="{{ old('max_invoice_number') }}" 
                                                           min="1" required>
                                                    @error('max_invoice_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Cantidad máxima de facturas permitidas con este timbrado.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="range-info" class="alert alert-secondary">
                                            <i class="bi bi-calculator me-2"></i>
                                            <strong>Rango disponible:</strong> <span id="available-range">Configure los valores para ver el rango</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-toggle-on me-2"></i>Estado</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                <strong>Timbrado activo</strong>
                                            </label>
                                        </div>
                                        <small class="form-text text-muted d-block mt-2">
                                            Solo los timbrados activos y vigentes podrán ser utilizados para generar facturas.
                                        </small>
                                    </div>
                                </div>

                                <!-- Información adicional -->
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Importante:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Verifique que todos los datos coincidan con la documentación de la SET</li>
                                        <li>El número de timbrado debe ser único</li>
                                        <li>Las fechas de vigencia deben ser verificadas cuidadosamente</li>
                                        <li>El sistema alertará cuando el timbrado esté próximo a vencer</li>
                                        <li>No podrá emitir facturas cuando se alcance el número máximo</li>
                                    </ul>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('fiscal-stamps.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Crear Timbrado
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
            const establishmentInput = document.getElementById('establishment');
            const pointOfSaleInput = document.getElementById('point_of_sale');
            const currentInvoiceInput = document.getElementById('current_invoice_number');
            const maxInvoiceInput = document.getElementById('max_invoice_number');
            const previewFormat = document.getElementById('preview-format');
            const availableRange = document.getElementById('available-range');
            
            function padNumber(num, size) {
                return String(num).padStart(size, '0');
            }
            
            function updatePreviewFormat() {
                const establishment = establishmentInput.value.trim() || '001';
                const pointOfSale = pointOfSaleInput.value.trim() || '001';
                const invoiceNumber = padNumber(1, 7);
                
                previewFormat.textContent = `${establishment}-${pointOfSale}-${invoiceNumber}`;
            }
            
            function updateAvailableRange() {
                const current = parseInt(currentInvoiceInput.value) || 0;
                const max = parseInt(maxInvoiceInput.value) || 0;
                
                if (max > 0) {
                    const available = max - current;
                    availableRange.innerHTML = `De <strong>${current + 1}</strong> a <strong>${max}</strong> (${available.toLocaleString()} facturas disponibles)`;
                } else {
                    availableRange.textContent = 'Configure los valores para ver el rango';
                }
            }
            
            establishmentInput.addEventListener('input', function() {
                // Auto-pad with zeros
                if (this.value.length > 0 && this.value.length < 3) {
                    const cursorPosition = this.selectionStart;
                    this.value = padNumber(parseInt(this.value) || 0, 3);
                    this.setSelectionRange(cursorPosition, cursorPosition);
                }
                updatePreviewFormat();
            });
            
            pointOfSaleInput.addEventListener('input', function() {
                // Auto-pad with zeros
                if (this.value.length > 0 && this.value.length < 3) {
                    const cursorPosition = this.selectionStart;
                    this.value = padNumber(parseInt(this.value) || 0, 3);
                    this.setSelectionRange(cursorPosition, cursorPosition);
                }
                updatePreviewFormat();
            });
            
            currentInvoiceInput.addEventListener('input', updateAvailableRange);
            maxInvoiceInput.addEventListener('input', updateAvailableRange);
            
            // Validación de fechas
            const validFromInput = document.getElementById('valid_from');
            const validUntilInput = document.getElementById('valid_until');
            
            validFromInput.addEventListener('change', function() {
                if (validUntilInput.value && this.value > validUntilInput.value) {
                    alert('La fecha de inicio no puede ser posterior a la fecha de fin');
                    this.value = '';
                }
            });
            
            validUntilInput.addEventListener('change', function() {
                if (validFromInput.value && this.value < validFromInput.value) {
                    alert('La fecha de fin no puede ser anterior a la fecha de inicio');
                    this.value = '';
                }
            });
            
            // Actualizar vistas previas iniciales
            updatePreviewFormat();
            updateAvailableRange();
        });
    </script>
</x-app-layout>
