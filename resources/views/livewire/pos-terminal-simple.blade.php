<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Terminal POS</h1>
            <p>Productos en carrito: {{ count($cart) }}</p>
            
            @if(!empty($cart))
                <div class="mt-3">
                    <h5>Items en carrito:</h5>
                    @foreach($cart as $item)
                        <div class="border p-2 mb-2">
                            {{ $item['product_name'] ?? 'Producto' }} - 
                            Cantidad: {{ $item['quantity'] }} - 
                            Precio: ₲{{ number_format($item['unit_price'] ?? 0, 0) }}
                        </div>
                    @endforeach
                </div>
                
                <button wire:click="openPaymentModal" class="btn btn-primary">
                    Procesar Venta
                </button>
            @else
                <p>El carrito está vacío</p>
            @endif
            
            @if($showPaymentModal)
                <div class="modal show d-block" style="background: rgba(0,0,0,0.5);">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Procesar Pago</h5>
                            </div>
                            <div class="modal-body">
                                <p>Total: ₲{{ number_format($total_amount, 0) }}</p>
                                
                                <div class="mb-3">
                                    <label>Método de pago:</label>
                                    <select wire:model="payment_method" class="form-select">
                                        <option value="CASH">Efectivo</option>
                                        <option value="TRANSFER">Transferencia</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label>Tipo de documento:</label>
                                    <select wire:model="document_type" class="form-select">
                                        <option value="ticket">Ticket</option>
                                        <option value="factura">Factura</option>
                                    </select>
                                </div>
                                
                                @if($payment_method === 'CASH')
                                    <div class="mb-3">
                                        <label>Efectivo recibido:</label>
                                        <input type="number" wire:model="cash_received" class="form-control">
                                    </div>
                                @endif
                                
                                @if($document_type === 'factura')
                                    <div class="mb-3">
                                        <label>Nombre del cliente:</label>
                                        <input type="text" wire:model="customer_name" class="form-control">
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button wire:click="closePaymentModal" class="btn btn-secondary">Cancelar</button>
                                <button wire:click="processSale" class="btn btn-success">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>