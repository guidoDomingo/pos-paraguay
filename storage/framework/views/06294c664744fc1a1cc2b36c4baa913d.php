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
     <?php $__env->slot('header', null, []); ?> 
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-sliders me-2"></i><?php echo e(__('Ajustar Stock')); ?>

            </h2>
            <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-gear me-2"></i>
                                Ajuste de Inventario
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('inventory.adjust.store')); ?>" id="adjustmentForm">
                                <?php echo csrf_field(); ?>
                                
                                <?php if($errors->any()): ?>
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($error); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="product_id" class="form-label">Producto <span class="text-danger">*</span></label>
                                            <select class="form-select <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="product_id" name="product_id" required>
                                                <option value="">Seleccionar producto</option>
                                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($product->id); ?>" 
                                                            data-stock="<?php echo e($product->stock ?? 0); ?>"
                                                            data-unit="<?php echo e($product->unit ?? 'unidades'); ?>"
                                                            data-min-stock="<?php echo e($product->min_stock ?? 0); ?>"
                                                            data-cost="<?php echo e($product->cost_price ?? 0); ?>"
                                                            <?php echo e(request('product') == $product->id || old('product_id') == $product->id ? 'selected' : ''); ?>>
                                                        <?php echo e($product->name); ?> (<?php echo e($product->code); ?>)
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['product_id'];
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
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="adjustment_type" class="form-label">Tipo de Ajuste <span class="text-danger">*</span></label>
                                            <select class="form-select <?php $__errorArgs = ['adjustment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="adjustment_type" name="adjustment_type" required>
                                                <option value="">Seleccionar tipo</option>
                                                <option value="add" <?php echo e(old('adjustment_type') == 'add' ? 'selected' : ''); ?>>Agregar Stock</option>
                                                <option value="subtract" <?php echo e(old('adjustment_type') == 'subtract' ? 'selected' : ''); ?>>Reducir Stock</option>
                                                <option value="set" <?php echo e(old('adjustment_type') == 'set' ? 'selected' : ''); ?>>Establecer Stock</option>
                                            </select>
                                            <?php $__errorArgs = ['adjustment_type'];
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
                                    </div>
                                </div>

                                <!-- Información del producto seleccionado -->
                                <div id="productInfo" class="alert alert-info" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Stock Actual:</strong> <span id="currentStock">0</span> <span id="stockUnit">unidades</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Stock Mínimo:</strong> <span id="minStock">0</span> <span id="minStockUnit">unidades</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Nuevo Stock:</strong> <span id="newStock" class="text-primary fw-bold">0</span> <span id="newStockUnit">unidades</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="quantity" name="quantity" value="<?php echo e(old('quantity')); ?>" 
                                                   min="0" step="1" required>
                                            <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div id="adjustmentHelp" class="form-text"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="unit_cost" class="form-label">Costo Unitario (opcional)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₲</span>
                                                <input type="number" class="form-control <?php $__errorArgs = ['unit_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="unit_cost" name="unit_cost" value="<?php echo e(old('unit_cost')); ?>" 
                                                       min="0" step="0.01" placeholder="0.00">
                                            </div>
                                            <?php $__errorArgs = ['unit_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <small class="form-text text-muted">Útil para calcular el valor del inventario</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="reason" class="form-label">Motivo del Ajuste <span class="text-danger">*</span></label>
                                    <textarea class="form-control <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="reason" name="reason" rows="3" required 
                                              placeholder="Describe el motivo del ajuste de inventario..."><?php echo e(old('reason')); ?></textarea>
                                    <?php $__errorArgs = ['reason'];
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

                                <!-- Vista previa del cambio -->
                                <div id="changePreview" class="card mb-4" style="display: none;">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="bi bi-eye me-2"></i>Vista Previa del Cambio
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <h6 class="text-muted">Stock Actual</h6>
                                                <h3 id="previewCurrentStock" class="text-info">0</h3>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="text-muted">Cambio</h6>
                                                <h3 id="previewChange" class="text-warning">+0</h3>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="text-muted">Nuevo Stock</h6>
                                                <h3 id="previewNewStock" class="text-success">0</h3>
                                            </div>
                                        </div>
                                        
                                        <div id="stockWarning" class="alert alert-warning mt-3" style="display: none;">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <strong>Advertencia:</strong> El nuevo stock será menor que el stock mínimo.
                                        </div>
                                        
                                        <div id="stockDanger" class="alert alert-danger mt-3" style="display: none;">
                                            <i class="bi bi-x-circle me-2"></i>
                                            <strong>Atención:</strong> El producto quedará sin stock.
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="bi bi-check-circle me-1"></i>Aplicar Ajuste
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
        let currentProductStock = 0;
        let currentProductUnit = 'unidades';
        let currentMinStock = 0;
        
        function updateProductInfo() {
            const select = document.getElementById('product_id');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                currentProductStock = parseInt(option.dataset.stock) || 0;
                currentProductUnit = option.dataset.unit || 'unidades';
                currentMinStock = parseInt(option.dataset.minStock) || 0;
                
                document.getElementById('currentStock').textContent = currentProductStock;
                document.getElementById('stockUnit').textContent = currentProductUnit;
                document.getElementById('minStock').textContent = currentMinStock;
                document.getElementById('minStockUnit').textContent = currentProductUnit;
                document.getElementById('newStockUnit').textContent = currentProductUnit;
                
                // Set default cost
                const cost = parseFloat(option.dataset.cost) || 0;
                if (cost > 0) {
                    document.getElementById('unit_cost').value = cost;
                }
                
                document.getElementById('productInfo').style.display = 'block';
                calculateNewStock();
            } else {
                document.getElementById('productInfo').style.display = 'none';
                document.getElementById('changePreview').style.display = 'none';
                document.getElementById('submitBtn').disabled = true;
            }
        }
        
        function updateAdjustmentHelp() {
            const type = document.getElementById('adjustment_type').value;
            const helpDiv = document.getElementById('adjustmentHelp');
            
            switch(type) {
                case 'add':
                    helpDiv.textContent = 'Se agregará esta cantidad al stock actual';
                    helpDiv.className = 'form-text text-success';
                    break;
                case 'subtract':
                    helpDiv.textContent = 'Se restará esta cantidad del stock actual';
                    helpDiv.className = 'form-text text-warning';
                    break;
                case 'set':
                    helpDiv.textContent = 'El stock se establecerá exactamente en esta cantidad';
                    helpDiv.className = 'form-text text-info';
                    break;
                default:
                    helpDiv.textContent = '';
            }
            
            calculateNewStock();
        }
        
        function calculateNewStock() {
            const productId = document.getElementById('product_id').value;
            const type = document.getElementById('adjustment_type').value;
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            
            if (!productId || !type || quantity === 0) {
                document.getElementById('changePreview').style.display = 'none';
                document.getElementById('submitBtn').disabled = true;
                return;
            }
            
            let newStock = 0;
            let change = 0;
            
            switch(type) {
                case 'add':
                    newStock = currentProductStock + quantity;
                    change = quantity;
                    break;
                case 'subtract':
                    newStock = Math.max(0, currentProductStock - quantity);
                    change = -quantity;
                    break;
                case 'set':
                    newStock = quantity;
                    change = quantity - currentProductStock;
                    break;
            }
            
            // Update display
            document.getElementById('newStock').textContent = newStock;
            document.getElementById('previewCurrentStock').textContent = currentProductStock;
            document.getElementById('previewChange').textContent = (change >= 0 ? '+' : '') + change;
            document.getElementById('previewNewStock').textContent = newStock;
            
            // Show warnings
            const warningDiv = document.getElementById('stockWarning');
            const dangerDiv = document.getElementById('stockDanger');
            
            warningDiv.style.display = 'none';
            dangerDiv.style.display = 'none';
            
            if (newStock <= 0) {
                dangerDiv.style.display = 'block';
            } else if (newStock <= currentMinStock && currentMinStock > 0) {
                warningDiv.style.display = 'block';
            }
            
            document.getElementById('changePreview').style.display = 'block';
            
            // Validar que todos los campos requeridos estén llenos
            const reason = document.getElementById('reason').value.trim();
            if (productId && type && quantity > 0 && reason.length > 0) {
                document.getElementById('submitBtn').disabled = false;
            } else {
                document.getElementById('submitBtn').disabled = true;
            }
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Event listeners
            document.getElementById('product_id').addEventListener('change', function() {
                updateProductInfo();
                calculateNewStock();
            });
            document.getElementById('adjustment_type').addEventListener('change', function() {
                updateAdjustmentHelp();
                calculateNewStock();
            });
            document.getElementById('quantity').addEventListener('input', calculateNewStock);
            document.getElementById('reason').addEventListener('input', calculateNewStock);
            
            updateProductInfo();
            updateAdjustmentHelp();
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/inventory/adjust.blade.php ENDPATH**/ ?>