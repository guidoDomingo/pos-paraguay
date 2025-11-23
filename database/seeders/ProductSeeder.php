<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use App\Models\Warehouse;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        $warehouse = Warehouse::where('company_id', $company->id)->first();
        
        // Obtener categorías existentes
        $categories = Category::where('company_id', $company->id)->get();
        
        // Verificar si tenemos categorías, sino crear una por defecto
        if ($categories->isEmpty()) {
            $defaultCategory = Category::create([
                'company_id' => $company->id,
                'name' => 'Productos Generales',
                'description' => 'Categoría general para productos',
                'is_active' => true,
            ]);
            $categoryId = $defaultCategory->id;
        } else {
            $categoryId = $categories->first()->id;
        }

        $products = [
            // Alimentos
            [
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'code' => 'ALM001',
                'name' => 'Pan de Molde',
                'description' => 'Pan de molde integral 450gr',
                'cost_price' => 85.00,
                'sale_price' => 120.00,
                'min_stock' => 10,
                'stock' => 15,
                'iva_type' => 'EXENTO',
                'unit' => 'UNIDAD',
                'is_active' => true,
            ],
            [
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'code' => 'ALM002',
                'name' => 'Arroz Tipo 1',
                'description' => 'Arroz premium tipo 1 - 1kg',
                'cost_price' => 42.00,
                'sale_price' => 65.00,
                'min_stock' => 20,
                'stock' => 5,
                'iva_type' => 'EXENTO',
                'unit' => 'KG',
                'is_active' => true,
            ],
            [
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'code' => 'ALM003',
                'name' => 'Aceite de Girasol',
                'description' => 'Aceite de girasol 900ml',
                'cost_price' => 120.00,
                'sale_price' => 165.00,
                'min_stock' => 5,
                'stock' => 12,
                'iva_type' => 'IVA_10',
                'unit' => 'UNIDAD',
                'is_active' => true,
            ],
            
            // Bebidas
            [
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'code' => 'BEB001',
                'name' => 'Coca Cola 2L',
                'description' => 'Coca Cola Original 2 litros',
                'cost_price' => 85.00,
                'sale_price' => 120.00,
                'min_stock' => 6,
                'stock' => 0,
                'iva_type' => 'IVA_10',
                'unit' => 'UNIDAD',
                'is_active' => true,
            ],
            [
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'code' => 'BEB002',
                'name' => 'Agua Mineral 500ml',
                'description' => 'Agua mineral natural 500ml',
                'cost_price' => 15.00,
                'sale_price' => 25.00,
                'min_stock' => 24,
                'stock' => 0,
                'iva_type' => 'EXENTO',
                'unit' => 'UNIDAD',
                'is_active' => true,
            ],
            [
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'code' => 'BEB003',
                'name' => 'Cerveza Pilsen 1L',
                'description' => 'Cerveza Pilsen botella 1 litro',
                'cost_price' => 70.00,
                'sale_price' => 110.00,
                'min_stock' => 12,
                'stock' => 0,
                'iva_type' => 'IVA_10',
                'unit' => 'UNIDAD',
                'is_active' => true,
            ],
            
            // Limpieza
            [
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'code' => 'LMP001',
                'name' => 'Detergente Líquido',
                'description' => 'Detergente líquido para ropa 1L',
                'cost_price' => 95.00,
                'sale_price' => 135.00,
                'min_stock' => 6,
                'stock' => 8,
                'iva_type' => 'IVA_10',
                'unit' => 'UNIDAD',
                'is_active' => true,
            ],
            [
                'company_id' => $company->id,
                'category_id' => $categoryId,
                'code' => 'LMP002',
                'name' => 'Papel Higiénico',
                'description' => 'Papel higiénico doble hoja x4 rollos',
                'cost_price' => 65.00,
                'sale_price' => 95.00,
                'min_stock' => 12,
                'stock' => 18,
                'iva_type' => 'EXENTO',
                'unit' => 'UNIDAD',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        echo "✅ Productos creados exitosamente!\n";
    }
}