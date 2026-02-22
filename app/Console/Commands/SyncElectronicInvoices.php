<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\FacturaSendService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncElectronicInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facturasend:sync {--limit=50 : Límite de facturas a procesar} {--force : Forzar sincronización incluso si hay errores}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar estados de facturas electrónicas con FacturaSend/SIFEN';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $force = $this->option('force');

        $this->info('🔄 Sincronizando facturas electrónicas con FacturaSend...');
        $this->newLine();

        if (!config('facturasend.enabled')) {
            $this->error('❌ FacturaSend no está habilitado en la configuración.');
            return Command::FAILURE;
        }

        try {
            $facturaService = new FacturaSendService();

            // Obtener facturas electrónicas pendientes de sincronización
            $query = Invoice::where('is_electronic', true)
                           ->where('electronic_status', 'generated'); // Solo las que están generadas

            if (!$force) {
                // Excluir las que tenían error hace menos de 1 hora para evitar spam
                $query->where(function($q) {
                    $q->where('electronic_status', '!=', 'error')
                      ->orWhere('updated_at', '<', now()->subHour());
                });
            }

            $invoices = $query->orderBy('electronic_sent_at')
                             ->limit($limit)
                             ->get();

            if ($invoices->isEmpty()) {
                $this->info('✅ No hay facturas electrónicas pendientes de sincronización.');
                return Command::SUCCESS;
            }

            $processed = 0;
            $approved = 0;
            $rejected = 0;
            $errors = 0;

            $bar = $this->output->createProgressBar($invoices->count());
            $bar->start();

            foreach ($invoices as $invoice) {
                try {
                    if (!$invoice->cdc) {
                        $this->newLine();
                        $this->warn("⚠️  Factura {$invoice->id} no tiene CDC. Saltando...");
                        continue;
                    }

                    // Consultar estado en FacturaSend
                    $statusResponse = $facturaService->checkDocumentStatus($invoice->cdc);

                    if ($statusResponse['success']) {
                        $documentData = $statusResponse['result'] ?? [];
                        $newStatus = $this->mapFacturaSendStatus($documentData['estado'] ?? null);

                        if ($newStatus && $newStatus !== $invoice->electronic_status) {
                            $invoice->update([
                                'electronic_status' => $newStatus,
                                'electronic_approved_at' => $newStatus === 'approved' ? now() : null,
                                'electronic_error' => null,
                            ]);

                            if ($newStatus === 'approved') {
                                $approved++;
                            } elseif ($newStatus === 'rejected') {
                                $rejected++;
                            }

                            Log::info("Factura electrónica sincronizada", [
                                'invoice_id' => $invoice->id,
                                'cdc' => $invoice->cdc,
                                'old_status' => $invoice->electronic_status,
                                'new_status' => $newStatus
                            ]);
                        }
                    } else {
                        // Error consultando estado
                        $invoice->update([
                            'electronic_error' => $statusResponse['error'] ?? 'Error desconocido consultando estado'
                        ]);
                        $errors++;
                    }

                    $processed++;
                    $bar->advance();
                    
                    // Pausa pequeña para no sobrecargar la API
                    usleep(200000); // 0.2 segundos

                } catch (\Exception $e) {
                    $this->newLine();
                    $this->error("❌ Error procesando factura {$invoice->id}: " . $e->getMessage());
                    
                    $invoice->update([
                        'electronic_error' => 'Error de sincronización: ' . $e->getMessage()
                    ]);
                    $errors++;
                    
                    // Continuar con la siguiente factura
                    continue;
                }
            }

            $bar->finish();
            $this->newLine(2);

            // Mostrar resumen
            $this->info('📊 Resumen de sincronización:');
            $this->table(['Estado', 'Cantidad'], [
                ['Procesadas', $processed],
                ['Aprobadas', $approved],
                ['Rechazadas', $rejected],
                ['Con errores', $errors],
            ]);

            if ($approved > 0 || $rejected > 0) {
                $this->info('✅ Sincronización completada con cambios de estado.');
            } else {
                $this->info('✅ Sincronización completada. No hubo cambios de estado.');
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error durante la sincronización: ' . $e->getMessage());
            Log::error('Error en sincronización de facturas electrónicas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Mapear estado de FacturaSend a estado local
     */
    private function mapFacturaSendStatus(?string $facturasenStatus): ?string
    {
        return match($facturasenStatus) {
            '1', 'aprobado', 'approved' => 'approved',
            '2', 'rechazado', 'rejected' => 'rejected',
            '0', 'generado', 'generated' => 'generated',
            'error' => 'error',
            default => null
        };
    }
}
