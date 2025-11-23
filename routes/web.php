<?php

use App\Http\Controllers\PosController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceSettingController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\FiscalStampController;
use App\Http\Controllers\InventoryController;
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
    
    // Fiscal Stamps
    Route::resource('fiscal-stamps', FiscalStampController::class)->except(['edit', 'update', 'destroy']);
    
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
    
    // Invoice Settings
    Route::get('/settings/invoice', [InvoiceSettingController::class, 'index'])->name('settings.invoice');
    Route::put('/settings/invoice', [InvoiceSettingController::class, 'update'])->name('settings.invoice.update');
    
    // Printer Management
    Route::get('/api/printers', [PrinterController::class, 'getAvailablePrinters'])->name('printers.list');
    Route::post('/api/printers/test', [PrinterController::class, 'testPrinter'])->name('printers.test');
    Route::get('/api/printers/{printer}/status', [PrinterController::class, 'getPrinterStatus'])->name('printers.status');
});

require __DIR__.'/auth.php';
