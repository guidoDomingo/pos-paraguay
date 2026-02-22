<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Company;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FacturaSendService
{
    private $apiUrl;
    private $apiKey;
    private $tenantId;
    private $environment;

    public function __construct()
    {
        $this->environment = config('facturasend.environment', 'test'); // test o production
        $this->apiKey = config('facturasend.api_key');
        $this->tenantId = config('facturasend.tenant_id');
        
        // FacturaSend usa la misma URL para test y production
        // La diferencia está en la configuración del ambiente en el panel
        $this->apiUrl = 'https://api.facturasend.com.py';
            
        if (!$this->apiKey || !$this->tenantId) {
            throw new Exception('FacturaSend: API Key y Tenant ID son requeridos. Configurar en .env');
        }
    }

    /**
     * Enviar factura a FacturaSend para facturación electrónica
     */
    public function sendInvoice(Invoice $invoice, bool $draft = false): array
    {
        try {
            Log::info("FacturaSend: Enviando factura {$invoice->id} to FacturaSend");
            
            $data = $this->buildInvoiceData($invoice);
            
            $httpClient = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json; charset=utf-8',
            ]);
            
            // Configuración SSL para desarrollo
            if (config('app.env') === 'local') {
                $httpClient = $httpClient->withOptions([
                    'verify' => false, // Solo para desarrollo
                ]);
            }
            
            $response = $httpClient->post(
                $this->apiUrl . "/{$this->tenantId}/lote/create",
                $data,
                [
                    'draft' => $draft ? 'true' : 'false',
                    'xml' => 'true',
                    'qr' => 'true', 
                    'tax' => 'true'
                ]
            );

            if ($response->successful()) {
                $result = $response->json();
                
                if ($result['success']) {
                    $deData = $result['result']['deList'][0] ?? null;
                    
                    if ($deData) {
                        // Actualizar invoice con datos de FacturaSend
                        $this->updateInvoiceWithResponse($invoice, $result, $deData);
                        
                        Log::info("FacturaSend: Factura {$invoice->id} enviada exitosamente. CDC: " . $deData['cdc']);
                        
                        return [
                            'success' => true,
                            'cdc' => $deData['cdc'],
                            'numero' => $deData['numero'],
                            'loteId' => $result['result']['loteId']
                        ];
                    }
                }
                
                Log::error("FacturaSend Error: " . json_encode($result));
                throw new Exception($result['error'] ?? 'Error desconocido de FacturaSend');
                
            } else {
                Log::error("FacturaSend HTTP Error: " . $response->body());
                throw new Exception('Error de comunicación con FacturaSend: ' . $response->status());
            }
            
        } catch (Exception $e) {
            Log::error("FacturaSend Exception: " . $e->getMessage());
            
            // Actualizar invoice con error
            $invoice->update([
                'electronic_status' => 'error',
                'electronic_error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Consultar estado de documento por CDC
     */
    public function checkDocumentStatus(string $cdc): array
    {
        try {
            $httpClient = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ]);
            
            // Configuración SSL para desarrollo
            if (config('app.env') === 'local') {
                $httpClient = $httpClient->withOptions([
                    'verify' => false, // Solo para desarrollo
                ]);
            }
            
            $response = $httpClient->get($this->apiUrl . "/{$this->tenantId}/de/cdc/{$cdc}");

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Error consultando estado del documento');

        } catch (Exception $e) {
            Log::error("FacturaSend Status Check Error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Obtener XML del documento
     */
    public function getDocumentXML(string $cdc): ?string
    {
        try {
            $httpClient = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);
            
            // Configuración SSL para desarrollo
            if (config('app.env') === 'local') {
                $httpClient = $httpClient->withOptions([
                    'verify' => false, // Solo para desarrollo
                ]);
            }
            
            $response = $httpClient->get($this->apiUrl . "/{$this->tenantId}/de/xml/{$cdc}");

            if ($response->successful()) {
                return $response->body();
            }

            return null;

        } catch (Exception $e) {
            Log::error("FacturaSend XML Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener KuDE (representación impresa) del documento
     */
    public function getDocumentKude(string $cdc): ?string
    {
        try {
            $httpClient = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);
            
            // Configuración SSL para desarrollo
            if (config('app.env') === 'local') {
                $httpClient = $httpClient->withOptions([
                    'verify' => false, // Solo para desarrollo
                ]);
            }
            
            $response = $httpClient->get($this->apiUrl . "/{$this->tenantId}/de/kude/{$cdc}");

            if ($response->successful()) {
                return $response->body();
            }

            return null;

        } catch (Exception $e) {
            Log::error("FacturaSend KuDE Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Construir datos de factura para FacturaSend
     */
    private function buildInvoiceData(Invoice $invoice): array
    {
        $company = $invoice->company;
        $customer = $invoice->customer;
        $items = $invoice->items;

        // Determinar tipo de documento (1 = Factura Electrónica)
        $tipoDocumento = 1;
        
        // Determinar tipo de transacción (1 = Venta de mercadería, 2 = Venta de servicios)
        $tipoTransaccion = 1;
        
        // Determinar tipo de emisión (1 = Normal, 2 = Contingencia)
        $tipoEmision = 1;

        $invoiceData = [
            "tipoDocumento" => $tipoDocumento,
            "establecimiento" => (int)($company->establishment ?? 1),
            "punto" => str_pad($company->point_of_sale ?? '001', 3, '0', STR_PAD_LEFT),
            "numero" => (int)$invoice->number,
            "descripcion" => "Factura electrónica - " . $company->name,
            "observacion" => $invoice->notes ?? "",
            "fecha" => $invoice->created_at->format('Y-m-d\TH:i:s'),
            "tipoEmision" => $tipoEmision,
            "tipoTransaccion" => $tipoTransaccion,
            "tipoImpuesto" => 1, // 1 = IVA
            "moneda" => "PYG",
            "condicionAnticipo" => 1,
            
            // Datos del cliente
            "cliente" => [
                "contribuyente" => !is_null($customer->ruc),
                "ruc" => $customer->ruc,
                "razonSocial" => $customer->name,
                "nombreFantasia" => $customer->business_name ?? $customer->name,
                "tipoOperacion" => 1, // 1 = B2B, 2 = B2C
                "direccion" => $customer->address ?? "No especificado",
                "numeroCasa" => "S/N",
                "departamento" => 11, // Central por defecto
                "distrito" => 1424, // Asunción por defecto  
                "ciudad" => 3344, // Asunción por defecto
                "telefono" => $customer->phone ?? "",
                "celular" => $customer->phone ?? "",
                "email" => $customer->email ?? "",
                "codigo" => $customer->id
            ],
            
            // Condición de la operación
            "condicionOperacion" => [
                "condicionTipo" => $invoice->payment_type === 'credit' ? 2 : 1, // 1 = Contado, 2 = Crédito
                "infoPago" => [
                    [
                        "plazo" => $invoice->payment_type === 'credit' ? 30 : null,
                        "cuota" => $invoice->payment_type === 'credit' ? 1 : null,
                        "monto" => (int)($invoice->total * 100) // FacturaSend usa centavos
                    ]
                ]
            ],

            // Items de la factura
            "items" => $this->buildItemsData($items)
        ];

        return [$invoiceData]; // FacturaSend espera un array
    }

    /**
     * Construir datos de items para FacturaSend
     */
    private function buildItemsData($items): array
    {
        $itemsData = [];
        
        foreach ($items as $item) {
            $product = $item->product;
            
            // Determinar tipo de IVA
            $tipoIva = match($product->tax_type) {
                'exempt' => 1, // Exento
                'five' => 2,   // 5%
                'ten' => 3,    // 10%
                default => 3   // Por defecto 10%
            };

            $itemData = [
                "codigo" => $product->code ?? $product->id,
                "descripcion" => $product->name,
                "observacion" => $product->description ?? "",
                "ncm" => "", // Código Mercosur (opcional)
                "unidadMedida" => 77, // 77 = Unidad (estándar)
                "cantidad" => (int)$item->quantity,
                "precioUnitario" => (int)($item->price * 100), // Centavos
                "cambio" => 100, // Factor de conversión
                "descuentoParticular" => 0,
                "descuentoGlobal" => 0,
                "anticipoGlobal" => 0,
                "anticipoParticular" => 0,
                "ivaTipo" => $tipoIva,
                "ivaBase" => (int)(($item->price * $item->quantity) * 100), // Base imponible
                "iva" => (int)($item->tax_amount * 100) // IVA en centavos
            ];
            
            $itemsData[] = $itemData;
        }
        
        return $itemsData;
    }

    /**
     * Actualizar invoice con respuesta de FacturaSend
     */
    private function updateInvoiceWithResponse(Invoice $invoice, array $result, array $deData): void
    {
        $updateData = [
            'is_electronic' => true,
            'facturasend_id' => $deData['id'] ?? null,
            'cdc' => $deData['cdc'],
            'numero_electronico' => $deData['numero'],
            'electronic_status' => 'generated',
            'electronic_sent_at' => now(),
            'lote_id' => $result['result']['loteId'],
        ];

        // Agregar datos opcionales si están presentes
        if (isset($deData['xml'])) {
            $updateData['xml_data'] = $deData['xml'];
        }
        
        if (isset($deData['qr'])) {
            $updateData['qr_data'] = $deData['qr'];
        }

        $invoice->update($updateData);
    }

    /**
     * Validar configuración de FacturaSend
     */
    public function validateConfiguration(): array
    {
        $errors = [];
        
        if (!$this->apiKey) {
            $errors[] = 'FACTURASEND_API_KEY no configurado';
        }
        
        if (!$this->tenantId) {
            $errors[] = 'FACTURASEND_TENANT_ID no configurado';
        }
        
        return $errors;
    }

    /**
     * Test de conexión con FacturaSend
     */
    public function testConnection(): array
    {
        try {
            $httpClient = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ]);
            
            // Configuración SSL para desarrollo
            if (config('app.env') === 'local') {
                $httpClient = $httpClient->withOptions([
                    'verify' => false, // Solo para desarrollo
                ]);
            }
            
            $response = $httpClient->get($this->apiUrl . "/{$this->tenantId}/contribuyente/parametros");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Conexión exitosa con FacturaSend',
                    'environment' => $this->environment
                ];
            }

            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $response->status()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}