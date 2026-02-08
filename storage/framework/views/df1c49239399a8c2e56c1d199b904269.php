

<?php $__env->startSection('title', 'Configuración de Facturación'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-gear-fill me-2"></i>
                    Configuración de Facturación
                </h1>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>¡Error!</strong> Por favor corrige los siguientes errores:
                    <ul class="mt-2 mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('settings.invoice.update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="row">
                    <!-- Información de la Empresa -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-building me-2"></i>
                                    Información de la Empresa
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Nombre de la Empresa *</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" 
                                           value="<?php echo e(old('company_name', $settings->company_name)); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="company_ruc" class="form-label">RUC</label>
                                    <input type="text" class="form-control" id="company_ruc" name="company_ruc" 
                                           value="<?php echo e(old('company_ruc', $settings->company_ruc)); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_address" class="form-label">Dirección</label>
                                    <textarea class="form-control" id="company_address" name="company_address" rows="3"><?php echo e(old('company_address', $settings->company_address)); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="company_phone" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="company_phone" name="company_phone" 
                                           value="<?php echo e(old('company_phone', $settings->company_phone)); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="company_email" name="company_email" 
                                           value="<?php echo e(old('company_email', $settings->company_email)); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="company_logo" class="form-label">Logo de la Empresa</label>
                                    <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
                                    <?php if($settings->company_logo): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo e(asset('storage/' . $settings->company_logo)); ?>" alt="Logo actual" style="max-height: 80px;">
                                            <p class="text-muted small mt-1">Logo actual</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración de Documentos -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    Configuración de Facturas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="invoice_prefix" class="form-label">Prefijo *</label>
                                            <input type="text" class="form-control" id="invoice_prefix" name="invoice_prefix" 
                                                   value="<?php echo e(old('invoice_prefix', $settings->invoice_prefix)); ?>" required maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="invoice_suffix" class="form-label">Sufijo</label>
                                            <input type="text" class="form-control" id="invoice_suffix" name="invoice_suffix" 
                                                   value="<?php echo e(old('invoice_suffix', $settings->invoice_suffix)); ?>" maxlength="10">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="invoice_counter" class="form-label">Próximo Número *</label>
                                    <input type="number" class="form-control" id="invoice_counter" name="invoice_counter" 
                                           value="<?php echo e(old('invoice_counter', $settings->invoice_counter)); ?>" required min="1">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="invoice_auto_increment" name="invoice_auto_increment" 
                                               <?php echo e($settings->invoice_auto_increment ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="invoice_auto_increment">
                                            Auto-incrementar número de factura
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-receipt me-2"></i>
                                    Configuración de Tickets
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ticket_prefix" class="form-label">Prefijo *</label>
                                            <input type="text" class="form-control" id="ticket_prefix" name="ticket_prefix" 
                                                   value="<?php echo e(old('ticket_prefix', $settings->ticket_prefix)); ?>" required maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ticket_suffix" class="form-label">Sufijo</label>
                                            <input type="text" class="form-control" id="ticket_suffix" name="ticket_suffix" 
                                                   value="<?php echo e(old('ticket_suffix', $settings->ticket_suffix)); ?>" maxlength="10">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="ticket_counter" class="form-label">Próximo Número *</label>
                                    <input type="number" class="form-control" id="ticket_counter" name="ticket_counter" 
                                           value="<?php echo e(old('ticket_counter', $settings->ticket_counter)); ?>" required min="1">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="ticket_auto_increment" name="ticket_auto_increment" 
                                               <?php echo e($settings->ticket_auto_increment ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="ticket_auto_increment">
                                            Auto-incrementar número de ticket
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Configuración de Impresión -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">
                                    <i class="bi bi-printer me-2"></i>
                                    Configuración de Impresión
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="paper_size" class="form-label">Tamaño de Papel *</label>
                                    <select class="form-select" id="paper_size" name="paper_size" required>
                                        <option value="A4" <?php echo e($settings->paper_size === 'A4' ? 'selected' : ''); ?>>A4</option>
                                        <option value="Letter" <?php echo e($settings->paper_size === 'Letter' ? 'selected' : ''); ?>>Carta</option>
                                        <option value="Ticket" <?php echo e($settings->paper_size === 'Ticket' ? 'selected' : ''); ?>>Ticket (80mm)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="orientation" class="form-label">Orientación *</label>
                                    <select class="form-select" id="orientation" name="orientation" required>
                                        <option value="portrait" <?php echo e($settings->orientation === 'portrait' ? 'selected' : ''); ?>>Vertical</option>
                                        <option value="landscape" <?php echo e($settings->orientation === 'landscape' ? 'selected' : ''); ?>>Horizontal</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="default_iva_rate" class="form-label">Tasa IVA por Defecto (%) *</label>
                                    <input type="number" class="form-control" id="default_iva_rate" name="default_iva_rate" 
                                           value="<?php echo e(old('default_iva_rate', $settings->default_iva_rate)); ?>" required min="0" max="100" step="0.01">
                                </div>

                                <!-- Configuración de Impresoras -->
                                <hr class="my-4">
                                <h6 class="text-primary">
                                    <i class="bi bi-printer-fill me-2"></i>
                                    Configuración de Impresoras
                                </h6>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label">Impresoras Disponibles</label>
                                        <button type="button" class="btn btn-sm btn-info" id="refreshPrinters">
                                            <i class="bi bi-arrow-clockwise"></i>
                                            Actualizar Lista
                                        </button>
                                    </div>
                                    <div id="printersLoading" class="text-center py-2 d-none">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        Detectando impresoras...
                                    </div>
                                    <div id="printersList" class="list-group mt-2">
                                        <!-- Las impresoras se cargarán aquí -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="default_printer" class="form-label">Impresora por Defecto</label>
                                            <select class="form-select" id="default_printer" name="default_printer">
                                                <option value="">Seleccionar impresora...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ticket_printer" class="form-label">Impresora para Tickets</label>
                                            <select class="form-select" id="ticket_printer" name="ticket_printer">
                                                <option value="">Usar impresora por defecto</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="invoice_printer" class="form-label">Impresora para Facturas</label>
                                            <select class="form-select" id="invoice_printer" name="invoice_printer">
                                                <option value="">Usar impresora por defecto</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-success btn-sm" id="testPrinter" disabled>
                                                    <i class="bi bi-printer"></i>
                                                    Probar Impresora
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-sm" id="diagnosePrinter" disabled>
                                                    <i class="bi bi-info-circle"></i>
                                                    Diagnóstico
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="auto_print_tickets" name="auto_print_tickets" 
                                                   <?php echo e($settings->auto_print_tickets ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="auto_print_tickets">
                                                Auto-imprimir tickets
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="auto_print_invoices" name="auto_print_invoices" 
                                                   <?php echo e($settings->auto_print_invoices ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="auto_print_invoices">
                                                Auto-imprimir facturas
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Textos Personalizados -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-textarea-t me-2"></i>
                                    Textos Personalizados
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="footer_text" class="form-label">Texto del Pie de Página</label>
                                    <textarea class="form-control" id="footer_text" name="footer_text" rows="3" 
                                              placeholder="Texto que aparecerá al final de los documentos"><?php echo e(old('footer_text', $settings->footer_text)); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="terms_conditions" class="form-label">Términos y Condiciones</label>
                                    <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="4" 
                                              placeholder="Términos y condiciones de la venta"><?php echo e(old('terms_conditions', $settings->terms_conditions)); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>
                                Guardar Configuración
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Definir función global para probar impresora
window.testPrinter = async function(printerName) {
    try {
        const response = await fetch('<?php echo e(route("printers.test")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ printer_name: printerName })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('✅ ' + data.message);
        } else {
            showError('❌ ' + data.message);
        }
    } catch (error) {
        showError('❌ Error al probar impresora: ' + error.message);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const currentSettings = {
        default_printer: '<?php echo e($settings->default_printer); ?>',
        ticket_printer: '<?php echo e($settings->ticket_printer); ?>',
        invoice_printer: '<?php echo e($settings->invoice_printer); ?>'
    };
    
    // Cargar impresoras al cargar la página
    loadPrinters();
    
    // Evento para actualizar lista de impresoras
    document.getElementById('refreshPrinters').addEventListener('click', function() {
        loadPrinters();
    });
    
    // Evento para probar impresora seleccionada
    document.getElementById('testPrinter').addEventListener('click', function() {
        const printerName = document.getElementById('default_printer').value;
        if (printerName) {
            window.testPrinter(printerName);
        }
    });
    
    // Evento para diagnóstico de impresora
    document.getElementById('diagnosePrinter').addEventListener('click', function() {
        const printerName = document.getElementById('default_printer').value;
        if (printerName) {
            diagnosePrinter(printerName);
        }
    });
    
    // Habilitar botones cuando se selecciona una impresora
    document.getElementById('default_printer').addEventListener('change', function() {
        const hasSelection = !!this.value;
        document.getElementById('testPrinter').disabled = !hasSelection;
        document.getElementById('diagnosePrinter').disabled = !hasSelection;
    });
    
    async function loadPrinters() {
        const loadingEl = document.getElementById('printersLoading');
        const listEl = document.getElementById('printersList');
        const refreshBtn = document.getElementById('refreshPrinters');
        
        // Mostrar loading
        loadingEl.classList.remove('d-none');
        refreshBtn.disabled = true;
        listEl.innerHTML = '';
        
        try {
            const response = await fetch('<?php echo e(route("printers.list")); ?>');
            const data = await response.json();
            
            if (data.success) {
                displayPrinters(data.printers);
                populateSelects(data.printers);
            } else {
                showError('Error al cargar impresoras: ' + data.message);
            }
        } catch (error) {
            showError('Error de conexión: ' + error.message);
        } finally {
            loadingEl.classList.add('d-none');
            refreshBtn.disabled = false;
        }
    }
    
    function displayPrinters(printers) {
        const listEl = document.getElementById('printersList');
        
        if (printers.length === 0) {
            listEl.innerHTML = '<div class="alert alert-warning">No se encontraron impresoras</div>';
            return;
        }
        
        const html = printers.map(printer => {
            const typeIcon = getPrinterIcon(printer.type);
            const typeLabel = getPrinterTypeLabel(printer.type);
            
            return `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center">
                            <i class="bi ${typeIcon} me-2"></i>
                            <strong>${escapeHtml(printer.name)}</strong>
                            <span class="badge bg-secondary ms-2">${typeLabel}</span>
                        </div>
                        <small class="text-muted">Puerto: ${escapeHtml(printer.port)} | Driver: ${escapeHtml(printer.driver)}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.testPrinter('${escapeHtml(printer.name)}')">
                        <i class="bi bi-printer"></i>
                        Probar
                    </button>
                </div>
            `;
        }).join('');
        
        listEl.innerHTML = html;
    }
    
    function populateSelects(printers) {
        const selects = ['default_printer', 'ticket_printer', 'invoice_printer'];
        
        selects.forEach(selectId => {
            const select = document.getElementById(selectId);
            const currentValue = select.value || currentSettings[selectId];
            
            // Limpiar opciones existentes (excepto la primera)
            while (select.children.length > 1) {
                select.removeChild(select.lastChild);
            }
            
            // Agregar impresoras
            printers.forEach(printer => {
                const option = document.createElement('option');
                option.value = printer.name;
                option.textContent = `${printer.name} (${getPrinterTypeLabel(printer.type)})`;
                
                if (printer.name === currentValue) {
                    option.selected = true;
                }
                
                select.appendChild(option);
            });
        });
        
        // Habilitar botón de prueba si hay impresora seleccionada
        document.getElementById('testPrinter').disabled = !document.getElementById('default_printer').value;
        document.getElementById('diagnosePrinter').disabled = !document.getElementById('default_printer').value;
    }
    
    async function diagnosePrinter(printerName) {
        try {
            window.showSuccess('🔍 Analizando estado de la impresora...');
            
            const response = await fetch(`/api/printers/${encodeURIComponent(printerName)}/status`);
            const data = await response.json();
            
            if (data.success) {
                let message = `📊 Diagnóstico de: ${printerName}\n\n`;
                
                if (data.status.online !== undefined) {
                    message += `Estado: ${data.status.online ? '🟢 En línea' : '🔴 Desconectada'}\n`;
                }
                
                if (data.status.description) {
                    message += `Descripción: ${data.status.description}\n`;
                }
                
                if (data.status.error_state && data.status.error_state !== 'Unknown') {
                    message += `Estado de error: ${data.status.error_state}\n`;
                }
                
                // Recomendaciones
                message += '\n📋 Recomendaciones:\n';
                message += '• Verifique que la impresora esté encendida\n';
                message += '• Compruebe la conexión USB/Red\n';
                message += '• Revise que tenga papel\n';
                message += '• Verifique los drivers instalados\n';
                
                alert(message);
            } else {
                window.showError('Error en diagnóstico: ' + data.message);
            }
        } catch (error) {
            window.showError('Error al obtener diagnóstico: ' + error.message);
        }
    }
    
    function getPrinterIcon(type) {
        switch (type) {
            case 'thermal': return 'bi-receipt';
            case 'pdf': return 'bi-file-pdf';
            case 'fax': return 'bi-telephone';
            default: return 'bi-printer';
        }
    }
    
    function getPrinterTypeLabel(type) {
        switch (type) {
            case 'thermal': return 'Térmica/POS';
            case 'pdf': return 'PDF';
            case 'fax': return 'Fax';
            default: return 'General';
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Hacer funciones de alerta disponibles globalmente
    window.showSuccess = function(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            <i class="bi bi-check-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    };
    
    window.showError = function(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            <i class="bi bi-exclamation-triangle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    };
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/settings/invoice.blade.php ENDPATH**/ ?>