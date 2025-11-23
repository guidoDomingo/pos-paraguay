<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener la primera empresa
        $company = Company::first();
        if (!$company) {
            $company = Company::create([
                'name' => 'Empresa Demo',
                'ruc' => '80000000-1',
                'address' => 'Asunción, Paraguay',
                'phone' => '021-000000',
                'email' => 'demo@empresa.com',
                'is_active' => true,
            ]);
        }

        // Crear una categoría si no existe
        $category = Category::firstOrCreate(
            ['company_id' => $company->id, 'name' => 'Bebidas'],
            [
                'description' => 'Bebidas y refrescos',
                'is_active' => true,
            ]
        );

        // Crear productos de prueba
        $products = [
            [
                'code' => '0000121',
                'barcode' => '7891000000121',
                'name' => 'cerveza kaiser',
                'description' => 'Cerveza Kaiser 350ml lata',
                'cost_price' => 12000,
                'sale_price' => 18000,
                'wholesale_price' => 15000,
                'iva_type' => 'IVA_10',
                'unit' => 'Unidad',
                'min_stock' => 10,
                'max_stock' => 100,
                'stock' => 50,
                'track_stock' => true,
                'is_active' => true,
            ],
            [
                'code' => '0000122',
                'barcode' => '7891000000122',
                'name' => 'Coca Cola 350ml',
                'description' => 'Coca Cola lata 350ml',
                'cost_price' => 8000,
                'sale_price' => 12000,
                'wholesale_price' => 10000,
                'iva_type' => 'IVA_10',
                'unit' => 'Unidad',
                'min_stock' => 20,
                'max_stock' => 200,
                'stock' => 75,
                'track_stock' => true,
                'is_active' => true,
            ],
            [
                'code' => '0000123',
                'barcode' => '7891000000123',
                'name' => 'Agua Mineral 500ml',
                'description' => 'Agua mineral natural 500ml',
                'cost_price' => 3000,
                'sale_price' => 5000,
                'wholesale_price' => 4000,
                'iva_type' => 'EXENTO',
                'unit' => 'Unidad',
                'min_stock' => 30,
                'max_stock' => 300,
                'stock' => 120,
                'track_stock' => true,
                'is_active' => true,
            ],
            [
                'code' => '0000124',
                'barcode' => '7891000000124',
                'name' => 'Sprite 350ml',
                'description' => 'Sprite lata 350ml',
                'cost_price' => 7500,
                'sale_price' => 11000,
                'wholesale_price' => 9500,
                'iva_type' => 'IVA_10',
                'unit' => 'Unidad',
                'min_stock' => 15,
                'max_stock' => 150,
                'stock' => 45,
                'track_stock' => true,
                'is_active' => true,
            ],
            [
                'code' => '0000125',
                'barcode' => '7891000000125',
                'name' => 'Pepsi 2L',
                'description' => 'Pepsi botella 2 litros',
                'cost_price' => 15000,
                'sale_price' => 22000,
                'wholesale_price' => 19000,
                'iva_type' => 'IVA_10',
                'unit' => 'Unidad',
                'min_stock' => 8,
                'max_stock' => 80,
                'stock' => 25,
                'track_stock' => true,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['code' => $productData['code'], 'company_id' => $company->id],
                array_merge($productData, [
                    'company_id' => $company->id,
                    'category_id' => $category->id,
                ])
            );
        }

        $this->command->info('✅ Productos de prueba creados exitosamente');
    }
}