<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-4">
        <div class="container-fluid px-4">
            <style>
                .dashboard-card {
                    transition: all 0.3s ease;
                    border-radius: 15px;
                    border: none;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                }
                
                .dashboard-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                }

                .page-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 2rem 0;
                    margin-bottom: 2rem;
                    border-radius: 0 0 20px 20px;
                }

                .page-title {
                    font-size: 2.5rem;
                    font-weight: 700;
                    margin: 0;
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }

                .filter-card {
                    background: white;
                    border-radius: 15px;
                    padding: 2rem;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                    margin-bottom: 2rem;
                }

                .section-title {
                    font-size: 1.5rem;
                    font-weight: 700;
                    color: #2d3748;
                    margin-bottom: 1.5rem;
                    border-bottom: 3px solid #667eea;
                    padding-bottom: 0.5rem;
                }

                .data-table {
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                }

                .data-table thead tr,
                .data-table thead tr th {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                    color: white !important;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-size: 0.875rem;
                }

                .data-table tbody tr:hover {
                    background-color: #f8fafc;
                }

                .btn-action {
                    border-radius: 8px;
                    padding: 0.5rem 1rem;
                    font-weight: 600;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    transition: all 0.3s ease;
                }

                .btn-primary-custom {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border: none;
                }

                .btn-success-custom {
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    color: white;
                    border: none;
                }
            </style>

            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">
                                <i class="bi bi-receipt-cutoff"></i>
                                Ventas
                            </h1>
                            <p class="mb-0 opacity-75">Sistema POS Paraguay - Gestión de ventas</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-primary-custom btn-action">
                                <i class="bi bi-plus-lg"></i>Nueva Venta
                            </a>
                            <a href="<?php echo e(route('sales.reports')); ?>" class="btn btn-success-custom btn-action">
                                <i class="bi bi-graph-up"></i>Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Ventas a Crédito -->
            <?php if($creditStats && $creditStats->total_credit_sales > 0): ?>
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="dashboard-card bg-primary text-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2 fw-normal text-uppercase" style="font-size:.75rem;letter-spacing:1px;">Ventas a Crédito</h6>
                                    <h2 class="mb-0 fw-bold" style="font-size:2rem;"><?php echo e($creditStats->total_credit_sales); ?></h2>
                                    <small class="text-white-50">ventas registradas</small>
                                </div>
                                <div>
                                    <i class="bi bi-credit-card" style="font-size:3.5rem;opacity:.25;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card bg-success text-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2 fw-normal text-uppercase" style="font-size:.75rem;letter-spacing:1px;">Total Cobrado</h6>
                                    <h2 class="mb-0 fw-bold" style="font-size:2rem;">₲ <?php echo e(number_format($creditStats->total_collected ?? 0, 0, ',', '.')); ?></h2>
                                    <small class="text-white-50">abonado en créditos</small>
                                </div>
                                <div>
                                    <i class="bi bi-cash-stack" style="font-size:3.5rem;opacity:.25;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card bg-danger text-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-2 fw-normal text-uppercase" style="font-size:.75rem;letter-spacing:1px;">Saldo Pendiente</h6>
                                    <h2 class="mb-0 fw-bold" style="font-size:2rem;">₲ <?php echo e(number_format($creditStats->total_balance_due ?? 0, 0, ',', '.')); ?></h2>
                                    <small class="text-white-50">por cobrar</small>
                                </div>
                                <div>
                                    <i class="bi bi-exclamation-triangle" style="font-size:3.5rem;opacity:.25;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Filtros -->
            <div class="filter-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="section-title mb-0">
                        <i class="bi bi-funnel me-2"></i>
                        Filtros de Búsqueda
                    </h3>
                    <a href="<?php echo e(route('sales.index', ['sale_condition' => 'CREDITO', 'pending_balance' => '1'])); ?>" 
                       class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-exclamation-circle"></i> Ver Solo Saldos Pendientes
                    </a>
                </div>
                <form method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label fw-bold">Buscar</label>
                            <input type="text" id="search" name="search" value="<?php echo e(request('search')); ?>"
                                   placeholder="Nº factura, cliente..."
                                   class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label fw-bold">Desde</label>
                            <input type="date" id="date_from" name="date_from" value="<?php echo e(request('date_from')); ?>"
                                   class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label fw-bold">Hasta</label>
                            <input type="date" id="date_to" name="date_to" value="<?php echo e(request('date_to')); ?>"
                                   class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="sale_condition" class="form-label fw-bold">Condición</label>
                            <select id="sale_condition" name="sale_condition" class="form-select">
                                <option value="">Todas</option>
                                <option value="CONTADO" <?php echo e(request('sale_condition') === 'CONTADO' ? 'selected' : ''); ?>>Contado</option>
                                <option value="CREDITO" <?php echo e(request('sale_condition') === 'CREDITO' ? 'selected' : ''); ?>>Crédito</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold d-block">Saldo Pendiente</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="pending_balance" name="pending_balance" value="1" <?php echo e(request('pending_balance') == '1' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="pending_balance">
                                    Solo con saldo
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Lista de Ventas -->
            <div class="dashboard-card">
                <div class="card-body">
                    <h3 class="section-title">
                        <i class="bi bi-list-ul me-2"></i>
                        Lista de Ventas
                    </h3>
                    
                    <div class="table-responsive">
                        <table class="table data-table mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Cliente</th>
                                    <th>Items</th>
                                    <th>Condición</th>
                                    <th>Total</th>
                                    <th>Abonado</th>
                                    <th>Saldo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="<?php echo e($sale->balance_due > 0 ? 'table-warning' : ''); ?>">
                                    <td><?php echo e($sale->created_at->format('d/m/Y H:i')); ?></td>
                                    <td class="fw-bold">
                                        <?php if($sale->invoice_number): ?>
                                            <?php echo e($sale->invoice_number); ?>

                                        <?php else: ?>
                                            <span class="text-muted">Sin factura</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($sale->customer_name ?: 'Cliente general'); ?></td>
                                    <td><?php echo e($sale->saleItems->count()); ?> items</td>
                                    <td>
                                        <?php if($sale->sale_condition === 'CREDITO'): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-calendar-check"></i> Crédito
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-cash-coin"></i> Contado
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold">₲ <?php echo e(number_format($sale->total_amount, 0, ',', '.')); ?></td>
                                    <td class="text-primary">₲ <?php echo e(number_format($sale->amount_paid ?? 0, 0, ',', '.')); ?></td>
                                    <td>
                                        <?php if($sale->balance_due > 0): ?>
                                            <span class="fw-bold text-danger">
                                                <i class="bi bi-exclamation-circle"></i>
                                                ₲ <?php echo e(number_format($sale->balance_due, 0, ',', '.')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($sale->status === 'COMPLETED'): ?>
                                            <span class="badge bg-success">Completada</span>
                                        <?php elseif($sale->status === 'PENDING'): ?>
                                            <span class="badge bg-warning">Pendiente</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Cancelada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo e(route('sales.show', $sale)); ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if($sale->sale_condition === 'CREDITO' && $sale->balance_due > 0): ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-warning" 
                                                    title="Registrar Pago"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal"
                                                    data-sale-id="<?php echo e($sale->id); ?>"
                                                    data-sale-number="<?php echo e($sale->sale_number); ?>"
                                                    data-customer="<?php echo e($sale->customer_name ?: 'Cliente general'); ?>"
                                                    data-balance="<?php echo e($sale->balance_due); ?>">
                                                <i class="bi bi-cash-coin"></i> Pagar
                                            </button>
                                            <?php endif; ?>
                                            <?php if($sale->invoice): ?>
                                            <a href="<?php echo e(route('pdf.preview.invoice', $sale->id)); ?>"
                                               class="btn btn-sm btn-outline-primary" target="_blank" title="Imprimir factura">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <a href="<?php echo e(route('pdf.invoice', $sale->id)); ?>"
                                               class="btn btn-sm btn-outline-danger" target="_blank" title="Descargar PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if(\App\Models\InvoiceSetting::getSettings()->ticket_printer): ?>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-info btn-print-bt"
                                                    data-sale-id="<?php echo e($sale->id); ?>"
                                                    title="Imprimir ticket en impresora Bluetooth">
                                                <i class="bi bi-bluetooth"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-3 mb-0">No hay ventas registradas</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($sales->hasPages()): ?>
                    <div class="mt-4 d-flex justify-content-center">
                        <?php echo e($sales->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal de Registro de Pago -->
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title text-dark" id="paymentModalLabel">
                                <i class="bi bi-cash-coin me-2"></i>
                                Registrar Pago
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="paymentForm" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <div class="mb-2"><strong>Venta:</strong> <span id="modal-sale-number"></span></div>
                                    <div class="mb-2"><strong>Cliente:</strong> <span id="modal-customer"></span></div>
                                    <div><strong>Saldo Pendiente:</strong> <span id="modal-balance" class="text-danger fw-bold"></span></div>
                                </div>

                                <div class="mb-3">
                                    <label for="payment-amount" class="form-label">Monto del Pago <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₲</span>
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="payment-amount" 
                                               name="amount" 
                                               min="1" 
                                               step="1" 
                                               placeholder="0"
                                               required>
                                    </div>
                                    <small class="text-muted">Ingrese el monto que desea abonar</small>
                                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="payment-method" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                                    <select class="form-select <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="payment-method" 
                                            name="payment_method" 
                                            required>
                                        <option value="">Seleccionar...</option>
                                        <option value="CASH">💵 Efectivo</option>
                                        <option value="CARD">💳 Tarjeta</option>
                                        <option value="CHEQUE">📝 Cheque</option>
                                        <option value="TRANSFER">🏦 Transferencia</option>
                                    </select>
                                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="payment-notes" class="form-label">Notas del Pago (opcional)</label>
                                    <textarea class="form-control" 
                                              id="payment-notes" 
                                              name="notes" 
                                              rows="2" 
                                              maxlength="500" 
                                              placeholder="Ej: Pago parcial acordado"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Registrar Pago
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bootstrap 5 JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            
            <script>
                // Configurar modal de pago
                const paymentModal = document.getElementById('paymentModal');
                if (paymentModal) {
                    paymentModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        const saleId = button.getAttribute('data-sale-id');
                        const saleNumber = button.getAttribute('data-sale-number');
                        const customer = button.getAttribute('data-customer');
                        const balance = button.getAttribute('data-balance');
                        
                        // Actualizar contenido del modal
                        document.getElementById('modal-sale-number').textContent = saleNumber;
                        document.getElementById('modal-customer').textContent = customer;
                        document.getElementById('modal-balance').textContent = 
                            '₲ ' + parseFloat(balance).toLocaleString('es-PY', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                        
                        // Configurar el formulario
                        const form = document.getElementById('paymentForm');
                        form.action = `/sales/${saleId}/payments`;
                        
                        // Configurar el max del input
                        document.getElementById('payment-amount').setAttribute('max', balance);
                        
                        // Limpiar el formulario
                        form.reset();
                    });
                }
            </script>

            
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.btn-print-bt').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const saleId = this.dataset.saleId;
                        const icon   = this.querySelector('i');
                        icon.className = 'spinner-border spinner-border-sm';
                        this.disabled = true;

                        fetch('/print/bluetooth/' + saleId, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        })
                        .then(r => r.json())
                        .then(function (data) {
                            if (data.success) {
                                icon.className = 'bi bi-check-lg text-success';
                                setTimeout(() => { icon.className = 'bi bi-bluetooth'; }, 2000);
                            } else {
                                alert('Error: ' + (data.error || 'No se pudo imprimir'));
                                icon.className = 'bi bi-bluetooth';
                            }
                        })
                        .catch(function () {
                            alert('Error de conexión al intentar imprimir');
                            icon.className = 'bi bi-bluetooth';
                        })
                        .finally(() => { btn.disabled = false; });
                    });
                });
            });
            </script>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/sales/index.blade.php ENDPATH**/ ?>