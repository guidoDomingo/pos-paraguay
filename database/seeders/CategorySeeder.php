<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Company;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $company = Company::first();
        
        $categories = [
            [
                'company_id' => $company->id,
                'name' => 'Alimentos',
                'description' => 'Productos alimenticios y comestibles',
                'is_active' => true,
            ],
            [
                'company_id' => $company->id,
                'name' => 'Bebidas',
                'description' => 'Bebidas alcohólicas y no alcohólicas',
                'is_active' => true,
            ],
            [
                'company_id' => $company->id,
                'name' => 'Limpieza',
                'description' => 'Productos de limpieza e higiene',
                'is_active' => true,
            ],
            [
                'company_id' => $company->id,
                'name' => 'Cuidado Personal',
                'description' => 'Productos de higiene personal',
                'is_active' => true,
            ],
        ];
        
        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
        
        echo "✅ Categorías creadas exitosamente!\n";
    }
}
