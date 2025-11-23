<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Warehouse;

class CreateInitialData extends Command
{
    protected $signature = 'create:initial-data';
    protected $description = 'Create initial company and warehouse data';

    public function handle()
    {
        $this->info('Creando datos iniciales...');
        
        // Crear compañía si no existe
        $company = Company::first();
        if (!$company) {
            $company = Company::create([
                'name' => 'Mi Empresa POS',
                'ruc' => '80000000-0',
                'address' => 'Dirección de la empresa',
                'phone' => '021-123456',
                'email' => 'empresa@pos.com',
                'logo' => null,
                'is_active' => true,
            ]);
            $this->info("Compañía creada: {$company->name}");
        }
        
        // Crear warehouse si no existe
        $warehouse = Warehouse::where('company_id', $company->id)->first();
        if (!$warehouse) {
            $warehouse = Warehouse::create([
                'company_id' => $company->id,
                'name' => 'Depósito Principal',
                'code' => 'DEP001',
                'address' => 'Dirección del depósito',
                'phone' => '021-123456',
                'is_active' => true,
                'is_main' => true,
            ]);
            $this->info("Warehouse creado: {$warehouse->name}");
        }
        
        $this->info('Datos iniciales creados correctamente.');
        return 0;
    }
}