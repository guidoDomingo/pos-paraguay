<?php

namespace App\Console\Commands;

use App\Models\FiscalStamp;
use App\Models\Company;
use App\Services\InvoiceNumberService;
use Illuminate\Console\Command;

class CreateFiscalStamp extends Command
{
    protected $signature = 'pos:create-fiscal-stamp
                          {--company= : ID de la empresa}
                          {--stamp-number= : Número del timbrado}
                          {--establishment= : Establecimiento (ej: 001)}
                          {--point-of-sale= : Punto de expedición (ej: 002)}
                          {--valid-from= : Fecha inicio vigencia (Y-m-d)}
                          {--valid-until= : Fecha fin vigencia (Y-m-d)}
                          {--max-invoices=999999 : Número máximo de facturas}';

    protected $description = 'Crea un nuevo timbrado fiscal para facturación DNIT';

    public function handle()
    {
        $this->info('📋 Creando nuevo timbrado fiscal...');

        $companies = Company::all();
        if ($companies->isEmpty()) {
            $this->error('No hay empresas registradas. Ejecute pos:setup primero.');
            return 1;
        }

        // Seleccionar empresa
        $companyId = $this->option('company');
        if (!$companyId) {
            $companyChoices = [];
            foreach ($companies as $company) {
                $companyChoices[$company->id] = $company->name;
            }
            
            $selectedName = $this->choice('Seleccione la empresa:', array_values($companyChoices));
            $companyId = array_search($selectedName, $companyChoices);
        }

        $company = Company::find($companyId);
        if (!$company) {
            $this->error('Empresa no encontrada');
            return 1;
        }

        // Recopilar datos del timbrado
        $data = [
            'company_id' => $company->id,
            'stamp_number' => $this->option('stamp-number') ?: 
                            $this->ask('Número del timbrado (8 dígitos)'),
            'establishment' => $this->option('establishment') ?: 
                             $this->ask('Establecimiento (3 dígitos, ej: 001)', '001'),
            'point_of_sale' => $this->option('point-of-sale') ?: 
                              $this->ask('Punto de expedición (3 dígitos, ej: 002)', '002'),
            'valid_from' => $this->option('valid-from') ?: 
                           $this->ask('Fecha inicio vigencia (Y-m-d)', now()->format('Y-m-d')),
            'valid_until' => $this->option('valid-until') ?: 
                            $this->ask('Fecha fin vigencia (Y-m-d)', now()->addYear()->format('Y-m-d')),
            'max_invoice_number' => $this->option('max-invoices') ?: 
                                   $this->ask('Número máximo de facturas', '999999'),
            'current_invoice_number' => 0,
            'is_active' => true,
        ];

        // Validaciones
        if (!preg_match('/^\d{8}$/', $data['stamp_number'])) {
            $this->error('El número del timbrado debe tener exactamente 8 dígitos');
            return 1;
        }

        if (!preg_match('/^\d{3}$/', $data['establishment'])) {
            $this->error('El establecimiento debe tener exactamente 3 dígitos');
            return 1;
        }

        if (!preg_match('/^\d{3}$/', $data['point_of_sale'])) {
            $this->error('El punto de expedición debe tener exactamente 3 dígitos');
            return 1;
        }

        // Verificar si ya existe un timbrado con los mismos datos
        $existing = FiscalStamp::where('company_id', $company->id)
            ->where('stamp_number', $data['stamp_number'])
            ->where('establishment', $data['establishment'])
            ->where('point_of_sale', $data['point_of_sale'])
            ->first();

        if ($existing) {
            $this->error('Ya existe un timbrado con estos datos');
            return 1;
        }

        try {
            $invoiceService = new InvoiceNumberService();
            $fiscalStamp = $invoiceService->createFiscalStamp($data);

            $this->info('✅ Timbrado fiscal creado exitosamente!');
            $this->newLine();
            
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['Empresa', $company->name],
                    ['Timbrado', $fiscalStamp->stamp_number],
                    ['Establecimiento', $fiscalStamp->establishment],
                    ['Punto de Expedición', $fiscalStamp->point_of_sale],
                    ['Vigencia Desde', $fiscalStamp->valid_from->format('d/m/Y')],
                    ['Vigencia Hasta', $fiscalStamp->valid_until->format('d/m/Y')],
                    ['Máximo Facturas', number_format($fiscalStamp->max_invoice_number)],
                    ['Próximo Número', $invoiceService->previewNextInvoiceNumber($company->id)],
                ]
            );

            return 0;

        } catch (\Exception $e) {
            $this->error('Error al crear el timbrado fiscal: ' . $e->getMessage());
            return 1;
        }
    }
}