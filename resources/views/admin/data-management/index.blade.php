<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🧹 Gestión de Datos del Sistema
        </h2>
    </x-slot>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="text-muted">
                                Limpiar y reiniciar datos transaccionales del sistema
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l5 5l10 -10"></path>
                            </svg>
                        </div>
                        <div>{{ session('success') }}</div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 9v4"></path>
                                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636-2.87L12.637 3.59a1.914 1.914 0 0 0-3.274 0z"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                        </div>
                        <div>{{ session('error') }}</div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif

            <!-- Estado Actual del Sistema -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">📊 Estado Actual - {{ $company->name }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-primary text-white avatar">💰</span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        {{ number_format($stats['sales_count']) }} Ventas
                                                    </div>
                                                    <div class="text-muted">
                                                        Total: ${{ number_format($stats['total_sales_amount'], 0) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-success text-white avatar">📄</span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        {{ number_format($stats['invoices_count']) }} Facturas
                                                    </div>
                                                    <div class="text-muted">Electrónicas</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-info text-white avatar">📦</span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        {{ number_format($stats['products_count']) }} Productos
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ number_format($stats['stock_movements_count']) }} movimientos
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-warning text-white avatar">💾</span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        {{ $stats['database_size'] }} MB
                                                    </div>
                                                    <div class="text-muted">
                                                        Última venta: {{ $stats['last_sale'] ?? 'Ninguna' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opciones de Limpieza -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">🗑️ Opciones de Limpieza</h3>
                        </div>
                        
                        <form action="{{ route('admin.data-management.clean') }}" method="POST" id="cleanForm">
                            @csrf
                            <div class="card-body">
                                
                                <!-- Tipo de Limpieza -->
                                <div class="mb-3">
                                    <label class="form-label">Tipo de limpieza</label>
                                    <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="clean_type" value="transactions" class="form-selectgroup-input" checked>
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong>Solo Transacciones</strong>
                                                    <div class="text-muted">Elimina ventas, facturas, movimientos de stock. Mantiene productos, clientes, configuraciones.</div>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="clean_type" value="all_except_products" class="form-selectgroup-input">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong>Transacciones + Historial de Clientes</strong>
                                                    <div class="text-muted">Incluye transacciones + resetea historial de compras de clientes.</div>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="clean_type" value="custom" class="form-selectgroup-input">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong>Limpieza Personalizada</strong>
                                                    <div class="text-muted">Permite configurar opciones específicas abajo.</div>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="clean_type" value="total_cleanup" class="form-selectgroup-input">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3 border-danger">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong class="text-danger">🔥 LIMPIEZA TOTAL</strong>
                                                    <div class="text-muted">SOLO mantiene usuarios y roles. Elimina TODO lo demás.</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Opciones Personalizadas -->
                                <div id="customOptions" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="preserve_products" id="preserve_products" checked>
                                                <label class="form-check-label" for="preserve_products">
                                                    Preservar productos y stock actual
                                                </label>
                                                <small class="form-hint">Mantiene la lista de productos y su stock actual</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="preserve_customers" id="preserve_customers" checked>
                                                <label class="form-check-label" for="preserve_customers">
                                                    Preservar clientes
                                                </label>
                                                <small class="form-hint">Mantiene información de clientes pero resetea su historial</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3" id="stockResetOption" style="display: none;">
                                        <label class="form-label">Resetear stock a valor específico</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="reset_stock" min="0" max="999999" placeholder="Ej: 0, 100">
                                            <span class="input-group-text">unidades</span>
                                        </div>
                                        <small class="form-hint">Deja vacío para mantener stock actual</small>
                                    </div>
                                </div>

                                <!-- Confirmación -->
                                <div class="mt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="confirmation" id="confirmation" required>
                                        <label class="form-check-label text-danger" for="confirmation">
                                            <strong>⚠️ Confirmo que entiendo que esta acción eliminará datos de forma permanente</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-danger" id="cleanButton" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 7l16 0"></path>
                                        <path d="M10 11l0 6"></path>
                                        <path d="M14 11l0 6"></path>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                    </svg>
                                    Ejecutar Limpieza
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">💡 Información Importante</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h4>✅ Datos que SE MANTIENEN (Limpieza Normal):</h4>
                                <ul class="list list-timeline list-timeline-simple">
                                    <li>👥 Usuarios y roles</li>
                                    <li>🏢 Información de la empresa</li>
                                    <li>🏪 Almacenes</li>
                                    <li>🏷️ Categorías</li>
                                    <li>📋 Productos (configurable)</li>
                                    <li>👤 Clientes (configurable)</li>
                                    <li>🏭 Proveedores</li>
                                    <li>📄 <strong>Timbres fiscales (CRÍTICO)</strong></li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <h4 class="text-danger">🔥 LIMPIEZA TOTAL:</h4>
                                <ul class="list list-timeline list-timeline-simple">
                                    <li class="text-success">✅ SOLO usuarios y roles</li>
                                    <li class="text-danger">❌ TODO lo demás se elimina</li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <h4>❌ Datos que SE ELIMINAN:</h4>
                                <ul class="list list-timeline list-timeline-simple">
                                    <li>💰 Todas las ventas</li>
                                    <li>📄 Todas las facturas</li>
                                    <li>📦 Movimientos de inventario</li>
                                    <li>💵 Registros de pagos</li>
                                    <li>🏧 Sesiones de caja</li>
                                    <li>📊 Reportes históricos</li>
                                </ul>
                            </div>

                            <div class="alert alert-info">
                                <h4 class="alert-title">💡 Recomendación</h4>
                                <div class="text-muted">
                                    Después de la limpieza, ejecuta:<br>
                                    <code>php artisan db:seed</code><br>
                                    para restaurar datos de prueba.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cleanTypeInputs = document.querySelectorAll('input[name="clean_type"]');
    const customOptions = document.getElementById('customOptions');
    const preserveProducts = document.getElementById('preserve_products');
    const stockResetOption = document.getElementById('stockResetOption');
    const confirmation = document.getElementById('confirmation');
    const cleanButton = document.getElementById('cleanButton');

    // Toggle custom options
    cleanTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value === 'custom') {
                customOptions.style.display = 'block';
            } else {
                customOptions.style.display = 'none';
            }
            
            // Cambiar el texto del botón dependiendo del tipo
            if (this.value === 'total_cleanup') {
                cleanButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg> 🔥 LIMPIEZA TOTAL';
                cleanButton.className = 'btn btn-danger';
            } else {
                cleanButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg> Ejecutar Limpieza';
                cleanButton.className = 'btn btn-danger';
            }
        });
    });

    // Toggle stock reset option
    preserveProducts.addEventListener('change', function() {
        if (!this.checked) {
            stockResetOption.style.display = 'block';
        } else {
            stockResetOption.style.display = 'none';
        }
    });

    // Enable/disable clean button
    confirmation.addEventListener('change', function() {
        cleanButton.disabled = !this.checked;
    });

    // Confirm before submit
    document.getElementById('cleanForm').addEventListener('submit', function(e) {
        const selectedType = document.querySelector('input[name="clean_type"]:checked').value;
        let confirmMessage = '⚠️ ¿ESTÁS SEGURO? Esta acción eliminará datos de forma permanente.';
        
        if (selectedType === 'total_cleanup') {
            confirmMessage = '🔥 ¿ESTÁS ABSOLUTAMENTE SEGURO? Esta acción eliminará TODO excepto usuarios y roles. NO SE PUEDE DESHACER.';
        }
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });
});
</script>
</x-app-layout>