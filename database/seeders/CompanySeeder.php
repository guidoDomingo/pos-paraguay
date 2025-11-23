<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\CompanyConfig;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $company = Company::create([
            'name' => 'Bodega App Demo',
            'trade_name' => 'Bodega App S.A.',
            'ruc' => '80012345',
            'dv' => '1',
            'phone' => '+595 21 123456',
            'email' => 'info@bodegaapp.com',
            'address' => 'Av. Mariscal López 1234, Asunción, Central',
            'activity_description' => 'Comercialización de productos alimenticios y bebidas',
            'taxpayer_type' => 'CONTRIBUYENTE',
            'is_active' => true,
        ]);
        
        // Configuración básica de la empresa
        CompanyConfig::create([
            'company_id' => $company->id,
            'default_iva_rate' => 10.00,
            'invoice_footer_text' => 'Gracias por su compra - BODEGA APP',
            'ticket_footer_text' => 'BODEGA APP - Asunción, Paraguay',
            'print_after_sale' => true,
            'default_print_type' => 'TICKET',
        ]);
        
        echo "✅ Empresa demo creada exitosamente!\n";
    }
}
