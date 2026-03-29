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
                <i class="bi bi-plus-circle me-2"></i><?php echo e(__('Nuevo Producto')); ?>

            </h2>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary">
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
                                <i class="bi bi-box-seam me-2"></i>
                                Información del Producto
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('products.store')); ?>" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                
                                <!-- Información básica -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Código del Producto <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="code" name="code" value="<?php echo e(old('code')); ?>" 
                                                   placeholder="Ej: PROD001">
                                            <?php $__errorArgs = ['code'];
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
                                            <label for="barcode" class="form-label">Código de Barras</label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="barcode" name="barcode" value="<?php echo e(old('barcode')); ?>" 
                                                   placeholder="Código de barras">
                                            <?php $__errorArgs = ['barcode'];
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

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="name" name="name" value="<?php echo e(old('name')); ?>" 
                                                   placeholder="Nombre del producto">
                                            <?php $__errorArgs = ['name'];
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
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Categoría</label>
                                            <select class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="category_id" name="category_id">
                                                <option value="">Seleccionar categoría</option>
                                                <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                                        <?php echo e($category->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['category_id'];
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

                                <div class="mb-4">
                                    <label for="description" class="form-label">Descripción</label>
                                    <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Descripción detallada del producto"><?php echo e(old('description')); ?></textarea>
                                    <?php $__errorArgs = ['description'];
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
                                                    <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp">
                                                    <div class="form-text mt-2">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Formatos: JPG, PNG, WebP • Tamaño máximo: 5MB
                                                    </div>
                                                    <?php $__errorArgs = ['image'];
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
                                        <h6 class="mb-0"><i class="bi bi-currency-exchange me-2"></i>Configuración de Precios</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Precio de Costo -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="cost_price" class="form-label">Precio de Costo</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₲</span>
                                                        <input type="number" class="form-control <?php $__errorArgs = ['cost_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="cost_price" name="cost_price" value="<?php echo e(old('cost_price')); ?>" 
                                                               min="0" step="0.01" placeholder="0.00">
                                                    </div>
                                                    <?php $__errorArgs = ['cost_price'];
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
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="profit_percentage" class="form-label">% Ganancia Venta</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" 
                                                               id="profit_percentage" 
                                                               min="0" max="1000" step="0.1" placeholder="30">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <div class="form-text">Porcentaje de ganancia para calcular precio de venta</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="wholesale_percentage" class="form-label">% Ganancia Mayorista</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" 
                                                               id="wholesale_percentage" 
                                                               min="0" max="1000" step="0.1" placeholder="20">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <div class="form-text">Porcentaje de ganancia para calcular precio mayorista</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Precios Calculados -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sale_price" class="form-label">
                                                        Precio de Venta <span class="text-danger">*</span>
                                                        <span class="badge bg-info ms-1" id="sale_profit_badge">Calculado</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₲</span>
                                                        <input type="number" class="form-control <?php $__errorArgs = ['sale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="sale_price" name="sale_price" value="<?php echo e(old('sale_price')); ?>" 
                                                               min="0" step="0.01" placeholder="0.00" required>
                                                        <button class="btn btn-outline-secondary" type="button" id="toggle_sale_manual" title="Alternar entrada manual">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    </div>
                                                    <?php $__errorArgs = ['sale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    <div class="form-text">Precio estándar para venta minorista</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="wholesale_price" class="form-label">
                                                        Precio Mayorista
                                                        <span class="badge bg-info ms-1" id="wholesale_profit_badge">Calculado</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₲</span>
                                                        <input type="number" class="form-control <?php $__errorArgs = ['wholesale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="wholesale_price" name="wholesale_price" value="<?php echo e(old('wholesale_price')); ?>" 
                                                               min="0" step="0.01" placeholder="0.00">
                                                        <button class="btn btn-outline-secondary" type="button" id="toggle_wholesale_manual" title="Alternar entrada manual">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    </div>
                                                    <?php $__errorArgs = ['wholesale_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    <div class="form-text">Para ventas al por mayor</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Información de Ganancia -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="alert alert-light border">
                                                    <small class="text-muted">
                                                        <strong>Ganancia Unitaria Venta:</strong> 
                                                        <span id="sale_profit_amount">₲ 0</span>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="alert alert-light border">
                                                    <small class="text-muted">
                                                        <strong>Ganancia Unitaria Mayorista:</strong> 
                                                        <span id="wholesale_profit_amount">₲ 0</span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Precios especiales -->
                                        <hr class="my-4">
                                        <h6 class="mb-3"><i class="bi bi-star me-2"></i>Precios Especiales</h6>
                                        
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="check_price" class="form-label">Precio para Cheques</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₲</span>
                                                        <input type="number" class="form-control <?php $__errorArgs = ['check_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="check_price" name="check_price" value="<?php echo e(old('check_price')); ?>" 
                                                               min="0" step="0.01" placeholder="0.00">
                                                    </div>
                                                    <?php $__errorArgs = ['check_price'];
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
                                                    <label for="check_price_description" class="form-label">Descripción del precio</label>
                                                    <input type="text" class="form-control <?php $__errorArgs = ['check_price_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="check_price_description" name="check_price_description" 
                                                           value="<?php echo e(old('check_price_description')); ?>" 
                                                           placeholder="Ej: 5% descuento por pago en cheque">
                                                    <?php $__errorArgs = ['check_price_description'];
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
                                                    <label for="credit_price" class="form-label">Precio a Crédito</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₲</span>
                                                        <input type="number" class="form-control <?php $__errorArgs = ['credit_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="credit_price" name="credit_price" value="<?php echo e(old('credit_price')); ?>" 
                                                               min="0" step="0.01" placeholder="0.00">
                                                    </div>
                                                    <?php $__errorArgs = ['credit_price'];
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
                                                    <label for="credit_price_description" class="form-label">Descripción del precio</label>
                                                    <input type="text" class="form-control <?php $__errorArgs = ['credit_price_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="credit_price_description" name="credit_price_description" 
                                                           value="<?php echo e(old('credit_price_description')); ?>" 
                                                           placeholder="Ej: Precio con financiamiento a 30 días">
                                                    <?php $__errorArgs = ['credit_price_description'];
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
                                        
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="special_price" class="form-label">Precio Especial/Promocional</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₲</span>
                                                        <input type="number" class="form-control <?php $__errorArgs = ['special_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="special_price" name="special_price" value="<?php echo e(old('special_price')); ?>" 
                                                               min="0" step="0.01" placeholder="0.00">
                                                    </div>
                                                    <?php $__errorArgs = ['special_price'];
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
                                                    <label for="special_price_description" class="form-label">Descripción del precio especial</label>
                                                    <input type="text" class="form-control <?php $__errorArgs = ['special_price_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                           id="special_price_description" name="special_price_description" 
                                                           value="<?php echo e(old('special_price_description')); ?>" 
                                                           placeholder="Ej: Oferta limitada por tiempo">
                                                    <?php $__errorArgs = ['special_price_description'];
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="iva_type" class="form-label">Tipo de IVA</label>
                                                    <select class="form-select <?php $__errorArgs = ['iva_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="iva_type" name="iva_type">
                                                        <option value="IVA_10" <?php echo e(old('iva_type', 'IVA_10') == 'IVA_10' ? 'selected' : ''); ?>>IVA 10%</option>
                                                        <option value="IVA_5" <?php echo e(old('iva_type') == 'IVA_5' ? 'selected' : ''); ?>>IVA 5%</option>
                                                        <option value="EXENTO" <?php echo e(old('iva_type') == 'EXENTO' ? 'selected' : ''); ?>>Exento</option>
                                                    </select>
                                                    <?php $__errorArgs = ['iva_type'];
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
                                                    <label for="unit" class="form-label">Unidad de Medida</label>
                                                    <select class="form-select <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="unit" name="unit">
                                                        <option value="UNIDAD" <?php echo e(old('unit', 'UNIDAD') == 'UNIDAD' ? 'selected' : ''); ?>>Unidad</option>
                                                        <option value="KG" <?php echo e(old('unit') == 'KG' ? 'selected' : ''); ?>>Kilogramo</option>
                                                        <option value="LT" <?php echo e(old('unit') == 'LT' ? 'selected' : ''); ?>>Litro</option>
                                                        <option value="MT" <?php echo e(old('unit') == 'MT' ? 'selected' : ''); ?>>Metro</option>
                                                        <option value="CAJA" <?php echo e(old('unit') == 'CAJA' ? 'selected' : ''); ?>>Caja</option>
                                                        <option value="PAQUETE" <?php echo e(old('unit') == 'PAQUETE' ? 'selected' : ''); ?>>Paquete</option>
                                                    </select>
                                                    <?php $__errorArgs = ['unit'];
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
                                                           value="1" <?php echo e(old('track_stock') ? 'checked' : ''); ?>>
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
                                                        <input type="number" class="form-control <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="stock" name="stock" value="<?php echo e(old('stock', 0)); ?>" 
                                                               min="0" step="1">
                                                        <?php $__errorArgs = ['stock'];
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
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="min_stock" class="form-label">Stock Mínimo</label>
                                                        <input type="number" class="form-control <?php $__errorArgs = ['min_stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                               id="min_stock" name="min_stock" value="<?php echo e(old('min_stock', 0)); ?>" 
                                                               min="0" step="1">
                                                        <?php $__errorArgs = ['min_stock'];
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
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="unit" class="form-label">Unidad de Medida</label>
                                                        <select class="form-select <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="unit" name="unit">
                                                            <option value="unidad" <?php echo e(old('unit', 'unidad') == 'unidad' ? 'selected' : ''); ?>>Unidad</option>
                                                            <option value="kg" <?php echo e(old('unit') == 'kg' ? 'selected' : ''); ?>>Kilogramo</option>
                                                            <option value="litro" <?php echo e(old('unit') == 'litro' ? 'selected' : ''); ?>>Litro</option>
                                                            <option value="metro" <?php echo e(old('unit') == 'metro' ? 'selected' : ''); ?>>Metro</option>
                                                            <option value="caja" <?php echo e(old('unit') == 'caja' ? 'selected' : ''); ?>>Caja</option>
                                                            <option value="paquete" <?php echo e(old('unit') == 'paquete' ? 'selected' : ''); ?>>Paquete</option>
                                                        </select>
                                                        <?php $__errorArgs = ['unit'];
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
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="is_active">
                                            Producto activo
                                        </label>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary">
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

        // ================================
        // CALCULADORA AUTOMÁTICA DE PRECIOS
        // ================================
        
        const costPriceInput = document.getElementById('cost_price');
        const profitPercentageInput = document.getElementById('profit_percentage');
        const wholesalePercentageInput = document.getElementById('wholesale_percentage');
        const salePriceInput = document.getElementById('sale_price');
        const wholesalePriceInput = document.getElementById('wholesale_price');
        const saleProfitBadge = document.getElementById('sale_profit_badge');
        const wholesaleProfitBadge = document.getElementById('wholesale_profit_badge');
        const saleProfitAmount = document.getElementById('sale_profit_amount');
        const wholesaleProfitAmount = document.getElementById('wholesale_profit_amount');
        const toggleSaleManualBtn = document.getElementById('toggle_sale_manual');
        const toggleWholesaleManualBtn = document.getElementById('toggle_wholesale_manual');
        
        let saleManualMode = false;
        let wholesaleManualMode = false;
        
        // Función para formatear números con separadores de miles
        function formatCurrency(amount) {
            return new Intl.NumberFormat('es-PY', {
                style: 'currency',
                currency: 'PYG',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }
        
        // Función para calcular precios
        function calculatePrices() {
            const costPrice = parseFloat(costPriceInput.value) || 0;
            const profitPercentage = parseFloat(profitPercentageInput.value) || 0;
            const wholesalePercentage = parseFloat(wholesalePercentageInput.value) || 0;
            
            // Calcular precio de venta si no está en modo manual
            if (!saleManualMode && costPrice > 0 && profitPercentage > 0) {
                const calculatedSalePrice = costPrice * (1 + profitPercentage / 100);
                salePriceInput.value = calculatedSalePrice.toFixed(0);
                saleProfitBadge.textContent = 'Auto (' + profitPercentage + '%)';
                saleProfitBadge.className = 'badge bg-success ms-1';
                
                // Calcular ganancia unitaria
                const saleProfit = calculatedSalePrice - costPrice;
                saleProfitAmount.textContent = formatCurrency(saleProfit);
            } else if (saleManualMode) {
                saleProfitBadge.textContent = 'Manual';
                saleProfitBadge.className = 'badge bg-warning ms-1';
                
                // Calcular ganancia con precio manual
                const salePrice = parseFloat(salePriceInput.value) || 0;
                const saleProfit = salePrice - costPrice;
                saleProfitAmount.textContent = formatCurrency(saleProfit);
            } else {
                saleProfitBadge.textContent = 'Esperando';
                saleProfitBadge.className = 'badge bg-secondary ms-1';
                saleProfitAmount.textContent = '₲ 0';
            }
            
            // Calcular precio mayorista si no está en modo manual
            if (!wholesaleManualMode && costPrice > 0 && wholesalePercentage > 0) {
                const calculatedWholesalePrice = costPrice * (1 + wholesalePercentage / 100);
                wholesalePriceInput.value = calculatedWholesalePrice.toFixed(0);
                wholesaleProfitBadge.textContent = 'Auto (' + wholesalePercentage + '%)';
                wholesaleProfitBadge.className = 'badge bg-success ms-1';
                
                // Calcular ganancia unitaria
                const wholesaleProfit = calculatedWholesalePrice - costPrice;
                wholesaleProfitAmount.textContent = formatCurrency(wholesaleProfit);
            } else if (wholesaleManualMode) {
                wholesaleProfitBadge.textContent = 'Manual';
                wholesaleProfitBadge.className = 'badge bg-warning ms-1';
                
                // Calcular ganancia con precio manual
                const wholesalePrice = parseFloat(wholesalePriceInput.value) || 0;
                const wholesaleProfit = wholesalePrice - costPrice;
                wholesaleProfitAmount.textContent = formatCurrency(wholesaleProfit);
            } else {
                wholesaleProfitBadge.textContent = 'Esperando';
                wholesaleProfitBadge.className = 'badge bg-secondary ms-1';
                wholesaleProfitAmount.textContent = '₲ 0';
            }
        }
        
        // Alternar modo manual para precio de venta
        toggleSaleManualBtn.addEventListener('click', function() {
            saleManualMode = !saleManualMode;
            if (saleManualMode) {
                this.innerHTML = '<i class="bi bi-calculator"></i>';
                this.title = 'Volver a cálculo automático';
                salePriceInput.focus();
            } else {
                this.innerHTML = '<i class="bi bi-pencil"></i>';
                this.title = 'Alternar entrada manual';
                calculatePrices();
            }
        });
        
        // Alternar modo manual para precio mayorista
        toggleWholesaleManualBtn.addEventListener('click', function() {
            wholesaleManualMode = !wholesaleManualMode;
            if (wholesaleManualMode) {
                this.innerHTML = '<i class="bi bi-calculator"></i>';
                this.title = 'Volver a cálculo automático';
                wholesalePriceInput.focus();
            } else {
                this.innerHTML = '<i class="bi bi-pencil"></i>';
                this.title = 'Alternar entrada manual';
                calculatePrices();
            }
        });
        
        // Event listeners para recalcular automáticamente
        costPriceInput.addEventListener('input', calculatePrices);
        profitPercentageInput.addEventListener('input', calculatePrices);
        wholesalePercentageInput.addEventListener('input', calculatePrices);
        salePriceInput.addEventListener('input', function() {
            if (saleManualMode) calculatePrices();
        });
        wholesalePriceInput.addEventListener('input', function() {
            if (wholesaleManualMode) calculatePrices();
        });
        
        // Valores por defecto para porcentajes
        if (profitPercentageInput) profitPercentageInput.value = '30';  // 30% ganancia por defecto
        if (wholesalePercentageInput) wholesalePercentageInput.value = '20'; // 20% ganancia por defecto
        
        // Calcular precios inicialmente
        calculatePrices();
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
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/products/create.blade.php ENDPATH**/ ?>