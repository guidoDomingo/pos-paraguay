<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\CashRegister;
use App\Models\StockMovement;
use App\Services\InvoiceNumberService;
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
    public $document_type = 'ticket'; // ticket o factura
    public $discount_amount = 0;
    public $cash_received = 0;
    public $notes = '';
    
    // Calculados
    public $subtotal = 0;
    public $tax_amount = 0;
    public $total_amount = 0;
    public $change_amount = 0;
    
    // Estado
    public $showCustomerModal = false;
    public $showPaymentModal = false;
    public $showPrintModal = false;
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
        'payment_method' => 'required|in:CASH,CARD,TRANSFER,CREDIT',
    ];

    public function mount()
    {
        $this->reset();
        $this->calculateTotals();
        // Cargar algunos productos por defecto al inicializar
        $this->searchResults = Product::where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->limit(8)
            ->get();
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 1) {
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

        $cartItemKey = collect($this->cart)->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });

        if ($cartItemKey !== false) {
            // Incrementar cantidad si ya existe
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
                'unit_price' => $product->sale_price,
                'total_price' => $product->sale_price,
                'iva_type' => $product->iva_type,
                'iva_rate' => $product->getIvaRate(),
            ];
        }

        $this->calculateTotals();
        // No limpiar la búsqueda para mantener la lista visible
        // $this->search = '';
        // $this->searchResults = [];
        session()->flash('success', 'Producto agregado al carrito');
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
            
            // Calcular IVA
            if ($item['iva_type'] !== 'EXENTO') {
                $ivaAmount = ($itemTotal * $item['iva_rate']) / (100 + $item['iva_rate']);
                $item['iva_amount'] = $ivaAmount;
                $this->tax_amount += $ivaAmount;
            } else {
                $item['iva_amount'] = 0;
            }
        }

        $this->total_amount = $this->subtotal - (float) $this->discount_amount;
        
        // Asegurar que cash_received sea numérico antes de calcular
        $cashReceived = (float) $this->cash_received;
        $this->change_amount = max(0, $cashReceived - $this->total_amount);
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

        if ($this->payment_method === 'CASH' && $this->cash_received < $this->total_amount) {
            session()->flash('error', 'Efectivo insuficiente');
            return;
        }

        if ($this->document_type === 'factura' && empty($this->customer_name)) {
            session()->flash('error', 'Debe ingresar el nombre del cliente para factura');
            return;
        }

        try {
            DB::beginTransaction();

            // Crear venta
            $sale = Sale::create([
                'company_id' => Auth::user()->company_id,
                'warehouse_id' => Auth::user()->warehouse_id ?? 1,
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
                'amount_paid' => $this->cash_received,
                'change_amount' => $this->change_amount,
                'notes' => $this->notes,
                'status' => 'COMPLETED',
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

            DB::commit();

            session()->flash('success', 'Venta procesada exitosamente');
            
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
            $this->reset(['cart', 'customer_name', 'customer_ruc', 'customer_address', 'notes', 'cash_received', 'document_type']);
            $this->document_type = 'ticket'; // Resetear a ticket por defecto
            $this->calculateTotals();
            $this->closePaymentModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al procesar la venta: ' . $e->getMessage());
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
}