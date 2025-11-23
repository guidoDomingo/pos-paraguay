<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FiscalStamp;
use App\Models\Company;
use Carbon\Carbon;

class FiscalStampSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $company = Company::first();
        
        FiscalStamp::create([
            'company_id' => $company->id,
            'stamp_number' => '12345678',
            'valid_from' => Carbon::now(),
            'valid_until' => Carbon::now()->addYear(),
            'establishment' => '001',
            'point_of_sale' => '001',
            'current_invoice_number' => 1,
            'max_invoice_number' => 999999,
            'is_active' => true,
        ]);
        
        echo "✅ Timbre fiscal creado exitosamente!\n";
    }
}
