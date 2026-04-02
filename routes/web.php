<?php

use App\Http\Controllers\PosController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceSettingController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\DirectPrintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FiscalStampController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DataManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS Terminal
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/simple', function () {
        return view('pos.simple');
    })->name('pos.simple');
    
    // Products
    Route::resource('products', ProductController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Inventory Management
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/movements', [InventoryController::class, 'movements'])->name('inventory.movements');
    Route::get('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::post('/inventory/adjust', [InventoryController::class, 'storeAdjustment'])->name('inventory.adjust.store');
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('/inventory/reports', [InventoryController::class, 'reports'])->name('inventory.reports');
    
    // Sales
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SalesController::class, 'show'])->name('sales.show');
    Route::get('/sales-reports', [SalesController::class, 'reports'])->name('sales.reports');
    
    // Payments for credit sales
    Route::post('/sales/{sale}/payments', [PaymentController::class, 'store'])->name('payments.store');
    
    // Fiscal Stamps
    Route::resource('fiscal-stamps', FiscalStampController::class);
    
    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('/sales/{sale}/print-ticket', [InvoiceController::class, 'printTicket'])->name('sales.print-ticket');
    
    // PDF Generation
    Route::get('/pdf/invoice/{sale}', [PdfController::class, 'generateInvoice'])->name('pdf.invoice');
    Route::get('/pdf/ticket/{sale}', [PdfController::class, 'generateTicket'])->name('pdf.ticket');
    Route::get('/pdf/preview/invoice/{sale}', [PdfController::class, 'previewInvoice'])->name('pdf.preview.invoice');
    Route::get('/pdf/preview/ticket/{sale}', [PdfController::class, 'previewTicket'])->name('pdf.preview.ticket');
    
    // Direct Printing - Ruta alternativa
    Route::get('/direct-print/{sale}', [DirectPrintController::class, 'printTicket'])->name('direct.print');
    Route::post('/direct-print-raw/{sale}', [DirectPrintController::class, 'printTicketRaw'])->name('direct.print.raw.post');
    Route::get('/direct-print-raw/{sale}', [DirectPrintController::class, 'printTicketRaw'])->name('direct.print.raw');
    Route::get('/print/ticket/{sale}', [DirectPrintController::class, 'printTicket'])->name('print.ticket.direct');
    Route::get('/print/ticket-escpos/{sale}', [DirectPrintController::class, 'printTicketESCPOS'])->name('print.ticket.escpos');
    Route::post('/print/bluetooth/{sale}', [DirectPrintController::class, 'printBluetooth'])->name('print.bluetooth');
    Route::post('/print/bluetooth-test', [DirectPrintController::class, 'printTest'])->name('print.bluetooth.test');
    Route::get('/api/bluetooth-ports', [DirectPrintController::class, 'detectBluetoothPorts'])->name('bluetooth.ports');
    Route::get('/print/rawbt/{sale}', [DirectPrintController::class, 'escposBase64'])->name('print.rawbt');
    Route::get('/print/rawbt/invoice/{sale}', [DirectPrintController::class, 'escposBase64Invoice'])->name('print.rawbt.invoice');
    Route::post('/print/bluetooth/invoice/{sale}', [DirectPrintController::class, 'printBluetoothInvoice'])->name('print.bluetooth.invoice');
    Route::get('/print/debug/{sale}', function($sale) {
        try {
            \Log::info("Debug route called for sale: {$sale}");
            
            // Test 1: Basic sale retrieval
            $saleData = \App\Models\Sale::find($sale);
            if (!$saleData) {
                return response()->json(['error' => 'Sale not found', 'sale_id' => $sale], 404);
            }
            
            // Test 2: Load with relations
            $saleWithRelations = \App\Models\Sale::with(['saleItems.product', 'customer', 'user'])->find($sale);
            
            // Test 3: InvoiceSettings
            $settings = \App\Models\InvoiceSetting::getSettings();
            
            return response()->json([
                'status' => 'success',
                'sale_id' => $sale,
                'sale_number' => $saleData->sale_number,
                'has_items' => $saleWithRelations->saleItems->count(),
                'has_user' => !is_null($saleWithRelations->user),
                'user_name' => $saleWithRelations->user->name ?? 'NULL',
                'settings_company' => $settings->company_name ?? 'NULL',
                'controller_exists' => class_exists('\App\Http\Controllers\DirectPrintController'),
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Debug route error: " . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString())
            ], 500);
        }
    });
    Route::get('/print/simple-test/{sale}', function($sale) {
        try {
            $saleData = \App\Models\Sale::find($sale);
            if (!$saleData) {
                return response()->json(['error' => 'Sale not found'], 404);
            }
            return response("TEST TICKET\nSale ID: {$sale}\nSale Number: {$saleData->sale_number}\nTotal: {$saleData->total_amount}")
                ->header('Content-Type', 'text/plain; charset=utf-8');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
    Route::get('/print/test', function() {
        return response()->json([
            'message' => 'DirectPrintController is accessible',
            'timestamp' => now(),
            'latest_sale' => \App\Models\Sale::orderBy('id', 'desc')->first()?->id ?? 'No sales found'
        ]);
    });
    
    // Invoice Settings
    Route::get('/settings/invoice', [InvoiceSettingController::class, 'index'])->name('settings.invoice');
    Route::put('/settings/invoice', [InvoiceSettingController::class, 'update'])->name('settings.invoice.update');
    
    // Printer Management
    Route::get('/api/printers', [PrinterController::class, 'getAvailablePrinters'])->name('printers.list');
    Route::post('/api/printers/test', [PrinterController::class, 'testPrinter'])->name('printers.test');
    Route::get('/api/printers/{printer}/status', [PrinterController::class, 'getPrinterStatus'])->name('printers.status');
    
    // Data Management - System Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/data-management', [DataManagementController::class, 'index'])->name('data-management.index');
        Route::get('/data-management/confirm', [DataManagementController::class, 'confirmClean'])->name('data-management.confirm');
        Route::post('/data-management/clean', [DataManagementController::class, 'cleanData'])->name('data-management.clean');
        Route::get('/data-management/backup', [DataManagementController::class, 'downloadBackup'])->name('data-management.backup');
    });
});

require __DIR__.'/auth.php';
