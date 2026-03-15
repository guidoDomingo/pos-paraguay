<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Company;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $company = Company::first();
        
        if (!$company) {
            echo "❌ No hay empresas. Ejecuta CompanySeeder primero.\n";
            return;
        }
        
        Warehouse::updateOrCreate([
            'company_id' => $company->id,
            'name' => 'Almacén Principal'
        ], [
            'company_id' => $company->id,
            'name' => 'Almacén Principal',
            'code' => 'DEP001',
            'address' => 'Depósito Central - Av. Mariscal López',
            'phone' => '+595 21 123456',
            'is_main' => true,
            'is_active' => true,
        ]);
        
        echo "✅ Almacén creado exitosamente!\n";
    }
}
