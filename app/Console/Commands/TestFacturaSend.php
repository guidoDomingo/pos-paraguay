<?php

namespace App\Console\Commands;

use App\Services\FacturaSendService;
use Illuminate\Console\Command;

class TestFacturaSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facturasend:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar conexión y configuración de FacturaSend';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Probando conexión con FacturaSend...');
        $this->newLine();

        try {
            $facturaService = new FacturaSendService();
            
            // 1. Validar configuración
            $this->info('1. Validando configuración...');
            $configErrors = $facturaService->validateConfiguration();
            
            if (!empty($configErrors)) {
                $this->error('❌ Errores de configuración:');
                foreach ($configErrors as $error) {
                    $this->error("   • $error");
                }
                return Command::FAILURE;
            }
            
            $this->info('✅ Configuración válida');
            
            // 2. Mostrar configuración actual
            $this->newLine();
            $this->info('2. Configuración actual:');
            $this->table(['Parámetro', 'Valor'], [
                ['Entorno', config('facturasend.environment', 'No configurado')],
                ['Habilitado', config('facturasend.enabled') ? 'Sí' : 'No'],
                ['Tenant ID', config('facturasend.tenant_id') ? 'Configurado' : 'No configurado'],
                ['API Key', config('facturasend.api_key') ? 'Configurado (oculto)' : 'No configurado'],
                ['URL API', 'https://api.facturasend.com.py'],
            ]);

            // 3. Test de conexión
            $this->newLine();
            $this->info('3. Probando conexión...');
            
            $connectionTest = $facturaService->testConnection();
            
            if ($connectionTest['success']) {
                $this->info('✅ ' . $connectionTest['message']);
                $this->info("   Ambiente: {$connectionTest['environment']}");
            } else {
                $this->error('❌ ' . $connectionTest['message']);
                return Command::FAILURE;
            }
            
            // 4. Mostrar estadísticas
            $this->newLine();
            $this->info('4. Estadísticas de facturas electrónicas:');
            
            $totalElectronic = \App\Models\Invoice::where('is_electronic', true)->count();
            $approved = \App\Models\Invoice::where('electronic_status', 'approved')->count();
            $pending = \App\Models\Invoice::where('electronic_status', 'generated')->count();
            $errors = \App\Models\Invoice::where('electronic_status', 'error')->count();
            
            $this->table(['Estado', 'Cantidad'], [
                ['Total electrónicas', $totalElectronic],
                ['Aprobadas', $approved],
                ['Pendientes', $pending],
                ['Con errores', $errors],
            ]);

            $this->newLine();
            $this->info('🎉 Prueba completada exitosamente!');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Error durante la prueba: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
