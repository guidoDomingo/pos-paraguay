<div>
    <style>
        .product-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: var(--bs-primary);
        }
        
        .product-card img {
            transition: transform 0.3s ease;
            width: 60px;
            height: 60px;
            object-fit: cover;
            object-position: center;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid #e9ecef;
        }
        
        .product-card img:hover {
            border-color: var(--bs-primary);
            transform: scale(1.1);
        }
        
        .product-card:hover img {
            transform: scale(1.05);
        }
        
        .product-image-container {
            width: 70px;
            height: 70px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 10px;
            flex-shrink: 0;
        }
        
        .product-image-placeholder {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 1.5rem;
            width: 60px;
            height: 60px;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }
        
        /* Modal para ampliar imagen */
        .image-zoom-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .image-zoom-modal.show {
            opacity: 1;
            visibility: visible;
        }
        
        .image-zoom-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transform: scale(0.7);
            transition: transform 0.3s ease;
        }
        
        .image-zoom-modal.show .image-zoom-content {
            transform: scale(1);
        }
        
        .image-zoom-content img {
            width: auto;
            height: auto;
            max-width: 400px;
            max-height: 400px;
            border-radius: 10px;
        }
        
        .close-zoom {
            position: absolute;
            top: 10px;
            right: 15px;
            background: #dc3545;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            line-height: 1;
        }
        
        .product-image-placeholder {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 2rem;
            height: 120px;
        }

        .search-input {
            padding-left: 45px;
            border-radius: 25px;
            border: 2px solid #e9ecef;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }
        
        /* Badge animado */
        .badge {
            transition: all 0.2s ease;
        }
        
        .product-card:hover .badge {
            transform: scale(1.1);
        }
        
        /* Efectos de carga de imágenes */
        .product-card img {
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        
        .product-image-loading {
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }
        
        .product-image-loaded {
            opacity: 1;
        }
        
        /* Responsivo para imágenes */
        @media (max-width: 768px) {
            .product-image-container {
                width: 50px;
                height: 50px;
            }
            
            .product-card img {
                width: 40px;
                height: 40px;
            }
            
            .product-image-placeholder {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            
            .card-body {
                padding: 0.75rem !important;
            }
        }
        
        @media (max-width: 576px) {
            .product-image-container {
                width: 45px;
                height: 45px;
            }
            
            .product-card img {
                width: 35px;
                height: 35px;
            }
            
            .product-image-placeholder {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
            
            .image-zoom-content {
                padding: 15px;
            }
            
            .image-zoom-content img {
                max-width: 280px;
                max-height: 280px;
            }
        }
    </style>

    <div class="row g-4">
        <!-- Modal de Pago Funcional -->
        <!--[if BLOCK]><![endif]--><?php if($showPaymentModal): ?>
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 99999; display: flex; align-items: center; justify-content: center;">
            <div style="background: white; width: 90%; max-width: 600px; border-radius: 15px; padding: 30px; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            
            <!-- Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #28a745; padding-bottom: 15px;">
                <h3 style="color: #28a745; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-credit-card"></i>
                    Procesar Pago
                </h3>
                <button wire:click="closePaymentModal" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 50%; cursor: pointer; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                    ✕
                </button>
            </div>

            <!-- Total a Pagar -->
            <div style="text-align: center; margin-bottom: 30px; background: #f8f9fa; padding: 20px; border-radius: 10px;">
                <h4 style="color: #333; margin-bottom: 10px;">TOTAL A PAGAR</h4>
                <h2 style="color: #28a745; font-weight: bold; font-size: 2.2rem; margin: 0;">₲ <?php echo e(number_format($total_amount, 0, ',', '.')); ?></h2>
                <p style="color: #6c757d; margin-top: 5px;"><?php echo e(count($cart)); ?> producto(s) en el carrito</p>
            </div>

            <!-- Métodos de Pago -->
            <div style="margin-bottom: 25px;">
                <h5 style="margin-bottom: 15px; color: #333;">💳 Método de Pago:</h5>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    <label style="background: <?php echo e($payment_method === 'CASH' ? '#e7f7e7' : '#f8f9fa'); ?>; padding: 12px; border-radius: 8px; cursor: pointer; border: 2px solid <?php echo e($payment_method === 'CASH' ? '#28a745' : '#dee2e6'); ?>; transition: all 0.3s; text-align: center;">
                        <input type="radio" name="payment_method" value="CASH" wire:model.live="payment_method" style="margin-right: 5px;">
                        <strong>💵 Efectivo</strong>
                    </label>
                    <label style="background: <?php echo e($payment_method === 'CARD' ? '#e7f7e7' : '#f8f9fa'); ?>; padding: 12px; border-radius: 8px; cursor: pointer; border: 2px solid <?php echo e($payment_method === 'CARD' ? '#28a745' : '#dee2e6'); ?>; transition: all 0.3s; text-align: center;">
                        <input type="radio" name="payment_method" value="CARD" wire:model.live="payment_method" style="margin-right: 5px;">
                        <strong>💳 Tarjeta</strong>
                    </label>
                    <label style="background: <?php echo e($payment_method === 'CHEQUE' ? '#e7f7e7' : '#f8f9fa'); ?>; padding: 12px; border-radius: 8px; cursor: pointer; border: 2px solid <?php echo e($payment_method === 'CHEQUE' ? '#28a745' : '#dee2e6'); ?>; transition: all 0.3s; text-align: center;">
                        <input type="radio" name="payment_method" value="CHEQUE" wire:model.live="payment_method" style="margin-right: 5px;">
                        <strong>📝 Cheque</strong>
                    </label>
                    <label style="background: <?php echo e($payment_method === 'TRANSFER' ? '#e7f7e7' : '#f8f9fa'); ?>; padding: 12px; border-radius: 8px; cursor: pointer; border: 2px solid <?php echo e($payment_method === 'TRANSFER' ? '#28a745' : '#dee2e6'); ?>; transition: all 0.3s; text-align: center;">
                        <input type="radio" name="payment_method" value="TRANSFER" wire:model.live="payment_method" style="margin-right: 5px;">
                        <strong>🏦 Transferencia</strong>
                    </label>
                </div>
            </div>

            <!-- Condición de Venta -->
            <div style="margin-bottom: 25px;">
                <h5 style="margin-bottom: 15px; color: #333;">📋 Condición de Venta:</h5>
                <div style="display: flex; gap: 15px;">
                    <label style="flex: 1; background: <?php echo e($sale_condition === 'CONTADO' ? '#e7f7e7' : '#f8f9fa'); ?>; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid <?php echo e($sale_condition === 'CONTADO' ? '#28a745' : '#dee2e6'); ?>;">
                        <input type="radio" name="sale_condition" value="CONTADO" wire:model.live="sale_condition" style="margin-right: 8px;">
                        <strong>✅ Contado</strong>
                    </label>
                    <label style="flex: 1; background: <?php echo e($sale_condition === 'CREDITO' ? '#e7f7e7' : '#f8f9fa'); ?>; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid <?php echo e($sale_condition === 'CREDITO' ? '#28a745' : '#dee2e6'); ?>;">
                        <input type="radio" name="sale_condition" value="CREDITO" wire:model.live="sale_condition" style="margin-right: 8px;">
                        <strong>📅 Crédito</strong>
                    </label>
                </div>
            </div>

            <!-- Campo de Efectivo (solo si es CASH y CONTADO) -->
            <!--[if BLOCK]><![endif]--><?php if($payment_method === 'CASH' && $sale_condition === 'CONTADO'): ?>
            <div style="margin-bottom: 25px; background: #fff3cd; padding: 20px; border-radius: 10px; border: 1px solid #ffc107;">
                <h6 style="margin-bottom: 15px; color: #856404;">💰 Dinero Recibido:</h6>
                
                <!-- Botones rápidos -->
                <div style="margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" 
                        onclick="setCashAmount(<?php echo e($total_amount); ?>)" 
                        style="background: #17a2b8; color: white; border: none; padding: 8px 12px; border-radius: 5px; font-size: 14px; cursor: pointer;">
                        Exacto
                    </button>
                    <?php
                        $amounts = [50000, 100000, 200000];
                        foreach($amounts as $amount) {
                            if($amount >= $total_amount) {
                                echo '<button type="button" onclick="setCashAmount('.$amount.')" style="background: #6c757d; color: white; border: none; padding: 8px 12px; border-radius: 5px; font-size: 14px; cursor: pointer;">₲ '.number_format($amount, 0, ',', '.').'</button>';
                            }
                        }
                    ?>
                </div>
                
                <input
                    type="number"
                    id="cash-input"
                    wire:model.blur="cash_received"
                    style="width: 100%; padding: 12px; border: 2px solid #ffc107; border-radius: 8px; font-size: 18px; text-align: center; font-weight: bold;"
                    min="0"
                    step="1000"
                    placeholder="0"
                    onfocus="this.select()">
                
                <!--[if BLOCK]><![endif]--><?php if($cash_received >= $total_amount && $cash_received > 0): ?>
                <div style="margin-top: 15px; background: #d4edda; padding: 15px; border-radius: 8px; border: 1px solid #c3e6cb;">
                    <p style="color: #155724; margin: 0; font-size: 18px; font-weight: bold; text-align: center;">
                        💸 Cambio: ₲ <?php echo e(number_format($cash_received - $total_amount, 0, ',', '.')); ?>

                    </p>
                </div>
                <?php elseif($cash_received > 0): ?>
                <div style="margin-top: 15px; background: #f8d7da; padding: 15px; border-radius: 8px; border: 1px solid #f5c6cb;">
                    <p style="color: #721c24; margin: 0; text-align: center;">
                        ⚠️ Faltan: ₲ <?php echo e(number_format($total_amount - $cash_received, 0, ',', '.')); ?>

                    </p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Sección de Venta a Crédito -->
            <!--[if BLOCK]><![endif]--><?php if($sale_condition === 'CREDITO'): ?>
            <div style="margin-bottom: 25px; background: #fff3cd; padding: 20px; border-radius: 10px; border: 2px solid #ffc107;">
                <h6 style="margin-bottom: 15px; color: #856404; text-align: center;">💰 Pago Inicial (Crédito)</h6>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Monto Abonado:</label>
                    <input 
                        type="number" 
                        wire:model.live.debounce.400ms="amount_paid"
                        style="width: 100%; padding: 12px; border: 2px solid #ffc107; border-radius: 8px; font-size: 18px; text-align: center; font-weight: bold;" 
                        min="0" 
                        max="<?php echo e($total_amount); ?>"
                        step="1000"
                        placeholder="₲ 0"
                        onfocus="this.select()">
                </div>
                
                <div style="background: #f8d7da; padding: 15px; border-radius: 8px; border: 1px solid #f5c6cb;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="color: #721c24; font-weight: bold;">Total:</span>
                        <span style="color: #721c24; font-weight: bold;">₲ <?php echo e(number_format((float)$total_amount, 0, ',', '.')); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="color: #721c24;">Abonado:</span>
                        <span style="color: #721c24;">₲ <?php echo e(number_format((float)$amount_paid, 0, ',', '.')); ?></span>
                    </div>
                    <div style="border-top: 2px solid #f5c6cb; margin: 10px 0; padding-top: 10px; display: flex; justify-content: space-between;">
                        <span style="color: #721c24; font-weight: bold; font-size: 16px;">Saldo Pendiente:</span>
                        <span style="color: #dc3545; font-weight: bold; font-size: 18px;">₲ <?php echo e(number_format(max(0, (float)$total_amount - (float)$amount_paid), 0, ',', '.')); ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Tipo de Comprobante -->
            <div style="margin-bottom: 25px;">
                <h5 style="margin-bottom: 15px; color: #333;">📄 Tipo de Comprobante:</h5>
                <div style="display: flex; gap: 15px;">
                    <label style="flex: 1; background: <?php echo e($document_type === 'ticket' ? '#e7f7e7' : '#f8f9fa'); ?>; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid <?php echo e($document_type === 'ticket' ? '#28a745' : '#dee2e6'); ?>;">
                        <input type="radio" name="document_type" value="ticket" wire:model.live="document_type" style="margin-right: 8px;">
                        <strong>🧾 Ticket</strong>
                    </label>
                    <label style="flex: 1; background: <?php echo e($document_type === 'factura' ? '#e7f7e7' : '#f8f9fa'); ?>; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid <?php echo e($document_type === 'factura' ? '#28a745' : '#dee2e6'); ?>;">
                        <input type="radio" name="document_type" value="factura" wire:model.live="document_type" style="margin-right: 8px;">
                        <strong>📋 Factura</strong>
                    </label>
                </div>
            </div>

            <!-- Campos de Cliente para Factura -->
            <!--[if BLOCK]><![endif]--><?php if($document_type === 'factura'): ?>
            <div style="margin-bottom: 25px; background: #fff3cd; padding: 20px; border-radius: 10px; border: 1px solid #ffc107;">
                <h6 style="margin-bottom: 15px; color: #856404;">👤 Datos del Cliente:</h6>
                <div style="margin-bottom: 15px;">
                    <input type="text" wire:model.blur="customer_name" placeholder="Nombre/Razón Social (Requerido)" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <input type="text" wire:model="customer_ruc" placeholder="RUC/CI" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                <div>
                    <input type="text" wire:model="customer_address" placeholder="Dirección" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Botones -->
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button wire:click="closePaymentModal" style="background: #6c757d; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 16px;">
                    ❌ Cancelar
                </button>
                
                <?php
                    // Validaciones para habilitar el botón
                    $isDisabled = false;
                    
                    // Si es efectivo y contado, debe tener efectivo suficiente
                    if ($payment_method === 'CASH' && $sale_condition === 'CONTADO' && $cash_received < $total_amount) {
                        $isDisabled = true;
                    }
                    
                    // Si es factura, debe tener nombre de cliente
                    if ($document_type === 'factura' && empty($customer_name)) {
                        $isDisabled = true;
                    }
                ?>
                
                <button 
                    wire:click="processSale" 
                    <?php if($isDisabled): ?> disabled <?php endif; ?>
                    style="background: <?php echo e($isDisabled ? '#6c757d' : '#28a745'); ?>; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: <?php echo e($isDisabled ? 'not-allowed' : 'pointer'); ?>; font-weight: bold; font-size: 16px;">
                    ✅ Confirmar Venta - ₲ <?php echo e(number_format($total_amount, 0, ',', '.')); ?>

                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Modal de Selección de Precios -->
    <!--[if BLOCK]><![endif]--><?php if($showPriceSelectionModal && $selectedProduct): ?>
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 99999; display: flex; align-items: center; justify-content: center;">
        <div style="background: white; width: 90%; max-width: 500px; border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            
            <!-- Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #007bff; padding-bottom: 15px;">
                <h4 style="color: #007bff; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-tag"></i>
                    Seleccionar Precio
                </h4>
                <button wire:click="closePriceSelectionModal" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 50%; cursor: pointer; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                    ✕
                </button>
            </div>

            <!-- Información del Producto -->
            <div style="text-align: center; margin-bottom: 25px; background: #f8f9fa; padding: 15px; border-radius: 10px;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
                    <!--[if BLOCK]><![endif]--><?php if($selectedProduct->hasImage()): ?>
                        <img src="<?php echo e($selectedProduct->getImageUrl('thumbnail')); ?>" alt="<?php echo e($selectedProduct->name); ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <div>
                        <h5 style="margin: 0; color: #333;"><?php echo e($selectedProduct->name); ?></h5>
                        <small style="color: #6c757d;">Código: <?php echo e($selectedProduct->code); ?></small>
                    </div>
                </div>
            </div>

            <!-- Opciones de Precios -->
            <div style="margin-bottom: 20px;">
                <h6 style="margin-bottom: 15px; color: #333;">💰 Selecciona el precio a usar:</h6>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $availablePrices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button wire:click="selectPrice(<?php echo e($index); ?>)" 
                            style="background: #f8f9fa; border: 2px solid #007bff; padding: 15px; border-radius: 10px; cursor: pointer; text-align: left; transition: all 0.3s ease; display: flex; justify-content: space-between; align-items: center;"
                            onmouseover="this.style.background='#e7f1ff'; this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.background='#f8f9fa'; this.style.transform='translateY(0)'">
                        <div>
                            <strong style="color: #007bff; font-size: 16px;"><?php echo e($price['label']); ?></strong>
                            <br>
                            <small style="color: #6c757d;">
                                <?php echo e($price['description'] ?? 'Sin descripción'); ?>

                            </small>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 18px; font-weight: bold; color: #28a745;">
                                ₲ <?php echo e(number_format($price['value'], 0, ',', '.')); ?>

                            </span>
                            <!--[if BLOCK]><![endif]--><?php if($price['type'] === 'wholesale_price' && $price['value'] < $selectedProduct->sale_price): ?>
                                <br>
                                <small style="color: #28a745;">
                                    ⬇️ <?php echo e(number_format((($selectedProduct->sale_price - $price['value']) / $selectedProduct->sale_price) * 100, 0)); ?>% menos
                                </small>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <!-- Botón Cancelar -->
            <div style="text-align: center;">
                <button wire:click="closePriceSelectionModal" 
                        style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 14px;">
                    <i class="bi bi-x-circle me-1"></i>
                    Cancelar
                </button>
            </div>

        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Flash Messages -->
    <!--[if BLOCK]><![endif]--><?php if(session('message')): ?>
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?php echo e(session('message')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(session('error')): ?>
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Panel Principal - Productos y Búsqueda -->
    <div class="col-lg-8">
        <div class="pos-main p-4">
            <!-- Barra de Búsqueda -->
            <div class="mb-4">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        class="form-control search-input"
                        placeholder="Buscar productos por código, nombre o código de barras..."
                        autocomplete="off"
                    >
                    <!--[if BLOCK]><![endif]--><?php if(!empty($search)): ?>
                    <button 
                        type="button" 
                        class="btn btn-sm btn-outline-secondary position-absolute" 
                        style="right: 10px; top: 50%; transform: translateY(-50%);" 
                        wire:click="clearSearch"
                        title="Limpiar búsqueda">
                        <i class="bi bi-x"></i>
                    </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <!-- Resultados de Búsqueda -->
            <!--[if BLOCK]><![endif]--><?php if($searchResults && count($searchResults) > 0): ?>
            <div class="mb-4">
                <h6 class="text-muted mb-3">
                    <i class="bi bi-search"></i> 
                    <!--[if BLOCK]><![endif]--><?php if(empty($search)): ?>
                        Productos disponibles (<?php echo e(count($searchResults)); ?>)
                    <?php else: ?> 
                        Resultados de búsqueda (<?php echo e(count($searchResults)); ?>)
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </h6>
                <div class="row g-3">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card product-card h-100" wire:click="addToCart(<?php echo e($product->id); ?>)" style="cursor: pointer;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start gap-3">
                                    <!--[if BLOCK]><![endif]--><?php if($product->hasImage()): ?>
                                        <div class="product-image-container">
                                            <img src="<?php echo e($product->getImageUrl('thumbnail')); ?>" 
                                                 alt="<?php echo e($product->name); ?>" 
                                                 loading="eager"
                                                 onclick="event.stopPropagation(); showImageZoom('<?php echo e($product->getImageUrl('medium')); ?>', '<?php echo e($product->name); ?>')">
                                        </div>
                                    <?php else: ?>
                                        <div class="product-image-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="card-title fw-bold text-primary mb-0 text-truncate"><?php echo e($product->name); ?></h6>
                                            <span class="badge bg-success ms-2 flex-shrink-0"><?php echo e($product->code); ?></span>
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php if($product->description): ?>
                                            <p class="card-text text-muted small mb-2 text-truncate"><?php echo e(Str::limit($product->description, 40)); ?></p>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="h6 text-success fw-bold mb-0">₲ <?php echo e(number_format($product->sale_price, 0, ',', '.')); ?></span>
                                            <small class="text-muted">
                                                <i class="bi bi-box-seam me-1"></i><?php echo e($product->stock ?? 0); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Mensaje cuando no hay productos -->
            <!--[if BLOCK]><![endif]--><?php if(!$searchResults || count($searchResults) == 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">
                    <!--[if BLOCK]><![endif]--><?php if(empty($search)): ?>
                        No hay productos disponibles
                    <?php else: ?>
                        No se encontraron productos con "<?php echo e($search); ?>"
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </p>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Panel Lateral - Carrito -->
    <div class="col-lg-4">
        <div class="pos-sidebar p-4">
            <h5 class="mb-4">
                <i class="bi bi-cart3"></i> Carrito de Compras
                <!--[if BLOCK]><![endif]--><?php if(!empty($cart)): ?>
                <span class="badge bg-primary ms-2"><?php echo e(array_sum(array_column($cart, 'quantity'))); ?></span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </h5>

            <!-- Items del Carrito -->
            <!--[if BLOCK]><![endif]--><?php if(empty($cart)): ?>
            <div class="text-center py-4">
                <i class="bi bi-cart-x text-muted" style="font-size: 2rem;"></i>
                <p class="text-muted mt-2">El carrito está vacío</p>
            </div>
            <?php else: ?>
            <div class="cart-items mb-4" style="max-height: 300px; overflow-y: auto;">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="cart-item p-3 mb-2">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-semibold mb-1"><?php echo e($item['product_name'] ?? $item['name'] ?? 'Producto'); ?></h6>
                            <!--[if BLOCK]><![endif]--><?php if(isset($item['price_label']) && $item['price_label'] !== 'Precio Venta'): ?>
                                <small class="text-info fw-semibold">
                                    <i class="bi bi-tag-fill"></i> <?php echo e($item['price_label']); ?>

                                </small>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeFromCart(<?php echo e($index); ?>)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="input-group" style="width: 100px;">
                            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="updateQuantity(<?php echo e($index); ?>, <?php echo e(max(1, $item['quantity'] - 1)); ?>)">-</button>
                            <input type="number" class="form-control form-control-sm text-center" value="<?php echo e($item['quantity']); ?>" wire:change="updateQuantity(<?php echo e($index); ?>, $event.target.value)" min="1">
                            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="updateQuantity(<?php echo e($index); ?>, <?php echo e($item['quantity'] + 1); ?>)">+</button>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">₲ <?php echo e(number_format(($item['unit_price'] ?? $item['price'] ?? 0) * $item['quantity'], 0, ',', '.')); ?></div>
                            <small class="text-muted">₲ <?php echo e(number_format($item['unit_price'] ?? $item['price'] ?? 0, 0, ',', '.')); ?> c/u</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Totales -->
            <div class="total-section p-3 mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span class="fw-semibold">₲ <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>IVA (10%):</span>
                    <span class="fw-semibold">₲ <?php echo e(number_format($tax_amount, 0, ',', '.')); ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between total-final p-3">
                    <span class="h5 mb-0">TOTAL:</span>
                    <span class="h4 mb-0">₲ <?php echo e(number_format($total_amount, 0, ',', '.')); ?></span>
                </div>
            </div>

            <!-- Botón Procesar Venta -->
            <button 
                wire:click="openPaymentModal" 
                class="btn btn-success btn-lg w-100 mt-3 btn-pos" 
                <?php echo e(empty($cart) ? 'disabled' : ''); ?>>
                <i class="bi bi-credit-card"></i>
                Procesar Venta
            </button>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Alertas dentro del div principal -->
    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(session()->has('error')): ?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Script principal -->
    <script>
        // Escuchar evento de venta completada para mostrar modal de impresión
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up Livewire listeners');
        });
        
        document.addEventListener('livewire:initialized', function() {
            console.log('Livewire initialized, setting up sale-completed listener');
            Livewire.on('sale-completed', function(data) {
                console.log('sale-completed event received:', data);
                var saleId = data.saleId;
                var documentType = data.documentType; 
                var saleNumber = data.saleNumber;
                showPrintModal(saleId, documentType, saleNumber);
            });
        });

        // También intentar con el evento directo de Livewire
        if (typeof Livewire !== 'undefined') {
            Livewire.on('sale-completed', function(data) {
                console.log('sale-completed event received (direct):', data);
                var saleId = data.saleId;
                var documentType = data.documentType;
                var saleNumber = data.saleNumber;
                showPrintModal(saleId, documentType, saleNumber);
            });
        }

        // Función para mostrar modal de impresión
        function showPrintModal(saleId, documentType, saleNumber) {
            console.log('showPrintModal called with:', { saleId: saleId, documentType: documentType, saleNumber: saleNumber });
            var modal = document.getElementById('printModal');
            var modalTitle = document.getElementById('printModalTitle');
            var modalBody = document.getElementById('printModalBody');
            var printBtn = document.getElementById('printConfirmBtn');
            var directPrintBtn = document.getElementById('directPrintBtn');
            var previewBtn = document.getElementById('previewBtn');
            
            if (!modal) {
                console.error('Modal printModal not found');
                return;
            }
            
            modalTitle.textContent = documentType === 'factura' ? 'Factura Generada' : 'Ticket Generado';
            modalBody.innerHTML = '<div class="text-center">' +
                '<i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>' +
                '<h5>¡Venta procesada exitosamente!</h5>' +
                '<p class="text-muted">Número: ' + saleNumber + '</p>' +
                '<p>¿Cómo deseas imprimir el ' + documentType + '?</p>' +
                '</div>';
            
            // Configurar botón de descarga PDF
            printBtn.onclick = function() {
                if (documentType === 'factura') {
                    window.open('/pdf/invoice/' + saleId, '_blank');
                } else {
                    window.open('/pdf/ticket/' + saleId, '_blank');
                }
                bootstrap.Modal.getInstance(modal).hide();
            };

            // Configurar botón de impresión directa
            directPrintBtn.onclick = function() {
                directPrint(saleId, documentType);
                bootstrap.Modal.getInstance(modal).hide();
            };
            
            previewBtn.onclick = function() {
                if (documentType === 'factura') {
                    window.open('/pdf/preview/invoice/' + saleId, '_blank');
                } else {
                    window.open('/pdf/preview/ticket/' + saleId, '_blank');
                }
            };
            
            new bootstrap.Modal(modal).show();
        }

        // Función para impresión directa
        function directPrint(saleId, documentType) {
            // Mostrar indicador de carga
            var loadingToast = showToast('Enviando a impresora...', 'info');
            
            console.log('Direct print called with:', { saleId: saleId, documentType: documentType });
            
            // Solo soportamos impresión directa para tickets por ahora
            if (documentType === 'ticket') {
                // Primero intentar el método de servidor directo
                fetch('/direct-print-raw/' + saleId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(function(response) {
                    console.log('Print response status:', response.status);
                    if (!response.ok) {
                        return response.json().then(function(data) {
                            throw new Error(data.error || 'Error HTTP: ' + response.status);
                        });
                    }
                    return response.json();
                })
                .then(function(result) {
                    if (result.success) {
                        showToast('Ticket enviado a impresora correctamente', 'success');
                        if (result.printerInfo) {
                            console.log('Impresora utilizada:', result.printerInfo);
                        }
                        console.log('Print job sent successfully:', result);
                    } else {
                        if (result.fallback === 'browser_print') {
                            console.log('Impresión directa no disponible, usando método silencioso...');
                            showToast('Usando método de impresión silencioso...', 'info');
                            printSilently(saleId);
                        } else {
                            throw new Error(result.error || 'Error desconocido en la impresión');
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Print error:', error);
                    console.log('Intentando método de impresión silencioso...');
                    printSilently(saleId);
                });
            } else {
                // Para facturas, usar PDF por ahora
                window.open('/pdf/invoice/' + saleId, '_blank');
                showToast('Factura descargada', 'success');
            }
        }

        // Nueva función para imprimir silenciosamente
        function printSilently(saleId) {
            showToast('Preparando impresión silenciosa...', 'info');
            
            // Obtener contenido del ticket
            fetch('/direct-print/' + saleId)
                .then(function(response) { return response.text(); })
                .then(function(content) {
                    // Crear ventana de impresión completamente oculta
                    var printWindow = window.open('', '_blank', 'width=1,height=1,left=-9999,top=-9999,menubar=no,toolbar=no,scrollbars=no');
                    
                    if (!printWindow) {
                        showToast('Error: Bloqueador de pop-ups activo', 'error');
                        return;
                    }
                    
                    // HTML optimizado para impresión térmica
                    var printHTML = '<!DOCTYPE html>' +
                        '<html><head>' +
                        '<title>Ticket</title>' +
                        '<style>' +
                        '@media print {' +
                        '@page { size: 80mm auto; margin: 0; }' +
                        'body { margin: 0; padding: 2mm; font-family: "Courier New", monospace; font-size: 11px; line-height: 1.1; }' +
                        '}' +
                        '@media screen { body { display: none; } }' +
                        '</style>' +
                        '</head>' +
                        '<body>' +
                        '<pre>' + escapeHtml(content) + '</pre>' +
                        '</body>' +
                        '</html>';
                    
                    printWindow.document.write(printHTML);
                    printWindow.document.close();
                    
                    // Esperar que se cargue y luego imprimir automáticamente
                    setTimeout(function() {
                        try {
                            printWindow.focus();
                            
                            // Imprimir inmediatamente
                            printWindow.print();
                            
                            showToast('Enviado a impresora', 'success');
                            
                            // Cerrar ventana después de imprimir
                            setTimeout(function() {
                                try {
                                    printWindow.close();
                                } catch (e) {
                                    console.log('Ventana ya cerrada');
                                }
                            }, 2000);
                            
                        } catch (e) {
                            console.error('Error en impresión silenciosa:', e);
                            showToast('Error en impresión: ' + e.message, 'error');
                            printWindow.close();
                        }
                    }, 1000);
                    
                })
                .catch(function(error) {
                    console.error('Error al obtener contenido para impresión silenciosa:', error);
                    showToast('Error: ' + error.message, 'error');
                });
        }

        // Método de fallback usando iframe
        function printWithIframe(saleId) {
            // En lugar de usar iframe que puede mostrar diálogo, usar una técnica más silenciosa
            
            // Crear un elemento de impresión invisible
            var printDiv = document.createElement('div');
            printDiv.style.position = 'absolute';
            printDiv.style.left = '-9999px';
            printDiv.style.top = '-9999px';
            printDiv.style.width = '80mm';
            printDiv.style.fontFamily = 'Courier New, monospace';
            printDiv.style.fontSize = '11px';
            printDiv.style.lineHeight = '1.2';
            printDiv.style.color = 'black';
            printDiv.style.backgroundColor = 'white';
            
            // Obtener contenido del ticket
            fetch('/direct-print/' + saleId)
                .then(function(response) { return response.text(); })
                .then(function(content) {
                    printDiv.innerHTML = '<pre>' + escapeHtml(content) + '</pre>';
                    document.body.appendChild(printDiv);
                    
                    // Crear CSS específico para impresión
                    var style = document.createElement('style');
                    style.textContent = `
                        @media print {
                            * { visibility: hidden; }
                            #print-content-${saleId}, #print-content-${saleId} * { visibility: visible; }
                            #print-content-${saleId} {
                                position: absolute;
                                left: 0;
                                top: 0;
                                width: 80mm;
                                font-family: 'Courier New', monospace;
                                font-size: 11px;
                                margin: 0;
                                padding: 0;
                            }
                            @page {
                                size: 80mm auto;
                                margin: 0;
                            }
                        }`;
                    document.head.appendChild(style);
                    
                    printDiv.id = 'print-content-' + saleId;
                    
                    // Usar técnica más avanzada para imprimir silenciosamente
                    setTimeout(function() {
                        // Guardar contenido original
                        var originalContents = document.body.innerHTML;
                        
                        // Reemplazar temporalmente el contenido del body
                        document.body.innerHTML = printDiv.outerHTML;
                        
                        // Imprimir
                        try {
                            // Para Chrome/Edge - Intentar imprimir sin diálogo
                            if (window.chrome && window.chrome.webstore) {
                                // Método específico para Chrome
                                window.print();
                            } else {
                                // Método estándar
                                window.print();
                            }
                        } catch (e) {
                            console.error('Error en impresión:', e);
                        }
                        
                        // Restaurar contenido original después de un breve delay
                        setTimeout(function() {
                            document.body.innerHTML = originalContents;
                            
                            // Reinicializar Livewire después de restaurar el contenido
                            if (window.Livewire) {
                                window.Livewire.restart();
                            }
                            
                            showToast('Impresión procesada', 'info');
                        }, 1000);
                        
                    }, 500);
                })
                .catch(function(error) {
                    console.error('Error al obtener contenido para imprimir:', error);
                    showToast('Error al obtener contenido: ' + error.message, 'error');
                    
                    // Limpiar elementos creados
                    if (printDiv.parentNode) {
                        printDiv.parentNode.removeChild(printDiv);
                    }
                });
        }

        // Función para impresión directa moderna
        function printDirectToDevice(content, saleId) {
            try {
                // Método 1: Usar Web Print API si está disponible
                if ('print' in window && 'navigator' in window && navigator.userAgent.includes('Chrome')) {
                    return printWithSilentMode(content, saleId);
                }
                
                // Método 2: Crear iframe oculto para impresión automática
                return printWithHiddenFrame(content, saleId);
                
            } catch (error) {
                console.error('Error in direct printing:', error);
                throw error;
            }
        }

        // Impresión silenciosa (Chrome/Edge)
        function printWithSilentMode(content, saleId) {
            return new Promise(function(resolve, reject) {
                var iframe = document.createElement('iframe');
                iframe.style.position = 'absolute';
                iframe.style.left = '-9999px';
                iframe.style.top = '-9999px';
                iframe.style.width = '1px';
                iframe.style.height = '1px';
                iframe.style.border = 'none';
                
                iframe.onload = function() {
                    try {
                        var doc = iframe.contentDocument || iframe.contentWindow.document;
                        doc.open();
                        doc.write('<!DOCTYPE html><html><head>' +
                            '<title>Ticket ' + saleId + '</title>' +
                            '<style>' +
                                '@page { size: 80mm auto; margin: 0; }' +
                                'body { font-family: "Courier New", monospace; font-size: 11px; line-height: 1.1; margin: 0; padding: 2mm;' +
                                        'white-space: pre-wrap; width: 76mm; color: black; }' +
                                '@media print { body { margin: 0; padding: 1mm; -webkit-print-color-adjust: exact; } }' +
                            '</style>' +
                            '</head>' +
                            '<body>' + escapeHtml(content) + '</body>' +
                            '</html>');
                        doc.close();
                        
                        // Esperar un momento y luego imprimir
                        setTimeout(function() {
                            iframe.contentWindow.focus();
                            iframe.contentWindow.print();
                            
                            // Limpiar después de imprimir
                            setTimeout(function() {
                                document.body.removeChild(iframe);
                                resolve();
                            }, 1000);
                        }, 500);
                        
                    } catch (error) {
                        document.body.removeChild(iframe);
                        reject(error);
                    }
                };
                
                iframe.onerror = function() {
                    document.body.removeChild(iframe);
                    reject(new Error('Error al cargar iframe de impresión'));
                };
                
                document.body.appendChild(iframe);
            });
        }

        // Impresión con frame oculto
        function printWithHiddenFrame(content, saleId) {
            return new Promise(function(resolve, reject) {
                var printWindow = window.open('', '_blank', 'width=300,height=300,left=9999,top=9999');
                
                if (!printWindow) {
                    reject(new Error('No se pudo abrir ventana de impresión (bloqueador de pop-ups?)'));
                    return;
                }
                
                printWindow.document.write(
                    '<!DOCTYPE html><html><head>' +
                    '<title>Ticket ' + saleId + '</title>' +
                    '<style>' +
                        '@page { size: 80mm auto; margin: 0; }' +
                        'body { font-family: "Courier New", monospace; font-size: 11px; line-height: 1.1; margin: 0; padding: 2mm; white-space: pre-wrap; width: 76mm; }' +
                    '</style>' +
                    '</head>' +
                    '<body>' + escapeHtml(content) + '</body>' +
                    '</html>'
                );
                
                printWindow.document.close();
                
                printWindow.onload = function() {
                    setTimeout(function() {
                        printWindow.focus();
                        printWindow.print();
                        
                        // Manejar eventos de impresión
                        printWindow.onafterprint = function() {
                            printWindow.close();
                            resolve();
                        };
                        
                        // Fallback: cerrar después de 5 segundos
                        setTimeout(function() {
                            if (!printWindow.closed) {
                                printWindow.close();
                            }
                            resolve();
                        }, 5000);
                    }, 300);
                };
            });
        }

        // Fallback: ventana de impresión tradicional
        function openTraditionalPrintWindow(saleId) {
            fetch('/direct-print/' + saleId)
                .then(function(response) { return response.text(); })
                .then(function(content) {
                    var printWindow = window.open('', '_blank', 'width=400,height=600');
                    printWindow.document.write(
                        '<!DOCTYPE html><html><head>' +
                        '<title>Ticket ' + saleId + '</title>' +
                        '<style>' +
                            'body { font-family: "Courier New", monospace; font-size: 12px; white-space: pre-wrap; margin: 10px; }' +
                        '</style>' +
                        '</head>' +
                        '<body>' + escapeHtml(content) + '<br><br>' +
                        '<button onclick="window.print()">Imprimir</button>' +
                        '<button onclick="window.close()">Cerrar</button>' +
                        '</body>' +
                        '</html>'
                    );
                    printWindow.document.close();
                    showToast('Ventana de impresión abierta', 'info');
                })
                .catch(function(error) {
                    console.error('Print error:', error);
                    showToast('Error al imprimir: ' + error.message, 'error');
                });
        }

        // Función auxiliar para escapar HTML
        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // Función para mostrar toast notifications
        function showToast(message, type) {
            if (!type) type = 'info';
            var toast = document.createElement('div');
            var bgClass = type === 'success' ? 'success' : type === 'error' ? 'danger' : 'primary';
            toast.className = 'toast align-items-center text-white bg-' + bgClass + ' border-0';
            toast.setAttribute('role', 'alert');
            toast.innerHTML = 
                '<div class="d-flex">' +
                    '<div class="toast-body">' + message + '</div>' +
                    '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
                '</div>';
            
            // Crear contenedor de toasts si no existe
            var toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }
            
            toastContainer.appendChild(toast);
            
            var bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remover después de que se oculte
            toast.addEventListener('hidden.bs.toast', function() {
                toast.remove();
            });
            
            return toast;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    <script>
        // Funciones globales para los botones de efectivo
        function setCashAmount(amount) {
            var input = document.getElementById('cash-input');
            if (input) {
                input.value = amount;
            }
            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('cash_received', amount);
        }

        function updateCashReceived(value) {
            // Función para oninput del input de efectivo
        }

        // Hacer funciones disponibles globalmente
        window.setCashAmount = setCashAmount;
        window.updateCashReceived = updateCashReceived;

        // Funciones para el modal de impresión
        window.openPreview = function(saleId, documentType) {
            var url = documentType === 'factura' ? '/pdf/preview/invoice/' + saleId : '/pdf/preview/ticket/' + saleId;
            window.open(url, '_blank');
        };

        window.directPrint = function(saleId, documentType) {
            if (documentType === 'ticket') {
                fetch('/direct-print/' + saleId)
                    .then(function(response) {
                        if (!response.ok) throw new Error('Error en impresión');
                        return response.text();
                    })
                    .then(function(content) {
                        // Crear iframe oculto para impresión
                        var printFrame = document.createElement('iframe');
                        printFrame.style.cssText = 'position:fixed;right:0;bottom:0;width:0;height:0;border:0;';
                        document.body.appendChild(printFrame);
                        
                        var doc = printFrame.contentWindow.document;
                        doc.open();
                        var htmlContent = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Ticket</title><style>body{font-family:monospace;font-size:12px;margin:0;padding:10px;width:80mm;}@media print{body{margin:0;padding:0;}@page{size:80mm auto;margin:0;}}</style></head><body>' + content + '</body></html>';
                        doc.write(htmlContent);
                        doc.close();
                        
                        setTimeout(function() {
                            printFrame.contentWindow.print();
                            setTimeout(function() {
                                document.body.removeChild(printFrame);
                                if (window.closePrintModal) window.closePrintModal();
                            }, 1000);
                        }, 500);
                    })
                    .catch(function(error) {
                        alert('Error: ' + error.message);
                    });
            } else {
                var w = window.open('/pdf/invoice/' + saleId, '_blank');
                setTimeout(function() {
                    w.print();
                    if (window.closePrintModal) window.closePrintModal();
                }, 1000);
            }
        };

        window.downloadPDF = function(saleId, documentType) {
            var url = '';
            if (documentType === 'factura') {
                url = '/pdf/invoice/' + saleId;
            } else {
                url = '/pdf/ticket/' + saleId;
            }
            window.open(url, '_blank');
        };
        
        // Función auxiliar para cerrar modal
        window.closePrintModal = function() {
            var closeBtn = document.querySelector('[wire\\:click="closePrintModal"]');
            if (closeBtn) closeBtn.click();
        };
    </script>

    <!-- Modal de Confirmación de Impresión -->
    <!--[if BLOCK]><![endif]--><?php if($showPrintModal): ?>
    <div class="modal fade show" id="printModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);" aria-labelledby="printModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="printModalTitle">
                        <i class="bi bi-printer-fill me-2"></i>
                        <?php echo e($lastDocumentType === 'factura' ? 'Factura Generada' : 'Ticket Generado'); ?>

                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closePrintModal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                    <h5>¡Venta procesada exitosamente!</h5>
                    <p class="text-muted">Número: <?php echo e($lastSaleNumber); ?></p>
                    <p>¿Cómo deseas imprimir el <?php echo e($lastDocumentType); ?>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" wire:click="closePrintModal">
                        <i class="bi bi-x-lg me-2"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-info" onclick="openPreview(<?php echo e($lastSaleId); ?>, '<?php echo e($lastDocumentType); ?>')">
                        <i class="bi bi-eye me-2"></i>
                        Vista Previa
                    </button>
                    <button type="button" class="btn btn-warning" onclick="directPrint(<?php echo e($lastSaleId); ?>, '<?php echo e($lastDocumentType); ?>')">
                        <i class="bi bi-printer-fill me-2"></i>
                        Imprimir Directo
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF(<?php echo e($lastSaleId); ?>, '<?php echo e($lastDocumentType); ?>')">
                        <i class="bi bi-download me-2"></i>
                        Descargar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <script>
        // Inicialización de imágenes
        function initializeImages() {
            const images = document.querySelectorAll('.product-image-container img');
            images.forEach(img => {
                if (!img.complete) {
                    img.classList.add('product-image-loading');
                    img.addEventListener('load', function() {
                        this.classList.remove('product-image-loading');
                        this.classList.add('product-image-loaded');
                    });
                } else {
                    img.classList.add('product-image-loaded');
                }
            });
        }
        
        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', initializeImages);
        
        // Ejecutar después de cada actualización de Livewire
        document.addEventListener('livewire:updated', function() {
            setTimeout(initializeImages, 50);
        });
        
        // También para navegación de Livewire
        document.addEventListener('livewire:navigated', function() {
            setTimeout(initializeImages, 50);
        });
        
        // Funciones para zoom de imagen
        function showImageZoom(imageUrl, productName) {
            const modal = document.getElementById('imageZoomModal');
            const modalImg = document.getElementById('zoomedImage');
            const modalTitle = document.getElementById('zoomImageTitle');
            
            modalImg.src = imageUrl;
            modalTitle.textContent = productName;
            modal.classList.add('show');
        }
        
        function closeImageZoom() {
            const modal = document.getElementById('imageZoomModal');
            modal.classList.remove('show');
        }
        
        // Cerrar modal al hacer clic fuera de la imagen
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('image-zoom-modal')) {
                closeImageZoom();
            }
        });
        
        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageZoom();
            }
        });
    </script>

    <!-- Modal para zoom de imagen de producto -->
    <div id="imageZoomModal" class="image-zoom-modal">
        <div class="image-zoom-content">
            <button class="close-zoom" onclick="closeImageZoom()">&times;</button>
            <div class="text-center">
                <h5 id="zoomImageTitle" class="mb-3 text-primary"></h5>
                <img id="zoomedImage" src="" alt="Imagen ampliada">
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\bodega-app\resources\views/livewire/pos-terminal.blade.php ENDPATH**/ ?>