<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\FacturaSendService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResendElectronicInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facturasend:resend {invoice? : ID de la factura específica a reenviar} {--all : Reenviar todas las facturas con error}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reenviar facturas electrónicas que tuvieron error';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $invoiceId = $this->argument('invoice');
        $resendAll = $this->option('all');

        if (!config('facturasend.enabled')) {
            $this->error('❌ FacturaSend no está habilitado en la configuración.');
            return Command::FAILURE;
        }

        try {
            $facturaService = new FacturaSendService();

            if ($invoiceId) {
                // Reenviar factura específica
                return $this->resendSingleInvoice($invoiceId, $facturaService);
                
            } elseif ($resendAll) {
                // Reenviar todas las facturas con error
                return $this->resendAllErrorInvoices($facturaService);
                
            } else {
                // Mostrar facturas con error y permitir selección
                return $this->selectAndResend($facturaService);
            }

        } catch (\Exception $e) {
            $this->error('❌ Error durante el reenvío: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Reenviar una factura específica
     */
    private function resendSingleInvoice(int $invoiceId, FacturaSendService $service): int
    {
        $invoice = Invoice::find($invoiceId);
        
        if (!$invoice) {
            $this->error("❌ No se encontró la factura con ID: {$invoiceId}");
            return Command::FAILURE;
        }

        if (!$invoice->is_electronic) {
            $this->error("❌ La factura {$invoiceId} no es una factura electrónica.");
            return Command::FAILURE;
        }

        $this->info("🔄 Reenviando factura {$invoiceId} a FacturaSend...");

        $result = $service->sendInvoice($invoice, false);

        if ($result['success']) {
            $this->info("✅ Factura {$invoiceId} reenviada exitosamente.");
            $this->info("   CDC: {$result['cdc']}");
            return Command::SUCCESS;
        } else {
            $this->error("❌ Error reenviando factura {$invoiceId}: {$result['error']}");
            return Command::FAILURE;
        }
    }

    /**
     * Reenviar todas las facturas con error
     */
    private function resendAllErrorInvoices(FacturaSendService $service): int
    {
        $errorInvoices = Invoice::where('is_electronic', true)
                               ->where('electronic_status', 'error')
                               ->orderBy('id')
                               ->get();

        if ($errorInvoices->isEmpty()) {
            $this->info('✅ No hay facturas electrónicas con error para reenviar.');
            return Command::SUCCESS;
        }

        $this->info("🔄 Reenviando {$errorInvoices->count()} facturas con error...");
        $this->newLine();

        $success = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar($errorInvoices->count());
        $bar->start();

        foreach ($errorInvoices as $invoice) {
            try {
                $result = $service->sendInvoice($invoice, false);
                
                if ($result['success']) {
                    $success++;
                } else {
                    $failed++;
                    Log::warning("Error reenviando factura {$invoice->id}", [
                        'invoice_id' => $invoice->id,
                        'error' => $result['error']
                    ]);
                }
                
                $bar->advance();
                
                // Pausa para no sobrecargar la API
                usleep(500000); // 0.5 segundos
                
            } catch (\Exception $e) {
                $failed++;
                Log::error("Excepción reenviando factura {$invoice->id}", [
                    'invoice_id' => $invoice->id,
                    'error' => $e->getMessage()
                ]);
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Mostrar resumen
        $this->info('📊 Resumen del reenvío:');
        $this->table(['Estado', 'Cantidad'], [
            ['Exitosos', $success],
            ['Fallidos', $failed],
            ['Total', $errorInvoices->count()],
        ]);

        if ($success > 0) {
            $this->info("✅ {$success} facturas reenviadas exitosamente.");
        }
        
        if ($failed > 0) {
            $this->warn("⚠️  {$failed} facturas fallaron al reenviar. Revisar logs para detalles.");
        }

        return Command::SUCCESS;
    }

    /**
     * Mostrar facturas con error y permitir selección
     */
    private function selectAndResend(FacturaSendService $service): int
    {
        $errorInvoices = Invoice::where('is_electronic', true)
                               ->where('electronic_status', 'error')
                               ->with(['customer', 'sale'])
                               ->orderBy('id', 'desc')
                               ->limit(20)
                               ->get();

        if ($errorInvoices->isEmpty()) {
            $this->info('✅ No hay facturas electrónicas con error.');
            return Command::SUCCESS;
        }

        $this->info('📋 Facturas electrónicas con error (últimas 20):');
        $this->newLine();

        $tableData = [];
        foreach ($errorInvoices as $invoice) {
            $tableData[] = [
                $invoice->id,
                $invoice->invoice_number ?? 'N/A',
                $invoice->customer_name ?? 'N/A', 
                $invoice->total_amount,
                $invoice->electronic_sent_at?->format('d/m/Y H:i') ?? 'N/A',
                substr($invoice->electronic_error ?? '', 0, 50) . '...'
            ];
        }

        $this->table([
            'ID', 'Número', 'Cliente', 'Total', 'Enviado', 'Error'
        ], $tableData);

        $this->newLine();
        $this->info('Opciones:');
        $this->info('  • Especificar ID: facturasend:resend {id}');
        $this->info('  • Reenviar todas: facturasend:resend --all');

        return Command::SUCCESS;
    }
}
