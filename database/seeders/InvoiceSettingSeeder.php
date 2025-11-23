<?php

namespace Database\Seeders;

use App\Models\InvoiceSetting;
use Illuminate\Database\Seeder;

class InvoiceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvoiceSetting::create([
            'company_name' => 'Bodega App Paraguay',
            'company_ruc' => '80123456-7',
            'company_address' => 'Av. Brasil 123, Asunción, Paraguay',
            'company_phone' => '+595 21 123-4567',
            'company_email' => 'contacto@bodegaapp.com.py',
            'invoice_prefix' => 'FACT-',
            'invoice_suffix' => '',
            'invoice_counter' => 1,
            'invoice_auto_increment' => true,
            'ticket_prefix' => 'TKT-',
            'ticket_suffix' => '',
            'ticket_counter' => 1,
            'ticket_auto_increment' => true,
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'footer_text' => 'Gracias por su compra. Visite nuestro sitio web: www.bodegaapp.com.py',
            'terms_conditions' => 'Esta venta se realiza bajo las condiciones generales de venta. El cliente tiene derecho a cambio o devolución dentro de los 30 días posteriores a la compra, presentando este comprobante y el producto en perfecto estado.',
            'default_iva_rate' => 10.00,
        ]);
    }
}
