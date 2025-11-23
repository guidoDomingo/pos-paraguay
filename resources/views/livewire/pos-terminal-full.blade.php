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
                <div style="display: flex; gap: 15px;">
                    <label style="flex: 1; background: {{ $payment_method === 'CASH' ? '#e7f7e7' : '#f8f9fa' }}; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $payment_method === 'CASH' ? '#28a745' : '#dee2e6' }}; transition: all 0.3s;">
                        <input type="radio" name="payment_method" value="CASH" wire:model="payment_method" style="margin-right: 8px;">
                        <strong>💵 Efectivo</strong>
                    </label>
                    <label style="flex: 1; background: {{ $payment_method === 'TRANSFER' ? '#e7f7e7' : '#f8f9fa' }}; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $payment_method === 'TRANSFER' ? '#28a745' : '#dee2e6' }}; transition: all 0.3s;">
                        <input type="radio" name="payment_method" value="TRANSFER" wire:model="payment_method" style="margin-right: 8px;">
                        <strong>🏦 Transferencia</strong>
                    </label>
                </div>
            </div>

            <!-- Campo de Efectivo -->
            @if($payment_method === 'CASH')
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
                    value="{{ $cash_received }}"
                    style="width: 100%; padding: 12px; border: 2px solid #ffc107; border-radius: 8px; font-size: 18px; text-align: center; font-weight: bold;" 
                    min="0" 
                    step="1000"
                    placeholder="0"
                    onfocus="this.select()"
                    oninput="updateCashReceived(this.value)"
                    onblur="@this.set('cash_received', this.value || 0)">
                
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

            <!-- Tipo de Comprobante -->
            <div style="margin-bottom: 25px;">
                <h5 style="margin-bottom: 15px; color: #333;">📄 Tipo de Comprobante:</h5>
                <div style="display: flex; gap: 15px;">
                    <label style="flex: 1; background: {{ $document_type === 'ticket' ? '#e7f7e7' : '#f8f9fa' }}; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $document_type === 'ticket' ? '#28a745' : '#dee2e6' }};">
                        <input type="radio" name="document_type" value="ticket" wire:model="document_type" style="margin-right: 8px;">
                        <strong>🧾 Ticket</strong>
                    </label>
                    <label style="flex: 1; background: {{ $document_type === 'factura' ? '#e7f7e7' : '#f8f9fa' }}; padding: 15px; border-radius: 8px; cursor: pointer; border: 2px solid {{ $document_type === 'factura' ? '#28a745' : '#dee2e6' }};">
                        <input type="radio" name="document_type" value="factura" wire:model="document_type" style="margin-right: 8px;">
                        <strong>📋 Factura</strong>
                    </label>
                </div>
            </div>

            <!-- Campos de Cliente para Factura -->
            @if($document_type === 'factura')
            <div style="margin-bottom: 25px; background: #fff3cd; padding: 20px; border-radius: 10px; border: 1px solid #ffc107;">
                <h6 style="margin-bottom: 15px; color: #856404;">👤 Datos del Cliente:</h6>
                <div style="margin-bottom: 15px;">
                    <input type="text" wire:model="customer_name" placeholder="Nombre/Razón Social (Requerido)" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <input type="text" wire:model="customer_ruc" placeholder="RUC/CI" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
                <div>
                    <input type="text" wire:model="customer_address" placeholder="Dirección" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                </div>
            </div>
            @endif

            <!-- Botones -->
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button wire:click="closePaymentModal" style="background: #6c757d; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-size: 16px;">
                    ❌ Cancelar
                </button>
                
                @php
                    $isDisabled = ($payment_method === 'CASH' && $cash_received < $total_amount) || ($sale_type === 'INVOICE' && empty($customer_name));
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
                        wire:model="search" 
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
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title fw-bold text-primary mb-0">{{ $product->name }}</h6>
                                    <span class="badge bg-success">{{ $product->code }}</span>
                                </div>
                                <p class="card-text text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h6 text-success fw-bold mb-0">₲ {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                    <small class="text-muted">Stock: {{ $product->stock ?? 0 }}</small>
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
                        <h6 class="fw-semibold mb-1">{{ $item['product_name'] ?? $item['name'] ?? 'Producto' }}</h6>
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
                        <span class="fw-bold">₲ {{ number_format(($item['unit_price'] ?? $item['price'] ?? 0) * $item['quantity'], 0, ',', '.') }}</span>
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

<script>
    let cashInputTimeout;
    
    function updateCashReceived(value) {
        // Limpiar timeout anterior
        clearTimeout(cashInputTimeout);
        
        // Esperar 500ms después de que el usuario deje de escribir
        cashInputTimeout = setTimeout(() => {
            const numericValue = parseFloat(value) || 0;
            @this.set('cash_received', numericValue);
        }, 500);
    }
    
    function setCashAmount(amount) {
        const cashInput = document.getElementById('cash-input');
        if (cashInput) {
            cashInput.value = amount;
            cashInput.focus();
            // Actualizar Livewire inmediatamente
            @this.set('cash_received', amount);
        }
    }
    
    // Actualizar el campo cuando cambie desde los botones
    document.addEventListener('livewire:updated', function () {
        const cashInput = document.getElementById('cash-input');
        if (cashInput && !cashInput.matches(':focus')) {
            cashInput.value = @this.get('cash_received') || '';
        }
    });

    // Escuchar evento de venta completada para mostrar modal de impresión
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('sale-completed', (data) => {
            const { saleId, documentType, saleNumber } = data;
            showPrintModal(saleId, documentType, saleNumber);
        });
    });

    // Función para mostrar modal de impresión
    function showPrintModal(saleId, documentType, saleNumber) {
        const modal = document.getElementById('printModal');
        const modalTitle = document.getElementById('printModalTitle');
        const modalBody = document.getElementById('printModalBody');
        const printBtn = document.getElementById('printConfirmBtn');
        const previewBtn = document.getElementById('previewBtn');
        
        modalTitle.textContent = documentType === 'factura' ? 'Factura Generada' : 'Ticket Generado';
        modalBody.innerHTML = `
            <div class="text-center">
                <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                <h5>¡Venta procesada exitosamente!</h5>
                <p class="text-muted">Número: ${saleNumber}</p>
                <p>¿Deseas imprimir el ${documentType}?</p>
            </div>
        `;
        
        // Configurar botones
        printBtn.onclick = () => {
            if (documentType === 'factura') {
                window.open(`/pdf/invoice/${saleId}`, '_blank');
            } else {
                window.open(`/pdf/ticket/${saleId}`, '_blank');
            }
            bootstrap.Modal.getInstance(modal).hide();
        };
        
        previewBtn.onclick = () => {
            if (documentType === 'factura') {
                window.open(`/pdf/preview/invoice/${saleId}`, '_blank');
            } else {
                window.open(`/pdf/preview/ticket/${saleId}`, '_blank');
            }
        };
        
        new bootstrap.Modal(modal).show();
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

    <!-- Modal de Confirmación de Impresión -->
    <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="printModalTitle">
                        <i class="bi bi-printer-fill me-2"></i>
                        Documento Generado
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="printModalBody">
                    <!-- Contenido dinámico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-info" id="previewBtn">
                        <i class="bi bi-eye me-2"></i>
                        Vista Previa
                    </button>
                    <button type="button" class="btn btn-success" id="printConfirmBtn">
                        <i class="bi bi-printer me-2"></i>
                        Imprimir/Descargar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>