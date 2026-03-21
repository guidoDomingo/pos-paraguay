<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\CashRegister;
use App\Models\StockMovement;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\InvoiceNumberService;
use App\Services\FacturaSendService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PosTerminal extends Component
{
    public $search = '';
    public $cart = [];
    public $customer_id = null;
    public $customer_name = '';
    public $customer_ruc = '';
    public $customer_address = '';
    public $payment_method = 'CASH';
    public $sale_type = 'TICKET'; // TICKET o INVOICE
    public $sale_condition = 'CONTADO'; // CONTADO o CREDITO
    public $document_type = 'ticket'; // ticket o factura
    public $discount_amount = 0;
    public $cash_received = 0;
    public $amount_paid = 0.00; // Monto abonado (para crédito)
    public $balance_due = 0.00; // Saldo pendiente (para crédito)
    public $notes = '';
    
    // Facturación electrónica FacturaSend
    public $send_electronic = false; // Si enviar como factura electrónica
    public $electronic_status = '';
    public $electronic_error = '';
    public $facturasend_enabled = false;
    
    // Calculados
    public $subtotal = 0;
    public $tax_amount = 0;
    public $total_amount = 0;
    public $change_amount = 0;
    
    // Estado
    public $showCustomerModal = false;
    public $showPaymentModal = false;
    public $showPrintModal = false;
    public $showPriceSelectionModal = false;
    public $selectedProduct = null;
    public $availablePrices = [];
    public $lastSaleId = null;
    public $lastDocumentType = null;
    public $lastSaleNumber = null;
    public $searchResults = [];
    public $selectedCustomer = null;

    protected $listeners = [
        'updateCashReceived' => 'updateCashReceived',
        'setCashAmount' => 'setCashAmount'
    ];

    protected $rules = [
        'customer_name' => 'required_if:document_type,factura|min:3',
        'customer_ruc' => 'nullable|string|max:20',
        'customer_address' => 'nullable|string',
        'cash_received' => 'required_if:payment_method,CASH|numeric|min:0',
        'sale_type' => 'required|in:TICKET,INVOICE',
        'document_type' => 'required|in:ticket,factura',
        'payment_method' => 'required|in:CASH,CARD,TRANSFER,CREDIT,CHEQUE',
        'send_electronic' => 'boolean',
    ];

    public function mount()
    {
        $this->reset();
        $this->calculateTotals();
        
        // Verificar si FacturaSend está habilitado
        $this->facturasend_enabled = config('facturasend.enabled', false);
        
        // Cargar algunos productos por defecto al inicializar
        $this->searchResults = Product::where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->limit(8)
            ->get();
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 3) {
            $this->searchResults = Product::where('company_id', Auth::user()->company_id)
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->where('name', 'LIKE', '%' . $this->search . '%')
                          ->orWhere('code', 'LIKE', '%' . $this->search . '%')
                          ->orWhere('barcode', 'LIKE', '%' . $this->search . '%');
                })
                ->limit(12)
                ->get();
        } else {
            // Mostrar productos populares cuando no hay búsqueda
            $this->searchResults = Product::where('company_id', Auth::user()->company_id)
                ->where('is_active', true)
                ->limit(8)
                ->get();
        }
    }

    public function clearSearch()
    {
        $this->search = '';
        // Recargar productos por defecto
        $this->searchResults = Product::where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->limit(8)
            ->get();
    }

    public function updatedCashReceived()
    {
        // Asegurar que sea un número válido
        $this->cash_received = is_numeric($this->cash_received) ? (float) $this->cash_received : 0;
        $this->calculateTotals();
    }

    public function updatedDiscountAmount()
    {
        $this->calculateTotals();
    }

    public function updatedAmountPaid()
    {
        // Asegurar que sea un número válido, mantener vacío si está vacío
        if ($this->amount_paid === '' || $this->amount_paid === null) {
            $this->amount_paid = 0;
        } else {
            $this->amount_paid = is_numeric($this->amount_paid) ? (float) $this->amount_paid : 0;
        }
        $this->calculateTotals();
    }

    public function updatedPaymentMethod()
    {
        // Cheque siempre implica Crédito
        if ($this->payment_method === 'CHEQUE') {
            $this->sale_condition = 'CREDITO';
        }
        $this->calculateTotals();
    }

    public function updatedSaleCondition()
    {
        // Resetear amount_paid cuando cambia la condición
        if ($this->sale_condition === 'CONTADO') {
            $this->amount_paid = 0;
            $this->balance_due = 0;
        }
        $this->calculateTotals();
    }
    
    public function calculateChange()
    {
        $this->change_amount = max(0, $this->cash_received - $this->total_amount);
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product || !$product->is_active) {
            session()->flash('error', 'Producto no encontrado o inactivo');
            return;
        }

        // Verificar stock si es necesario
        if ($product->track_stock) {
            $currentStock = $product->stock; // Usar stock directo por ahora
            $cartQuantity = collect($this->cart)->where('product_id', $productId)->sum('quantity');
            
            if ($currentStock <= $cartQuantity) {
                session()->flash('error', 'Stock insuficiente');
                return;
            }
        }

        // Obtener precios disponibles y válidos
        $availablePrices = $product->getAllPrices();
        
        // Si hay múltiples precios, mostrar modal de selección
        if (count($availablePrices) > 1) {
            $this->selectedProduct = $product;
            $this->availablePrices = $availablePrices;
            $this->showPriceSelectionModal = true;
            return;
        }

        // Si solo hay un precio o es solo precio de venta, agregarlo directamente
        $selectedPrice = count($availablePrices) > 0 ? $availablePrices[0] : ['type' => 'sale_price', 'value' => $product->sale_price, 'label' => 'Precio Venta', 'description' => 'Precio estándar'];
        $this->addToCartWithPrice($product, $selectedPrice);
    }

    public function addToCartWithPrice($product, $priceInfo)
    {
        $cartItemKey = collect($this->cart)->search(function ($item) use ($product, $priceInfo) {
            return $item['product_id'] == $product->id && $item['price_type'] == $priceInfo['type'];
        });

        if ($cartItemKey !== false) {
            // Incrementar cantidad si ya existe con el mismo tipo de precio
            $this->cart[$cartItemKey]['quantity']++;
            $this->cart[$cartItemKey]['total_price'] = 
                $this->cart[$cartItemKey]['quantity'] * $this->cart[$cartItemKey]['unit_price'];
        } else {
            // Agregar nuevo ítem
            $this->cart[] = [
                'product_id' => $product->id,
                'product_code' => $product->code,
                'product_name' => $product->name,
                'quantity' => 1,
                'unit_price' => $priceInfo['value'],
                'total_price' => $priceInfo['value'],
                'iva_type' => $product->iva_type,
                'iva_rate' => $product->getIvaRate(),
                'price_type' => $priceInfo['type'],
                'price_label' => $priceInfo['label'],
            ];
        }

        $this->calculateTotals();
        $this->closePriceSelectionModal();
        session()->flash('success', 'Producto agregado al carrito con ' . $priceInfo['label']);
    }

    public function selectPrice($priceTypeIndex)
    {
        if (!$this->selectedProduct || !isset($this->availablePrices[$priceTypeIndex])) {
            return;
        }

        $selectedPrice = $this->availablePrices[$priceTypeIndex];
        $this->addToCartWithPrice($this->selectedProduct, $selectedPrice);
    }

    public function closePriceSelectionModal()
    {
        $this->showPriceSelectionModal = false;
        $this->selectedProduct = null;
        $this->availablePrices = [];
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($index);
            return;
        }

        $product = Product::find($this->cart[$index]['product_id']);
        
        if ($product->track_stock) {
            $currentStock = $product->stock;
            if ($quantity > $currentStock) {
                session()->flash('error', 'Cantidad mayor al stock disponible');
                $this->cart[$index]['quantity'] = $currentStock;
                $quantity = $currentStock;
            }
        }

        $this->cart[$index]['quantity'] = $quantity;
        $this->cart[$index]['total_price'] = $quantity * $this->cart[$index]['unit_price'];
        
        $this->calculateTotals();
    }

    // Método para actualizar desde la vista
    public function updatedCart($value, $key)
    {
        if (strpos($key, '.quantity') !== false) {
            $index = explode('.', $key)[0];
            $this->updateQuantity($index, $this->cart[$index]['quantity']);
        }
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->calculateTotals();
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        $this->tax_amount = 0;

        foreach ($this->cart as &$item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $item['total_price'] = $itemTotal;
            $this->subtotal += $itemTotal;
            
            // Calcular IVA (método paraguayo: total/11 para 10%, total/21 para 5%)
            if ($item['iva_type'] === 'IVA_10') {
                $ivaAmount = $itemTotal / 11;
                $item['iva_amount'] = $ivaAmount;
                $this->tax_amount += $ivaAmount;
            } elseif ($item['iva_type'] === 'IVA_5') {
                $ivaAmount = $itemTotal / 21;
                $item['iva_amount'] = $ivaAmount;
                $this->tax_amount += $ivaAmount;
            } else {
                $item['iva_amount'] = 0;
            }
        }

        $this->total_amount = $this->subtotal - (float) $this->discount_amount;
        
        // Calcular balance_due si es venta a crédito
        if ($this->sale_condition === 'CREDITO') {
            $this->balance_due = max(0, $this->total_amount - $this->amount_paid);
        } else {
            $this->balance_due = 0;
        }
        
        // Calcular cambio si es efectivo de contado
        if ($this->payment_method === 'CASH' && $this->sale_condition === 'CONTADO') {
            $cashReceived = (float) $this->cash_received;
            $this->change_amount = max(0, $cashReceived - $this->total_amount);
        } else {
            $this->change_amount = 0;
        }
    }

    public function openCustomerModal()
    {
        $this->showCustomerModal = true;
        $this->resetCustomerData();
    }

    public function closeCustomerModal()
    {
        $this->showCustomerModal = false;
    }

    public function selectCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        $this->selectedCustomer = $customer;
        $this->customer_id = $customer->id;
        $this->customer_name = $customer->name;
        $this->customer_ruc = $customer->getFormattedRucAttribute();
        $this->customer_address = $customer->address;
        $this->closeCustomerModal();
    }

    public function resetCustomerData()
    {
        $this->customer_id = null;
        $this->customer_name = '';
        $this->customer_ruc = '';
        $this->customer_address = '';
        $this->selectedCustomer = null;
    }

    public function setSaleType($type)
    {
        $this->sale_type = $type;
        
        if ($type === 'INVOICE') {
            $this->openCustomerModal();
        } else {
            $this->resetCustomerData();
        }
    }

    public function openPaymentModal()
    {        
        if (empty($this->cart)) {
            session()->flash('error', 'El carrito está vacío');
            return;
        }

        // Establecer valores por defecto
        $this->cash_received = 0; // Cambiar a 0 para que el usuario ingrese el monto
        $this->payment_method = 'CASH';
        $this->sale_type = 'TICKET';
        
        $this->calculateTotals();
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        // Reset form data if needed
        $this->customer_name = '';
        $this->customer_ruc = '';
        $this->customer_address = '';
        $this->notes = '';
    }


    public function processSale()
    {
        // Validaciones básicas
        if (empty($this->cart)) {
            session()->flash('error', 'El carrito está vacío');
            return;
        }

        // Validación de efectivo solo si es pago de contado con efectivo
        if ($this->payment_method === 'CASH' && $this->sale_condition === 'CONTADO' && $this->cash_received < $this->total_amount) {
            session()->flash('error', 'Efectivo insuficiente');
            return;
        }

        if ($this->document_type === 'factura' && empty($this->customer_name)) {
            session()->flash('error', 'Debe ingresar el nombre del cliente para factura');
            return;
        }

        // Validar que el monto abonado no sea mayor al total en venta a crédito
        if ($this->sale_condition === 'CREDITO' && $this->amount_paid > $this->total_amount) {
            session()->flash('error', 'El monto abonado no puede ser mayor al total');
            return;
        }

        // Validaciones para facturación electrónica
        if ($this->send_electronic && !$this->facturasend_enabled) {
            session()->flash('error', 'Facturación electrónica no está habilitada');
            return;
        }

        if ($this->send_electronic && empty($this->customer_ruc)) {
            session()->flash('error', 'RUC es requerido para facturación electrónica');
            return;
        }

        // Validar que existe un timbrado fiscal activo si se va a emitir factura
        if ($this->document_type === 'factura') {
            $invoiceService = new InvoiceNumberService();
            $fiscalStamp = $invoiceService->getActiveFiscalStamp(Auth::user()->company_id);
            
            if (!$fiscalStamp) {
                session()->flash('error', 'No hay timbrado fiscal activo. Por favor configure un timbrado fiscal antes de emitir facturas.');
                return;
            }

            // Validar que el timbrado sea válido (errores bloqueantes)
            $validationErrors = $invoiceService->validateFiscalStamp($fiscalStamp);
            if (!empty($validationErrors)) {
                session()->flash('error', 'Timbrado fiscal inválido: ' . implode(', ', $validationErrors));
                return;
            }

            // Obtener advertencias (no bloqueantes) y registrarlas
            $warnings = $invoiceService->getFiscalStampWarnings($fiscalStamp);
            if (!empty($warnings)) {
                Log::warning('Advertencias del timbrado fiscal', [
                    'fiscal_stamp_id' => $fiscalStamp->id,
                    'warnings' => $warnings,
                    'user_id' => Auth::id()
                ]);
                // Opcional: mostrar advertencia al usuario pero permitir continuar
                // session()->flash('warning', implode(', ', $warnings));
            }
        }

        try {
            DB::beginTransaction();

            // Calcular montos para venta a crédito
            $amountPaid = $this->sale_condition === 'CREDITO' ? $this->amount_paid : $this->total_amount;
            $balanceDue = $this->sale_condition === 'CREDITO' ? ($this->total_amount - $this->amount_paid) : 0;
            $changeAmount = 0;
            
            // Si es efectivo y pago de contado, calcular vuelto
            if ($this->payment_method === 'CASH' && $this->sale_condition === 'CONTADO') {
                $changeAmount = $this->cash_received - $this->total_amount;
                $amountPaid = $this->cash_received;
            }

            // Crear venta
            $sale = Sale::create([
                'company_id' => Auth::user()->company_id,
                'warehouse_id' => Auth::user()->warehouse_id ?? null,
                'user_id' => Auth::id(),
                'customer_id' => $this->customer_id,
                'sale_number' => $this->generateSaleNumber(),
                'sale_type' => $this->sale_type,
                'document_type' => $this->document_type,
                'customer_name' => $this->customer_name,
                'customer_document' => $this->customer_ruc,
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->tax_amount,
                'discount_amount' => $this->discount_amount,
                'total_amount' => $this->total_amount,
                'payment_method' => $this->payment_method,
                'sale_condition' => $this->sale_condition,
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'balance_due' => $balanceDue,
                'notes' => $this->notes,
                'status' => $this->sale_condition === 'CREDITO' ? 'PENDING' : 'COMPLETED',
                'sale_date' => now(),
            ]);

            // Crear ítems de venta
            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'product_code' => $item['product_code'] ?? '',
                    'product_name' => $item['product_name'] ?? '',
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'iva_type' => $item['iva_type'] ?? 'EXENTO',
                    'iva_amount' => $item['iva_amount'] ?? 0,
                    'discount_percentage' => 0,
                    'discount_amount' => 0,
                ]);

                // Actualizar stock
                $product = Product::find($item['product_id']);
                if ($product->track_stock) {
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // Crear Invoice si el tipo de comprobante es factura
            if ($this->document_type === 'factura') {
                $invoice = $this->createInvoiceFromSale($sale);

                // Enviar a FacturaSend si está habilitado (solo contado)
                if ($this->send_electronic && $this->facturasend_enabled && $this->sale_condition === 'CONTADO') {
                    $this->processElectronicInvoice($invoice);
                }
            }

            DB::commit();

            $successMessage = $this->sale_condition === 'CREDITO'
                ? 'Venta a crédito registrada correctamente. Estado: PENDIENTE.'
                : 'Venta procesada exitosamente';
            
            session()->flash('success', $successMessage);
            
            // Configurar datos para el modal de impresión
            $this->lastSaleId = $sale->id;
            $this->lastDocumentType = $this->document_type;
            $this->lastSaleNumber = $sale->sale_number;
            $this->showPrintModal = true;
            
            // Guardar ID de venta para redirección PDF
            session()->put('sale_id_for_pdf', $sale->id);
            session()->put('document_type_for_pdf', $this->document_type);
            
            // Guardar el tipo de documento antes de resetear
            $currentDocumentType = $this->document_type;
            
            // Emitir evento para JavaScript para mostrar modal de impresión
            $this->dispatch('sale-completed', 
                saleId: $sale->id,
                documentType: $currentDocumentType,
                saleNumber: $sale->sale_number
            );
            
            // Resetear formulario después de emitir el evento
            $this->reset(['cart', 'customer_name', 'customer_ruc', 'customer_address', 'notes', 'cash_received', 'document_type', 'send_electronic', 'electronic_status', 'electronic_error']);
            $this->document_type = 'ticket'; // Resetear a ticket por defecto
            $this->calculateTotals();
            $this->closePaymentModal();

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Mensajes de error más específicos
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, 'timbrado fiscal')) {
                session()->flash('error', $errorMessage);
            } else {
                session()->flash('error', 'Error al procesar la venta: ' . $errorMessage);
            }
            
            Log::error('Error procesando venta: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'cart' => $this->cart,
                'document_type' => $this->document_type,
                'error' => $e->getTraceAsString()
            ]);
        }
    }

    private function generateSaleNumber(): string
    {
        $prefix = $this->sale_type === 'TICKET' ? 'T' : 'V';
        $lastSale = Sale::where('company_id', Auth::user()->company_id)
            ->where('sale_type', $this->sale_type)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastSale ? (int) substr($lastSale->sale_number, 1) + 1 : 1;

        return $prefix . str_pad($number, 8, '0', STR_PAD_LEFT);
    }

    public function updateCashReceived($value)
    {
        $this->cash_received = floatval($value);
        $this->calculateTotals();
    }

    public function setCashAmount($amount)
    {
        $this->cash_received = floatval($amount);
        $this->calculateTotals();
    }

    public function closePrintModal()
    {
        $this->showPrintModal = false;
        $this->lastSaleId = null;
        $this->lastDocumentType = null;
        $this->lastSaleNumber = null;
    }

    public function render()
    {
        $customers = Customer::where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $products = Product::where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($catQuery) {
                          $catQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('livewire.pos-terminal', compact('customers', 'products'));
    }

    // =====================================================
    // MÉTODOS DE FACTURACIÓN ELECTRÓNICA FACTURASEND
    // =====================================================

    /**
     * Crear Invoice desde Sale para facturación electrónica
     */
    private function createInvoiceFromSale(Sale $sale): Invoice
    {
        // Verificar que la venta tiene ID
        if (!$sale->id) {
            throw new \Exception('La venta no se guardó correctamente');
        }

        // Obtener timbrado fiscal activo
        $invoiceService = new InvoiceNumberService();
        $fiscalStamp = $invoiceService->getActiveFiscalStamp(Auth::user()->company_id);
        
        if (!$fiscalStamp) {
            throw new \Exception('No hay timbrado fiscal activo. Por favor configure un timbrado fiscal antes de emitir facturas.');
        }

        // Validar que el timbrado sea válido
        $validationErrors = $invoiceService->validateFiscalStamp($fiscalStamp);
        if (!empty($validationErrors)) {
            throw new \Exception('Timbrado fiscal inválido: ' . implode(', ', $validationErrors));
        }

        // Obtener próximo número de factura
        $invoiceNumber = $fiscalStamp->getNextInvoiceNumber();

        $invoice = Invoice::create([
            'company_id' => $sale->company_id,
            'sale_id' => $sale->id,
            'customer_id' => $sale->customer_id,
            'fiscal_stamp_id' => $fiscalStamp->id,
            'invoice_number' => $invoiceNumber,
            'stamp_number' => $fiscalStamp->stamp_number,
            'establishment' => $fiscalStamp->establishment,
            'point_of_sale' => $fiscalStamp->point_of_sale,
            'sequential_number' => $fiscalStamp->current_invoice_number,
            'subtotal_exento' => $sale->subtotal,
            'subtotal_iva_5' => 0,
            'subtotal_iva_10' => 0,
            'total_iva_5' => 0,
            'total_iva_10' => 0,
            'total_iva' => $sale->tax_amount,
            'total_amount' => $sale->total_amount,
            'customer_name' => $sale->customer_name,
            'customer_ruc' => $sale->customer_document,
            'customer_address' => $this->customer_address,
            'condition' => 'CONTADO',
            'invoice_date' => $sale->sale_date,
            'observations' => $sale->notes,
            'is_printed' => false,
        ]);

        // Crear InvoiceItems desde SaleItems
        foreach ($sale->items as $saleItem) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $saleItem->product_id,
                'product_code' => $saleItem->product_code ?? '',
                'product_name' => $saleItem->product_name,
                'quantity' => $saleItem->quantity,
                'unit_price' => $saleItem->unit_price,
                'total_price' => $saleItem->total_price,
                'iva_type' => $saleItem->iva_type,
                'iva_amount' => $saleItem->iva_amount,
            ]);
        }

        // Calcular totales de la factura
        $invoice->calculateTotals();

        return $invoice;
    }

    /**
     * Procesar factura electrónica con FacturaSend
     */
    private function processElectronicInvoice(Invoice $invoice): void
    {
        try {
            $facturasendService = new FacturaSendService();
            
            // Enviar a FacturaSend
            $result = $facturasendService->sendInvoice($invoice, false); // false = no es borrador
            
            if ($result['success']) {
                $this->electronic_status = 'Enviado a FacturaSend exitosamente';
                $this->electronic_error = '';
                
                session()->flash('success', 'Factura electrónica enviada exitosamente. CDC: ' . $result['cdc']);
                Log::info('Factura electrónica enviada', [
                    'invoice_id' => $invoice->id,
                    'cdc' => $result['cdc']
                ]);
                
            } else {
                $this->electronic_status = 'Error al enviar';
                $this->electronic_error = $result['error'];
                
                session()->flash('warning', 'Factura creada pero hubo error en envío electrónico: ' . $result['error']);
                Log::error('Error enviando factura electrónica', [
                    'invoice_id' => $invoice->id,
                    'error' => $result['error']
                ]);
            }
            
        } catch (\Exception $e) {
            $this->electronic_status = 'Error de conexión';
            $this->electronic_error = $e->getMessage();
            
            session()->flash('warning', 'Factura creada pero hubo error de conexión con FacturaSend: ' . $e->getMessage());
            Log::error('Excepción enviando factura electrónica', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Verificar si se puede enviar factura electrónica
     */
    public function canSendElectronic(): bool
    {
        return $this->facturasend_enabled && 
               $this->document_type === 'factura' && 
               !empty($this->customer_ruc);
    }

    /**
     * Toggle para activar/desactivar envío electrónico
     */
    public function toggleElectronic()
    {
        if (!$this->facturasend_enabled) {
            session()->flash('error', 'Facturación electrónica no está habilitada');
            $this->send_electronic = false;
            return;
        }

        if ($this->document_type !== 'factura') {
            session()->flash('error', 'Solo se puede enviar facturas como documentos electrónicos');
            $this->send_electronic = false;
            return;
        }

        if (empty($this->customer_ruc)) {
            session()->flash('error', 'RUC es requerido para facturación electrónica');
            $this->send_electronic = false;
            return;
        }

        $this->send_electronic = !$this->send_electronic;
    }
}