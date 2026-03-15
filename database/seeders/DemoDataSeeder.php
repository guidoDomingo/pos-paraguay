<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed demo data after cleaning the system
     */
    public function run(): void
    {
        // Verificar si ya existen datos de prueba
        if (Sale::count() > 0) {
            $this->command->info('Ya existen datos de prueba. Omitiendo...');
            return;
        }

        $this->command->info('🌱 Creando datos de prueba...');

        // Obtener la primera empresa
        $company = Company::first();
        if (!$company) {
            $this->command->error('No existe ninguna empresa. Ejecuta primero: php artisan db:seed');
            return;
        }

        // Crear clientes de prueba
        $this->createDemoCustomers($company);
        
        // Crear ventas de ejemplo
        $this->createDemoSales($company);

        $this->command->info('✅ Datos de prueba creados exitosamente');
    }

    private function createDemoCustomers($company)
    {
        $customers = [
            [
                'name' => 'María González',
                'ci' => '4567890',
                'address' => 'Av. España 123, Asunción',
                'phone' => '0981123456',
                'email' => 'maria.gonzalez@email.com',
                'customer_type' => 'INDIVIDUAL',
            ],
            [
                'name' => 'Juan Pérez',
                'ci' => '3456789', 
                'address' => 'Calle Palma 456, Fernando de la Mora',
                'phone' => '0982654321',
                'customer_type' => 'INDIVIDUAL',
                'credit_limit' => 500000,
            ],
            [
                'name' => 'Comercial San Lorenzo S.A.',
                'ruc' => '80098765',
                'dv' => '4',
                'address' => 'Ruta 2 Km 8, San Lorenzo',
                'phone' => '021456789',
                'email' => 'ventas@comercialsanlorenzo.com.py',
                'customer_type' => 'COMPANY',
                'credit_limit' => 2000000,
            ]
        ];

        foreach ($customers as $customerData) {
            $customerData['company_id'] = $company->id;
            Customer::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'ci' => $customerData['ci'] ?? null,
                    'ruc' => $customerData['ruc'] ?? null,
                ],
                $customerData
            );
        }

        $this->command->line('  ✓ Clientes de prueba creados');
    }

    private function createDemoSales($company)
    {
        $products = Product::where('company_id', $company->id)->get();
        $customers = Customer::where('company_id', $company->id)->get();
        $warehouse = $company->warehouses()->first();
        
        if ($products->isEmpty()) {
            $this->command->warn('  ⚠ No hay productos. Ejecuta ProductSeeder primero.');
            return;
        }
        
        if (!$warehouse) {
            $this->command->warn('  ⚠ No hay almacenes. Ejecuta WarehouseSeeder primero.');
            return;
        }

        // Crear 10 ventas de ejemplo
        for ($i = 1; $i <= 10; $i++) {
            $customer = $customers->random();
            $saleCondition = $i <= 7 ? 'CONTADO' : 'CREDITO'; // 70% contado, 30% crédito
            
            $sale = Sale::create([
                'company_id' => $company->id,
                'warehouse_id' => $warehouse->id,
                'sale_number' => 'DEMO-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'user_id' => $company->users()->first()?->id ?? 1,
                'sale_date' => now()->subDays(rand(0, 30)),
                'sale_type' => 'TICKET',
                'sale_condition' => $saleCondition,
                'payment_method' => $saleCondition === 'CONTADO' ? 'CASH' : 'CREDIT',
                'subtotal' => 0, // Se calculará después
                'tax_amount' => 0,
                'total_amount' => 0,
                'status' => 'COMPLETED',
                'notes' => $i <= 3 ? 'Venta de prueba generada automáticamente' : null,
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ]);

            // Agregar 1-4 items por venta
            $itemCount = rand(1, 4);
            $subtotal = 0;
            $totalTax = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 5);
                $unitPrice = $product->sale_price;
                $lineTotal = $quantity * $unitPrice;
                $lineTax = $lineTotal * 0.10; // IVA 10%
                
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                    'iva_amount' => $lineTax,
                    'iva_type' => 'IVA_10',
                ]);

                $subtotal += $lineTotal;
                $totalTax += $lineTax;

                // Actualizar stock del producto
                $product->decrement('stock', $quantity);
            }

            // Actualizar totales de la venta
            $sale->update([
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'total_amount' => $subtotal + $totalTax,
                'amount_paid' => $saleCondition === 'CONTADO' ? $subtotal + $totalTax : 0,
                'balance_due' => $saleCondition === 'CREDITO' ? $subtotal + $totalTax : 0,
            ]);
        }

        $this->command->line("  ✓ 10 ventas de prueba creadas");
        $this->command->line("  ✓ Stock de productos actualizado");
    }
}