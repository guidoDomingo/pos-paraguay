

<?php $__env->startSection('title', 'Detalle de Venta #'.$sale->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">
            <i class="bi bi-receipt me-2"></i>
            Detalle de Venta #<?php echo e($sale->id); ?>

        </h2>
        <div class="btn-group">
            <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver al POS
            </a>
            <?php if($sale->sale_type === 'INVOICE'): ?>
            <button class="btn btn-primary">
                <i class="bi bi-printer me-1"></i>
                Imprimir Factura
            </button>
            <?php else: ?>
            <button class="btn btn-outline-primary">
                <i class="bi bi-receipt me-1"></i>
                Imprimir Ticket
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información General -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha y Hora</label>
                            <p class="mb-0"><?php echo e($sale->created_at->format('d/m/Y H:i:s')); ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Número de Venta</label>
                            <p class="mb-0"><?php echo e($sale->sale_number ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Estado</label>
                            <p class="mb-0">
                                <?php if($sale->status === 'COMPLETED'): ?>
                                    <span class="badge bg-success">Completada</span>
                                <?php elseif($sale->status === 'PENDING'): ?>
                                    <span class="badge bg-warning">Pendiente</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Cancelada</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Cliente</label>
                            <p class="mb-0"><?php echo e($sale->customer_name ?: 'Cliente general'); ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Documento</label>
                            <p class="mb-0"><?php echo e($sale->customer_document ?: '-'); ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Vendedor</label>
                            <p class="mb-0"><?php echo e($sale->user->name ?? 'Administrador Sistema'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items de la Venta -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-cart-check me-2"></i>
                        Items Vendidos
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Código</th>
                                    <th class="text-end">Precio Unit.</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">IVA</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $sale->saleItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-semibold"><?php echo e($item->product_name); ?></div>
                                                <?php if($item->iva_type !== 'EXENTO'): ?>
                                                    <small class="text-muted"><?php echo e($item->iva_type); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="small"><?php echo e($item->product_code); ?></code>
                                    </td>
                                    <td class="text-end">
                                        <strong>₲ <?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></strong>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light text-dark"><?php echo e(number_format($item->quantity, 0)); ?></span>
                                    </td>
                                    <td class="text-end">
                                        <?php if($item->iva_amount > 0): ?>
                                            <span class="text-success">₲ <?php echo e(number_format($item->iva_amount, 0, ',', '.')); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Exento</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <strong>₲ <?php echo e(number_format($item->total_price, 0, ',', '.')); ?></strong>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                            No hay items en esta venta
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totales y Pago -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calculator me-2"></i>
                        Totales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Subtotal:</span>
                        <strong>₲ <?php echo e(number_format($sale->subtotal, 0, ',', '.')); ?></strong>
                    </div>
                    <?php if($sale->discount_amount > 0): ?>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Descuento:</span>
                        <span class="text-danger">- ₲ <?php echo e(number_format($sale->discount_amount, 0, ',', '.')); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>IVA (10%):</span>
                        <strong>₲ <?php echo e(number_format($sale->tax_amount, 0, ',', '.')); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between py-3 border-top border-2 mt-2">
                        <span class="h5 mb-0">TOTAL:</span>
                        <span class="h4 mb-0 text-success">₲ <?php echo e(number_format($sale->total_amount, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Pago -->
        <div class="col-lg-6">
            <div class="card <?php echo e($sale->balance_due > 0 ? 'border-danger border-2' : ''); ?>">
                <div class="card-header <?php echo e($sale->balance_due > 0 ? 'bg-danger' : 'bg-warning'); ?> text-<?php echo e($sale->balance_due > 0 ? 'white' : 'dark'); ?>">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>
                        Información de Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Condición de Venta</label>
                            <p class="mb-0">
                                <?php if($sale->sale_condition === 'CREDITO'): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-calendar-check"></i> Crédito
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-cash-coin"></i> Contado
                                    </span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Método de Pago</label>
                            <p class="mb-0">
                                <?php if($sale->payment_method === 'CASH'): ?>
                                    <i class="bi bi-cash me-1"></i> Efectivo
                                <?php elseif($sale->payment_method === 'TRANSFER'): ?>
                                    <i class="bi bi-bank me-1"></i> Transferencia
                                <?php elseif($sale->payment_method === 'CARD'): ?>
                                    <i class="bi bi-credit-card me-1"></i> Tarjeta
                                <?php elseif($sale->payment_method === 'CHEQUE'): ?>
                                    <i class="bi bi-check2-square me-1"></i> Cheque
                                <?php else: ?>
                                    <?php echo e($sale->payment_method); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                        
                        <?php if($sale->sale_condition === 'CREDITO'): ?>
                        <div class="col-12">
                            <hr class="my-2">
                            <div class="alert alert-<?php echo e($sale->balance_due > 0 ? 'warning' : 'success'); ?> mb-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Total de la Venta:</strong>
                                    <strong>₲ <?php echo e(number_format($sale->total_amount, 0, ',', '.')); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Monto Abonado:</span>
                                    <span class="text-primary">₲ <?php echo e(number_format($sale->amount_paid ?? 0, 0, ',', '.')); ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <strong class="text-<?php echo e($sale->balance_due > 0 ? 'danger' : 'success'); ?>">Saldo Pendiente:</strong>
                                    <strong class="text-<?php echo e($sale->balance_due > 0 ? 'danger' : 'success'); ?> fs-5">
                                        <?php if($sale->balance_due > 0): ?>
                                            <i class="bi bi-exclamation-circle"></i>
                                        <?php else: ?>
                                            <i class="bi bi-check-circle"></i>
                                        <?php endif; ?>
                                        ₲ <?php echo e(number_format($sale->balance_due ?? 0, 0, ',', '.')); ?>

                                    </strong>
                                </div>
                            </div>
                        </div>
                        
                        <?php if($sale->sale_condition === 'CREDITO' && $sale->balance_due > 0): ?>
                        <!-- Formulario para Registrar Pago -->
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="mb-3">
                                <i class="bi bi-plus-circle me-1"></i>
                                Registrar Pago Adicional
                            </h6>
                            <form action="<?php echo e(route('payments.store', $sale)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="amount" class="form-label">Monto del Pago <span class="text-danger">*</span></label>
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
                                                   id="amount" 
                                                   name="amount" 
                                                   min="1" 
                                                   max="<?php echo e($sale->balance_due); ?>"
                                                   step="1" 
                                                   placeholder="0"
                                                   required>
                                        </div>
                                        <small class="text-muted">Máximo: ₲ <?php echo e(number_format($sale->balance_due, 0, ',', '.')); ?></small>
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
                                    <div class="col-md-6">
                                        <label for="payment_method" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                                        <select class="form-select <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="payment_method" 
                                                name="payment_method" 
                                                required>
                                            <option value="">Seleccionar...</option>
                                            <option value="CASH">Efectivo</option>
                                            <option value="CARD">Tarjeta</option>
                                            <option value="CHEQUE">Cheque</option>
                                            <option value="TRANSFER">Transferencia</option>
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
                                    <div class="col-12">
                                        <label for="notes" class="form-label">Notas del Pago (opcional)</label>
                                        <textarea class="form-control" 
                                                  id="notes" 
                                                  name="notes" 
                                                  rows="2" 
                                                  maxlength="500" 
                                                  placeholder="Ej: Pago parcial acordado"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Registrar Pago
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>
                        <?php else: ?>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Monto Pagado</label>
                            <p class="mb-0 text-primary">₲ <?php echo e(number_format($sale->amount_paid ?? $sale->total_amount, 0, ',', '.')); ?></p>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Cambio</label>
                            <p class="mb-0 text-success">₲ <?php echo e(number_format($sale->change_amount ?? 0, 0, ',', '.')); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tipo de Comprobante</label>
                            <p class="mb-0">
                                <?php if($sale->sale_type === 'TICKET'): ?>
                                    <i class="bi bi-receipt me-1"></i> Ticket
                                <?php else: ?>
                                    <i class="bi bi-file-earmark-text me-1"></i> Factura
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Pagos (solo para ventas a crédito) -->
        <?php if($sale->sale_condition === 'CREDITO' && $sale->payments && $sale->payments->count() > 0): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Historial de Pagos (<?php echo e($sale->payments->count()); ?>)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Usuario</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $sale->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                        <?php echo e($payment->payment_date->format('d/m/Y H:i')); ?>

                                    </td>
                                    <td>
                                        <strong class="text-success">₲ <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></strong>
                                    </td>
                                    <td>
                                        <?php if($payment->payment_method === 'CASH'): ?>
                                            <i class="bi bi-cash text-success"></i> Efectivo
                                        <?php elseif($payment->payment_method === 'CARD'): ?>
                                            <i class="bi bi-credit-card text-primary"></i> Tarjeta
                                        <?php elseif($payment->payment_method === 'CHEQUE'): ?>
                                            <i class="bi bi-check2-square text-info"></i> Cheque
                                        <?php elseif($payment->payment_method === 'TRANSFER'): ?>
                                            <i class="bi bi-bank text-warning"></i> Transferencia
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($payment->user->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($payment->notes ?: '-'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Abonado:</strong></td>
                                    <td>
                                        <strong class="text-success">₲ <?php echo e(number_format($sale->amount_paid, 0, ',', '.')); ?></strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Notas -->
        <?php if($sale->notes): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-chat-text me-2"></i>
                        Notas
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo e($sale->notes); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.pos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/sales/show.blade.php ENDPATH**/ ?>