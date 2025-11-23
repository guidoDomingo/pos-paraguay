<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Company;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $user = User::first();
        $product = Product::first();

        if (!$user || !$product) {
            $this->command->info('Faltan datos básicos (user, product). No se pueden crear ventas.');
            return;
        }

        // Crear algunas ventas de prueba simples
        $salesData = [
            [
                'date' => Carbon::now()->subDays(5),
                'customer_name' => 'Juan Pérez',
                'customer_document' => '1234567',
                'items' => [['quantity' => 2, 'unit_price' => 8000]],
            ],
            [
                'date' => Carbon::now()->subDays(4),
                'customer_name' => 'María González',
                'customer_document' => '2345678',
                'items' => [['quantity' => 1, 'unit_price' => 8000]],
            ],
            [
                'date' => Carbon::now()->subDays(3),
                'customer_name' => 'Carlos López',
                'customer_document' => '3456789',
                'items' => [['quantity' => 3, 'unit_price' => 8000]],
            ],
            [
                'date' => Carbon::now()->subDays(2),
                'customer_name' => 'Ana Martínez',
                'customer_document' => '4567890',
                'items' => [['quantity' => 1, 'unit_price' => 8000]],
            ],
            [
                'date' => Carbon::now()->subDay(),
                'customer_name' => 'Roberto Silva',
                'customer_document' => '5678901',
                'items' => [['quantity' => 2, 'unit_price' => 8000]],
            ],
            [
                'date' => Carbon::now(),
                'customer_name' => 'Laura Fernández',
                'customer_document' => '6789012',
                'items' => [['quantity' => 4, 'unit_price' => 8000]],
            ],
        ];

        foreach ($salesData as $index => $saleData) {
            $subtotal = 0;
            foreach ($saleData['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            
            $taxAmount = $subtotal * 0.1; // 10% IVA
            $totalAmount = $subtotal + $taxAmount;

            $sale = Sale::create([
                'company_id' => $user->company_id,
                'warehouse_id' => 1, // Asumir warehouse por defecto
                'user_id' => $user->id,
                'sale_number' => 'V' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                'invoice_number' => '001-001-' . str_pad($index + 1, 7, '0', STR_PAD_LEFT),
                'customer_name' => $saleData['customer_name'],
                'customer_document' => $saleData['customer_document'],
                'sale_type' => 'INVOICE',
                'subtotal' => $subtotal,
                'subtotal_amount' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0,
                'total_amount' => $totalAmount,
                'payment_method' => 'CASH',
                'amount_paid' => $totalAmount,
                'change_amount' => 0,
                'status' => 'COMPLETED',
                'sale_date' => $saleData['date'],
                'created_at' => $saleData['date'],
                'updated_at' => $saleData['date'],
            ]);

            // Crear items de la venta
            foreach ($saleData['items'] as $itemData) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_code' => $product->code,
                    'product_name' => $product->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['quantity'] * $itemData['unit_price'],
                    'iva_type' => 'IVA_10',
                    'iva_amount' => ($itemData['quantity'] * $itemData['unit_price']) * 0.1,
                    'discount_percentage' => 0,
                    'discount_amount' => 0,
                ]);
            }
        }

        $this->command->info('Se crearon ' . count($salesData) . ' ventas de prueba.');
    }
}
