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
        @if($showPaymentModal)
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
                <h2 style="color: #28a745; font-weight: bold; font-size: 2.2rem; margin: 0;">₲ {{ number_format($total_amount, 0, ',', '.') }}</h2>
                <p style="color: #6c757d; margin-top: 5px;">{{ count($cart) }} producto(s) en el carrito</p>
            </div>

            <!-- Métodos de Pago -->
            <div style="margin-bottom: 25px;">
                <h5 style="margin-bottom: 15px; color: #333;">💳 Método de Pago:</h5>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    <label style="background: {{ $payment_method === 'CASH' ? '#e7f7e7' : '#f8f9fa' }}; padding: 12px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $payment_method === 'CASH' ? '#28a745' : '#dee2e6' }}; transition: all 0.3s; text-align: center;">
                        <input type="radio" name="payment_method" value="CASH" wire:model.live="payment_method" style="margin-right: 5px;">
                        <strong>💵 Efectivo</strong>
                    </label>
                    <label style="background: {{ $payment_method === 'CARD' ? '#e7f7e7' : '#f8f9fa' }}; padding: 12px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $payment_method === 'CARD' ? '#28a745' : '#dee2e6' }}; transition: all 0.3s; text-align: center;">
                        <input type="radio" name="payment_method" value="CARD" wire:model.live="payment_method" style="margin-right: 5px;">
                        <strong>💳 Tarjeta</strong>
                    </label>
                    <label style="background: {{ $payment_method === 'CHEQUE' ? '#e7f7e7' : '#f8f9fa' }}; padding: 12px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $payment_method === 'CHEQUE' ? '#28a745' : '#dee2e6' }}; transition: all 0.3s; text-align: center;">
                        <input type="radio" name="payment_method" value="CHEQUE" wire:model.live="payment_method" style="margin-right: 5px;">
                        <strong>📝 Cheque</strong>
                    </label>
                    <label style="background: {{ $payment_method === 'TRANSFER' ? '#e7f7e7' : '#f8f9fa' }}; padding: 12px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $payment_method === 'TRANSFER' ? '#28a745' : '#dee2e6' }}; transition: all 0.3s; text-align: center;">
                        <input type="radio" name="payment_method" value="TRANSFER" wire:model.live="payment_method" style="margin-right: 5px;">
                        <strong>🏦 Transferencia</strong>
                    </label>
                </div>
            </div>

            <!-- Condición de Venta -->
            <div style="margin-bottom: 25px;">
                <h5 style="margin-bottom: 15px; color: #333;">📋 Condición de Venta:</h5>
                <div style="display: flex; gap: 15px;">
                    <label style="flex: 1; background: {{ $sale_condition === 'CONTADO' ? '#e7f7e7' : '#f8f9fa' }}; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $sale_condition === 'CONTADO' ? '#28a745' : '#dee2e6' }};">
                        <input type="radio" name="sale_condition" value="CONTADO" wire:model.live="sale_condition" style="margin-right: 8px;">
                        <strong>✅ Contado</strong>
                    </label>
                    <label style="flex: 1; background: {{ $sale_condition === 'CREDITO' ? '#e7f7e7' : '#f8f9fa' }}; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $sale_condition === 'CREDITO' ? '#28a745' : '#dee2e6' }};">
                        <input type="radio" name="sale_condition" value="CREDITO" wire:model.live="sale_condition" style="margin-right: 8px;">
                        <strong>📅 Crédito</strong>
                    </label>
                </div>
            </div>

            <!-- Campo de Efectivo (solo si es CASH y CONTADO) -->
            @if($payment_method === 'CASH' && $sale_condition === 'CONTADO')
            <div style="margin-bottom: 25px; background: #fff3cd; padding: 20px; border-radius: 10px; border: 1px solid #ffc107;">
                <h6 style="margin-bottom: 15px; color: #856404;">💰 Dinero Recibido:</h6>
                
                <!-- Botones rápidos -->
                <div style="margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" 
                        onclick="setCashAmount({{ $total_amount }})" 
                        style="background: #17a2b8; color: white; border: none; padding: 8px 12px; border-radius: 5px; font-size: 14px; cursor: pointer;">
                        Exacto
                    </button>
                    @php
                        $amounts = [50000, 100000, 200000];
                        foreach($amounts as $amount) {
                            if($amount >= $total_amount) {
                                echo '<button type="button" onclick="setCashAmount('.$amount.')" style="background: #6c757d; color: white; border: none; padding: 8px 12px; border-radius: 5px; font-size: 14px; cursor: pointer;">₲ '.number_format($amount, 0, ',', '.').'</button>';
                            }
                        }
                    @endphp
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
                
                @if($cash_received >= $total_amount && $cash_received > 0)
                <div style="margin-top: 15px; background: #d4edda; padding: 15px; border-radius: 8px; border: 1px solid #c3e6cb;">
                    <p style="color: #155724; margin: 0; font-size: 18px; font-weight: bold; text-align: center;">
                        💸 Cambio: ₲ {{ number_format($cash_received - $total_amount, 0, ',', '.') }}
                    </p>
                </div>
                @elseif($cash_received > 0)
                <div style="margin-top: 15px; background: #f8d7da; padding: 15px; border-radius: 8px; border: 1px solid #f5c6cb;">
                    <p style="color: #721c24; margin: 0; text-align: center;">
                        ⚠️ Faltan: ₲ {{ number_format($total_amount - $cash_received, 0, ',', '.') }}
                    </p>
                </div>
                @endif
            </div>
            @endif

            <!-- Sección de Venta a Crédito -->
            @if($sale_condition === 'CREDITO')
            <div style="margin-bottom: 25px; background: #fff3cd; padding: 20px; border-radius: 10px; border: 2px solid #ffc107;">
                <h6 style="margin-bottom: 15px; color: #856404; text-align: center;">💰 Pago Inicial (Crédito)</h6>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333;">Monto Abonado:</label>
                    <input 
                        type="number" 
                        wire:model.live.debounce.400ms="amount_paid"
                        style="width: 100%; padding: 12px; border: 2px solid #ffc107; border-radius: 8px; font-size: 18px; text-align: center; font-weight: bold;" 
                        min="0" 
                        max="{{ $total_amount }}"
                        step="1000"
                        placeholder="₲ 0"
                        onfocus="this.select()">
                </div>
                
                <div style="background: #f8d7da; padding: 15px; border-radius: 8px; border: 1px solid #f5c6cb;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="color: #721c24; font-weight: bold;">Total:</span>
                        <span style="color: #721c24; font-weight: bold;">₲ {{ number_format((float)$total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="color: #721c24;">Abonado:</span>
                        <span style="color: #721c24;">₲ {{ number_format((float)$amount_paid, 0, ',', '.') }}</span>
                    </div>
                    <div style="border-top: 2px solid #f5c6cb; margin: 10px 0; padding-top: 10px; display: flex; justify-content: space-between;">
                        <span style="color: #721c24; font-weight: bold; font-size: 16px;">Saldo Pendiente:</span>
                        <span style="color: #dc3545; font-weight: bold; font-size: 18px;">₲ {{ number_format(max(0, (float)$total_amount - (float)$amount_paid), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tipo de Comprobante -->
            <div style="margin-bottom: 25px;">
                <h5 style="margin-bottom: 15px; color: #333;">📄 Tipo de Comprobante:</h5>
                <div style="display: flex; gap: 15px;">
                    <label style="flex: 1; background: {{ $document_type === 'ticket' ? '#e7f7e7' : '#f8f9fa' }}; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $document_type === 'ticket' ? '#28a745' : '#dee2e6' }};">
                        <input type="radio" name="document_type" value="ticket" wire:model.live="document_type" style="margin-right: 8px;">
                        <strong>🧾 Ticket</strong>
                    </label>
                    <label style="flex: 1; background: {{ $document_type === 'factura' ? '#e7f7e7' : '#f8f9fa' }}; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $document_type === 'factura' ? '#28a745' : '#dee2e6' }};">
                        <input type="radio" name="document_type" value="factura" wire:model.live="document_type" style="margin-right: 8px;">
                        <strong>📋 Factura</strong>
                    </label>
                </div>
            </div>

            <!-- Campos de Cliente para Factura -->
            @if($document_type === 'factura')
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
            @endif

            <!-- Campos opcionales de Cliente para Ticket -->
            @if($document_type === 'ticket')
            <div style="margin-bottom: 25px; background: #f0f4ff; padding: 20px; border-radius: 10px; border: 1px solid #c0d0f0;">
                <h6 style="margin-bottom: 5px; color: #4a5568;">👤 Datos del Cliente <span style="font-weight: normal; font-size: 13px; color: #888;">(Opcional)</span></h6>
                <p style="font-size: 12px; color: #888; margin-bottom: 15px;">Si se completan, aparecerán en el ticket.</p>
                <div style="margin-bottom: 15px;">
                    <input type="text" wire:model.blur="customer_name" placeholder="Nombre del cliente" style="width: 100%; padding: 10px; border: 1px solid #c0d0f0; border-radius: 5px; font-size: 16px; background: #fff;">
                </div>
                <div style="margin-bottom: 15px;">
                    <input type="text" wire:model="customer_ruc" placeholder="CI / RUC" style="width: 100%; padding: 10px; border: 1px solid #c0d0f0; border-radius: 5px; font-size: 16px; background: #fff;">
                </div>
                <div>
                    <input type="text" wire:model="customer_address" placeholder="Dirección" style="width: 100%; padding: 10px; border: 1px solid #c0d0f0; border-radius: 5px; font-size: 16px; background: #fff;">
                </div>
            </div>
            @endif

            <!-- Botones -->
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button wire:click="closePaymentModal" style="background: #6c757d; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 16px;">
                    ❌ Cancelar
                </button>
                
                @php
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
                @endphp
                
                <button 
                    wire:click="processSale" 
                    @if($isDisabled) disabled @endif
                    style="background: {{ $isDisabled ? '#6c757d' : '#28a745' }}; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: {{ $isDisabled ? 'not-allowed' : 'pointer' }}; font-weight: bold; font-size: 16px;">
                    ✅ Confirmar Venta - ₲ {{ number_format($total_amount, 0, ',', '.') }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de Selección de Precios -->
    @if($showPriceSelectionModal && $selectedProduct)
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 99999; display: flex; align-items: flex-start; justify-content: center; overflow-y: auto; padding: 16px 0;">
        <div style="background: white; width: 90%; max-width: 500px; border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin: auto; max-height: calc(100vh - 32px); overflow-y: auto; display: flex; flex-direction: column;">
            
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
                    @if($selectedProduct->hasImage())
                        <img src="{{ $selectedProduct->getImageUrl('thumbnail') }}" alt="{{ $selectedProduct->name }}" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                    @endif
                    <div>
                        <h5 style="margin: 0; color: #333;">{{ $selectedProduct->name }}</h5>
                        <small style="color: #6c757d;">Código: {{ $selectedProduct->code }}</small>
                    </div>
                </div>
            </div>

            <!-- Cantidad -->
            <div style="margin-bottom: 20px;">
                <h6 style="margin-bottom: 10px; color: #333;">📦 Cantidad:</h6>
                <div style="display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <button type="button"
                        wire:click="decrementModalQty"
                        style="background:#6c757d;color:white;border:none;border-radius:8px;width:42px;height:42px;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;">−</button>
                    <input type="number"
                        wire:model.live="modalQuantity"
                        min="1"
                        style="width:80px;height:42px;text-align:center;font-size:20px;font-weight:bold;border:2px solid #007bff;border-radius:8px;outline:none;"
                        onclick="this.select()">
                    <button type="button"
                        wire:click="incrementModalQty"
                        style="background:#007bff;color:white;border:none;border-radius:8px;width:42px;height:42px;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
                </div>
            </div>

            <!-- Opciones de Precios -->
            <div style="margin-bottom: 20px;">
                <h6 style="margin-bottom: 15px; color: #333;">💰 Selecciona el precio a usar:</h6>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($availablePrices as $index => $price)
                    <div role="button" tabindex="0"
                         wire:click="selectPrice({{ $index }})"
                         style="background: #f8f9fa; border: 2px solid #007bff; padding: 15px; border-radius: 10px; cursor: pointer; text-align: left; transition: all 0.3s ease; display: flex; justify-content: space-between; align-items: center;"
                         onmouseover="this.style.background='#e7f1ff'; this.style.transform='translateY(-2px)'"
                         onmouseout="this.style.background='#f8f9fa'; this.style.transform='translateY(0)'">
                        <div>
                            <strong style="color: #007bff; font-size: 16px;">{{ $price['label'] }}</strong>
                            <br>
                            <small style="color: #6c757d;">
                                {{ $price['description'] ?? 'Sin descripción' }}
                            </small>
                        </div>
                        <div style="text-align: right; display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                            @if(!empty($price['hidden']))
                            <span class="price-hidden-badge-{{ $index }}" style="font-size: 20px; color: #adb5bd; letter-spacing: 5px; font-weight: bold; line-height:1;">• • • •</span>
                            <span class="price-visible-badge-{{ $index }}" style="display:none; font-size: 18px; font-weight: bold; color: #28a745;">
                                ₲ {{ number_format($price['value'], 0, ',', '.') }}
                            </span>
                            <button type="button"
                                    id="reveal-btn-{{ $index }}"
                                    onclick="event.stopPropagation(); togglePriceReveal({{ $index }})"
                                    style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 50%; width: 30px; height: 30px; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(102,126,234,0.45); transition: all 0.2s ease;"
                                    onmouseover="this.style.transform='scale(1.15)'; this.style.boxShadow='0 4px 14px rgba(102,126,234,0.65)'"
                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(102,126,234,0.45)'">
                                <i class="bi bi-eye-fill" id="eye-icon-{{ $index }}"></i>
                            </button>
                            @else
                            <span style="font-size: 18px; font-weight: bold; color: #28a745;">
                                ₲ {{ number_format($price['value'], 0, ',', '.') }}
                            </span>
                            @if($price['type'] === 'wholesale_price' && $price['value'] < $selectedProduct->sale_price)
                                <small style="color: #28a745;">
                                    ⬇️ {{ number_format((($selectedProduct->sale_price - $price['value']) / $selectedProduct->sale_price) * 100, 0) }}% menos
                                </small>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
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
    @endif

    <!-- Flash Messages -->
    @if (session('message'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

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
                    @if(!empty($search))
                    <button 
                        type="button" 
                        class="btn btn-sm btn-outline-secondary position-absolute" 
                        style="right: 10px; top: 50%; transform: translateY(-50%);" 
                        wire:click="clearSearch"
                        title="Limpiar búsqueda">
                        <i class="bi bi-x"></i>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Resultados de Búsqueda -->
            @if($searchResults && count($searchResults) > 0)
            <div class="mb-4">
                <h6 class="text-muted mb-3">
                    <i class="bi bi-search"></i> 
                    @if(empty($search))
                        Productos disponibles ({{ count($searchResults) }})
                    @else 
                        Resultados de búsqueda ({{ count($searchResults) }})
                    @endif
                </h6>
                <div class="row g-3">
                    @foreach($searchResults as $product)
                    <div class="col-md-6 col-lg-4">
                        <div class="card product-card h-100" wire:click="addToCart({{ $product->id }})" style="cursor: pointer;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start gap-3">
                                    @if($product->hasImage())
                                        <div class="product-image-container">
                                            <img src="{{ $product->getImageUrl('thumbnail') }}" 
                                                 alt="{{ $product->name }}" 
                                                 loading="eager"
                                                 onclick="event.stopPropagation(); showImageZoom('{{ $product->getImageUrl('medium') }}', '{{ $product->name }}')">
                                        </div>
                                    @else
                                        <div class="product-image-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="card-title fw-bold text-primary mb-0 text-truncate">{{ $product->name }}</h6>
                                            <span class="badge bg-success ms-2 flex-shrink-0">{{ $product->code }}</span>
                                        </div>
                                        @if($product->description)
                                            <p class="card-text text-muted small mb-2 text-truncate">{{ Str::limit($product->description, 40) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="h6 text-success fw-bold mb-0">₲ {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                            <small class="text-muted">
                                                <i class="bi bi-box-seam me-1"></i>{{ $product->stock ?? 0 }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Mensaje cuando no hay productos -->
            @if(!$searchResults || count($searchResults) == 0)
            <div class="text-center py-5">
                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">
                    @if(empty($search))
                        No hay productos disponibles
                    @else
                        No se encontraron productos con "{{ $search }}"
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Panel Lateral - Carrito -->
    <div class="col-lg-4">
        <div class="pos-sidebar p-4">
            <h5 class="mb-4">
                <i class="bi bi-cart3"></i> Carrito de Compras
                @if(!empty($cart))
                <span class="badge bg-primary ms-2">{{ array_sum(array_column($cart, 'quantity')) }}</span>
                @endif
            </h5>

            <!-- Items del Carrito -->
            @if(empty($cart))
            <div class="text-center py-4">
                <i class="bi bi-cart-x text-muted" style="font-size: 2rem;"></i>
                <p class="text-muted mt-2">El carrito está vacío</p>
            </div>
            @else
            <div class="cart-items mb-4" style="max-height: 300px; overflow-y: auto;">
                @foreach($cart as $index => $item)
                <div class="cart-item p-3 mb-2">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-semibold mb-1">{{ $item['product_name'] ?? $item['name'] ?? 'Producto' }}</h6>
                            @if(isset($item['price_label']) && $item['price_label'] !== 'Precio Venta')
                                <small class="text-info fw-semibold">
                                    <i class="bi bi-tag-fill"></i> {{ $item['price_label'] }}
                                </small>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeFromCart({{ $index }})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="input-group" style="width: 100px;">
                            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="updateQuantity({{ $index }}, {{ max(1, $item['quantity'] - 1) }})">-</button>
                            <input type="number" class="form-control form-control-sm text-center" value="{{ $item['quantity'] }}" wire:change="updateQuantity({{ $index }}, $event.target.value)" min="1">
                            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})">+</button>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">₲ {{ number_format(($item['unit_price'] ?? $item['price'] ?? 0) * $item['quantity'], 0, ',', '.') }}</div>
                            <small class="text-muted">₲ {{ number_format($item['unit_price'] ?? $item['price'] ?? 0, 0, ',', '.') }} c/u</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Totales -->
            <div class="total-section p-3 mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span class="fw-semibold">₲ {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>IVA (10%):</span>
                    <span class="fw-semibold">₲ {{ number_format($tax_amount, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between total-final p-3">
                    <span class="h5 mb-0">TOTAL:</span>
                    <span class="h4 mb-0">₲ {{ number_format($total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Botón Procesar Venta -->
            <button 
                wire:click="openPaymentModal" 
                class="btn btn-success btn-lg w-100 mt-3 btn-pos" 
                {{ empty($cart) ? 'disabled' : '' }}>
                <i class="bi bi-credit-card"></i>
                Procesar Venta
            </button>
            @endif
        </div>
    </div>

    <!-- Alertas dentro del div principal -->
    @if (session()->has('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Script principal -->
    <script>
        // Escuchar evento de venta completada para mostrar modal de impresión
        // El modal de impresión es manejado por Livewire ($showPrintModal)
        // Los botones del modal llaman directamente a directPrint(), openPreview(), downloadPDF()

        function openPreview(saleId, documentType) {
            var url = documentType === 'factura'
                ? '/pdf/preview/invoice/' + saleId
                : '/pdf/preview/ticket/' + saleId;
            window.open(url, '_blank');
        }

        function downloadPDF(saleId, documentType) {
            var url = documentType === 'factura'
                ? '/pdf/invoice/' + saleId
                : '/pdf/ticket/' + saleId;
            window.open(url, '_blank');
        }

        // Función para impresión directa Bluetooth
        function directPrint(saleId, documentType) {
            // Mostrar indicador de carga
            var loadingToast = showToast('Enviando a impresora...', 'info');
            
            console.log('Direct print called with:', { saleId: saleId, documentType: documentType });
            
            // Solo soportamos impresión directa para tickets por ahora
            if (documentType === 'ticket') {
                fetch('/print/bluetooth/' + saleId, {
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
                    } else {
                        showToast('Error: ' + (result.error || 'No se pudo imprimir'), 'error');
                        console.error('Bluetooth print error:', result);
                    }
                })
                .catch(function(error) {
                    showToast('Error: ' + error.message, 'error');
                    console.error('Bluetooth print error:', error);
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

        // Revelar/ocultar precio en el modal de selección de precio
        function togglePriceReveal(index) {
            var badge = document.querySelector('.price-hidden-badge-' + index);
            var visible = document.querySelector('.price-visible-badge-' + index);
            var icon = document.getElementById('eye-icon-' + index);
            if (!badge || !visible) return;
            if (badge.style.display === 'none') {
                badge.style.display = '';
                visible.style.display = 'none';
                if (icon) icon.className = 'bi bi-eye-fill';
            } else {
                badge.style.display = 'none';
                visible.style.display = '';
                if (icon) icon.className = 'bi bi-eye-slash-fill';
            }
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
            @this.set('cash_received', amount);
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

        // ── Agente de impresion Windows (print-agent.ps1 en localhost:18000) ──
        window.printWithAgent = function(printerName, base64Data) {
            return fetch('http://localhost:18000/print', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ printer: printerName, data: base64Data })
            }).then(function(r) { return r.json(); }).then(function(result) {
                if (!result.success) throw new Error(result.error || 'Error al imprimir');
            });
        };

        window.directPrint = function(saleId, documentType) {
            var printerType = '{{ $printerSettings->printer_type ?? 'thermal' }}';
            var winPrinter  = '{{ $printerSettings->default_printer ?? '' }}';
            var rawbtUrl = documentType === 'factura'
                ? '/print/rawbt/invoice/' + saleId
                : '/print/rawbt/' + saleId;

            // Modo PDF
            if (printerType === 'pdf') {
                var pdfUrl = documentType === 'factura' ? '/pdf/invoice/' + saleId : '/pdf/ticket/' + saleId;
                var printWin = window.open(pdfUrl, '_blank');
                if (printWin) { printWin.onload = function() { printWin.focus(); printWin.print(); }; }
                return;
            }

            var isAndroid = /android/i.test(navigator.userAgent);

            if (isAndroid) {
                // Android: PrintBridge en localhost:18000
                showToast('Enviando a impresora...', 'info');
                fetch(rawbtUrl, { headers: { 'Accept': 'application/json' } })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (!data.success) throw new Error(data.error || 'Error al generar documento');
                        return fetch('http://localhost:18000/print', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ base64: data.base64 })
                        });
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(result) {
                        if (result.success) showToast('Impreso correctamente', 'success');
                        else showToast('Error: ' + (result.error || 'No se pudo imprimir'), 'error');
                    })
                    .catch(function(e) { showToast('Error: ' + e.message + ' — ¿Está abierta la app PrintBridge?', 'error'); });

            } else if (winPrinter) {
                // Windows: agente local print-agent.ps1
                showToast('Enviando a impresora...', 'info');
                fetch(rawbtUrl, { headers: { 'Accept': 'application/json' } })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (!data.success) throw new Error(data.error || 'Error al generar documento');
                        return window.printWithAgent(winPrinter, data.base64);
                    })
                    .then(function() { showToast('Impreso correctamente', 'success'); })
                    .catch(function(e) {
                        showToast('Error: ' + e.message + ' — ¿Está corriendo print-agent.ps1?', 'error');
                    });

            } else {
                showToast('Configurá una impresora en Configuración → Facturación', 'warning');
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
    @if($showPrintModal)
    <div class="modal fade show" id="printModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);" aria-labelledby="printModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="printModalTitle">
                        <i class="bi bi-printer-fill me-2"></i>
                        {{ $lastDocumentType === 'factura' ? 'Factura Generada' : 'Ticket Generado' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closePrintModal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                    <h5>¡Venta procesada exitosamente!</h5>
                    <p class="text-muted">Número: {{ $lastSaleNumber }}</p>
                    <p>¿Cómo deseas imprimir el {{ $lastDocumentType }}?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" wire:click="closePrintModal">
                        <i class="bi bi-x-lg me-2"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-info" onclick="openPreview({{ $lastSaleId }}, '{{ $lastDocumentType }}')">
                        <i class="bi bi-eye me-2"></i>
                        Vista Previa
                    </button>
                    <button type="button" class="btn btn-warning" onclick="directPrint({{ $lastSaleId }}, '{{ $lastDocumentType }}')">
                        <i class="bi bi-printer-fill me-2"></i>
                        Imprimir Directo
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadPDF({{ $lastSaleId }}, '{{ $lastDocumentType }}')">
                        <i class="bi bi-download me-2"></i>
                        Descargar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
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
{{-- Final del archivo --}}