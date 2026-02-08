<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\InvoiceSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class DirectPrintController extends Controller
{
    public function printTicket($saleId)
    {
        try {
            Log::info('DirectPrintController::printTicket called with saleId: ' . $saleId);
            
            // Cargar la venta con todas las relaciones necesarias
            $sale = Sale::with(['saleItems.product', 'customer', 'user'])->find($saleId);
            
            if (!$sale) {
                Log::warning('Sale not found: ' . $saleId);
                return response()->json([
                    'error' => 'Venta no encontrada',
                    'saleId' => $saleId
                ], 404);
            }
            
            Log::info('Sale found: ' . $sale->sale_number);
            
            // Generar ticket completo
            $ticketContent = $this->generateTicketForBrowser($sale);
            
            return response($ticketContent)
                ->header('Content-Type', 'text/plain; charset=utf-8')
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('X-Print-Direct', 'true');
                
        } catch (\Exception $e) {
            Log::error('Error en DirectPrintController::printTicket', [
                'saleId' => $saleId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al generar ticket: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
    
    // Método para generar comandos ESC/POS reales (para impresoras térmicas)
    public function printTicketESCPOS($saleId)
    {
        try {
            $sale = Sale::with(['saleItems.product', 'customer', 'user'])->find($saleId);
            
            if (!$sale) {
                return response()->json([
                    'error' => 'Venta no encontrada',
                    'saleId' => $saleId
                ], 404);
            }
            
            // Generar comandos ESC/POS para impresora térmica
            $escPos = $this->generateTicketESCPOS($sale);
            
            return response($escPos)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="ticket-' . $sale->sale_number . '.txt"')
                ->header('Cache-Control', 'no-cache, must-revalidate')
                ->header('X-Print-Direct', 'true');
                
        } catch (\Exception $e) {
            \Log::error('Error en DirectPrintController::printTicketESCPOS', [
                'saleId' => $saleId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Error al generar ticket ESC/POS: ' . $e->getMessage()], 500);
        }
    }
    
    // Método para generar comandos RAW para impresoras térmicas
    public function printTicketRaw($saleId)
    {
        try {
            Log::info('DirectPrintController::printTicketRaw called with saleId: ' . $saleId);
            
            $sale = Sale::with(['saleItems.product', 'customer', 'user'])->find($saleId);
            
            if (!$sale) {
                return response()->json([
                    'error' => 'Venta no encontrada',
                    'saleId' => $saleId
                ], 404);
            }
            
            // Generar comandos ESC/POS para impresión directa
            $rawCommands = $this->generateTicketESCPOS($sale);
            
            // Intentar enviar a la impresora directamente
            $printResult = $this->sendToPrinter($rawCommands, $sale->sale_number);
            
            if ($printResult['success']) {
                Log::info('Ticket sent to printer successfully');
                return response()->json([
                    'success' => true,
                    'message' => 'Ticket enviado a impresora correctamente',
                    'saleId' => $saleId,
                    'printerInfo' => $printResult['printer'] ?? null
                ]);
            } else {
                Log::warning('Failed to send to printer, returning raw data');
                return response()->json([
                    'success' => false,
                    'error' => $printResult['error'],
                    'fallback' => 'browser_print'
                ]);
            }
                
        } catch (\Exception $e) {
            Log::error('Error en DirectPrintController::printTicketRaw', [
                'saleId' => $saleId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Error al generar ticket raw: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Envía datos directamente a la impresora del sistema
     */
    private function sendToPrinter($rawData, $saleNumber)
    {
        try {
            // Para Windows, usar el comando PRINT o copy con puerto paralelo/USB
            if (PHP_OS_FAMILY === 'Windows') {
                return $this->sendToWindowsPrinter($rawData, $saleNumber);
            }
            
            // Para Linux/Unix, usar lp o lpr
            return $this->sendToUnixPrinter($rawData, $saleNumber);
            
        } catch (\Exception $e) {
            Log::error('Error sending to printer: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Envía a impresora en Windows
     */
    private function sendToWindowsPrinter($rawData, $saleNumber)
    {
        try {
            // Crear archivo temporal
            $tempFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ticket_' . $saleNumber . '_' . time() . '.prn';
            file_put_contents($tempFile, $rawData);
            
            // Obtener lista de impresoras instaladas
            $installedPrinters = $this->getWindowsPrinters();
            Log::info('Impresoras encontradas en Windows:', $installedPrinters);
            
            // Lista priorizada de nombres de impresoras térmicas/POS
            $thermalPrinters = [
                'TM-U220',          // Epson TM-U220 (detectado en sistema)
                'Receipt',          // Impresoras de recibo
                'POS-80',           // Nombre común genérico
                'POS-58',           // 58mm
                'POS',              // Nombre genérico
                'Thermal',          // Impresoras térmicas
                'TM-T88',           // Epson TM-T88 series
                'TM-T20',           // Epson TM-T20
                'RP-80',            // Citizen RP-80
                'CT-S310',          // Citizen CT-S310
                'TSP100',           // Star TSP100
                'TSP650',           // Star TSP650
                'XP-80C',           // Xprinter XP-80C
                'XP-58',            // Xprinter 58mm
                '80mm',             // Cualquier con 80mm
                '58mm',             // Cualquier con 58mm
                'BIXOLON',          // Marca Bixolon
                'CUSTOM',           // Marca Custom
                'EPSON',            // Cualquier Epson
                'STAR'              // Cualquier Star
            ];
            
            // Buscar coincidencias entre impresoras instaladas y térmicas
            foreach ($installedPrinters as $installedPrinter) {
                foreach ($thermalPrinters as $thermalName) {
                    if (stripos($installedPrinter, $thermalName) !== false) {
                        $result = $this->printToSpecificPrinter($tempFile, $installedPrinter);
                        if ($result['success']) {
                            return $result;
                        }
                    }
                }
            }
            
            // Si no encontramos térmica específica, intentar con la primera impresora disponible
            if (!empty($installedPrinters)) {
                foreach ($installedPrinters as $printer) {
                    $result = $this->printToSpecificPrinter($tempFile, $printer);
                    if ($result['success']) {
                        return $result;
                    }
                }
            }
            
            // Último intento con impresora predeterminada del sistema
            $command = "print \"$tempFile\"";
            exec($command, $output, $returnCode);
            
            unlink($tempFile);
            
            if ($returnCode === 0) {
                return [
                    'success' => true,
                    'printer' => 'Sistema predeterminado',
                    'method' => 'windows_print_default'
                ];
            }
            
            return [
                'success' => false,
                'error' => 'No se pudo encontrar ninguna impresora funcional'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Error en impresión Windows: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtiene lista de impresoras instaladas en Windows
     */
    private function getWindowsPrinters()
    {
        try {
            // Usar PowerShell para obtener impresoras
            $command = 'powershell "Get-WmiObject -Query \\"SELECT Name FROM Win32_Printer\\" | Select-Object -ExpandProperty Name"';
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && !empty($output)) {
                return array_filter($output, function($printer) {
                    return !empty(trim($printer));
                });
            }
            
            // Fallback: usar wmic si PowerShell falla
            $command = 'wmic printer get name /format:csv | findstr /v "Node"';
            exec($command, $output2, $returnCode2);
            
            if ($returnCode2 === 0) {
                $printers = [];
                foreach ($output2 as $line) {
                    if (strpos($line, ',') !== false) {
                        $parts = explode(',', $line);
                        if (isset($parts[1]) && !empty(trim($parts[1]))) {
                            $printers[] = trim($parts[1]);
                        }
                    }
                }
                return $printers;
            }
            
            return [];
            
        } catch (\Exception $e) {
            Log::error('Error obteniendo impresoras Windows: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Imprime a una impresora específica
     */
    private function printToSpecificPrinter($tempFile, $printerName)
    {
        try {
            // Método 1: copy con UNC path
            $command = "copy /B \"$tempFile\" \"\\\\localhost\\$printerName\" > NUL 2>&1";
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                unlink($tempFile);
                return [
                    'success' => true,
                    'printer' => $printerName,
                    'method' => 'windows_copy_unc'
                ];
            }
            
            // Método 2: PowerShell Out-Printer
            $escapedPrinter = str_replace('"', '`"', $printerName);
            $command = "powershell \"Get-Content '$tempFile' | Out-Printer -Name '$escapedPrinter'\"";
            exec($command, $output2, $returnCode2);
            
            if ($returnCode2 === 0) {
                unlink($tempFile);
                return [
                    'success' => true,
                    'printer' => $printerName,
                    'method' => 'powershell_out_printer'
                ];
            }
            
            return [
                'success' => false,
                'error' => "No se pudo imprimir en $printerName (códigos: $returnCode, $returnCode2)"
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => "Error imprimiendo en $printerName: " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Envía a impresora en sistemas Unix/Linux
     */
    private function sendToUnixPrinter($rawData, $saleNumber)
    {
        try {
            // Crear archivo temporal
            $tempFile = '/tmp/ticket_' . $saleNumber . '_' . time() . '.prn';
            file_put_contents($tempFile, $rawData);
            
            // Intentar con lp (cups)
            $command = "lp -d POS-80 \"$tempFile\" 2>&1";
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                unlink($tempFile);
                return [
                    'success' => true,
                    'printer' => 'POS-80',
                    'method' => 'cups_lp'
                ];
            }
            
            // Intentar con impresora predeterminada
            $command = "lp \"$tempFile\" 2>&1";
            exec($command, $output, $returnCode);
            
            unlink($tempFile);
            
            if ($returnCode === 0) {
                return [
                    'success' => true,
                    'printer' => 'default',
                    'method' => 'cups_default'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'No se pudo imprimir con CUPS'
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Error en impresión Unix: ' . $e->getMessage()
            ];
        }
    }
    
    private function generateTicketForBrowser($sale)
    {
        try {
            Log::info('Generating ticket for browser...');
            
            // Configuración de la empresa
            $settings = InvoiceSetting::getSettings();
            
            // Ancho del ticket (caracteres)
            $ticketWidth = 40;
            $content = '';
            
            // === HEADER DE LA EMPRESA ===
            $companyName = $settings->company_name ?? 'BODEGA APP PARAGUAY';
            $content .= $this->centerText($companyName, $ticketWidth) . "\n";
            
            $rucText = "RUC: " . ($settings->company_ruc ?? '80123456-7');
            $content .= $this->centerText($rucText, $ticketWidth) . "\n";
            
            $address = $settings->company_address ?? 'Av. Brasil 123, Asunción, Paraguay';
            $content .= $this->centerText($address, $ticketWidth) . "\n";
            
            $phone = "Tel: " . ($settings->company_phone ?? '+595 21 123-4567');
            $content .= $this->centerText($phone, $ticketWidth) . "\n";
            
            $content .= str_repeat("-", $ticketWidth) . "\n\n";
            
            // === INFORMACIÓN DEL TICKET ===
            $content .= $this->centerText("TICKET DE VENTA", $ticketWidth) . "\n";
            $content .= "Nro: " . $sale->sale_number . "\n";
            $content .= "Fecha: " . $sale->sale_date->format('d/m/Y H:i:s') . "\n";
            
            // Vendedor con manejo seguro
            $userName = 'Administrador Sistema';
            if ($sale->user && $sale->user->name) {
                $userName = $sale->user->name;
            }
            $content .= "Vendedor: " . $userName . "\n";
            $content .= str_repeat(".", $ticketWidth) . "\n\n";
            
            // === PRODUCTOS ===
            foreach ($sale->saleItems as $item) {
                // Nombre del producto
                $productName = $item->product_name;
                if (strlen($productName) > $ticketWidth) {
                    $productName = substr($productName, 0, $ticketWidth - 3) . "...";
                }
                $content .= $productName . "\n";
                
                // Cantidad x Precio = Total
                $quantity = number_format($item->quantity, 0);
                $unitPrice = number_format($item->unit_price, 0);
                $total = number_format($item->total_price, 0);
                
                $leftSide = "{$quantity} x Gs. {$unitPrice}";
                $rightSide = "Gs. {$total}";
                $spaces = $ticketWidth - strlen($leftSide) - strlen($rightSide);
                
                $content .= $leftSide . str_repeat(" ", max(1, $spaces)) . $rightSide . "\n\n";
            }
            
            $content .= str_repeat("-", $ticketWidth) . "\n";
            
            // === TOTALES ===
            $subtotalText = "Subtotal:";
            $subtotalAmount = "Gs. " . number_format($sale->subtotal, 0);
            $content .= $this->alignText($subtotalText, $subtotalAmount, $ticketWidth) . "\n";
            
            $ivaText = "IVA (10%):";
            $ivaAmount = "Gs. " . number_format($sale->tax_amount, 0);
            $content .= $this->alignText($ivaText, $ivaAmount, $ticketWidth) . "\n\n";
            
            // TOTAL en línea destacada
            $totalText = "TOTAL:";
            $totalAmount = "Gs. " . number_format($sale->total_amount, 0);
            $content .= $this->alignText($totalText, $totalAmount, $ticketWidth) . "\n\n";
            
            $content .= str_repeat(".", $ticketWidth) . "\n";
            
            // === INFORMACIÓN DE PAGO ===
            $content .= $this->centerText("INFORMACIÓN DE PAGO", $ticketWidth) . "\n";
            
            $methodText = "Método:";
            $methodValue = $this->getPaymentMethodName($sale->payment_method);
            $content .= $this->alignText($methodText, $methodValue, $ticketWidth) . "\n";
            
            $receivedText = "Recibido:";
            $receivedAmount = "Gs. " . number_format($sale->amount_paid, 0);
            $content .= $this->alignText($receivedText, $receivedAmount, $ticketWidth) . "\n";
            
            if ($sale->change_amount > 0) {
                $changeText = "Cambio:";
                $changeAmount = "Gs. " . number_format($sale->change_amount, 0);
                $content .= $this->alignText($changeText, $changeAmount, $ticketWidth) . "\n";
            }
            
            $content .= str_repeat(".", $ticketWidth) . "\n\n";
            
            // === FOOTER ===
            $content .= $this->centerText("Gracias por su compra. Visite nuestro sitio web:", $ticketWidth) . "\n";
            $content .= $this->centerText("www.bodegaapp.com.py", $ticketWidth) . "\n\n";
            
            $content .= $this->centerText("¡Gracias por su compra!", $ticketWidth) . "\n\n";
            $content .= $this->centerText("Visite nuestro sitio web", $ticketWidth) . "\n";
            $content .= $this->centerText("www.bodegaapp.com.py", $ticketWidth) . "\n";
            $content .= str_repeat(".", $ticketWidth) . "\n\n";
            
            Log::info('Ticket content generated successfully, length: ' . strlen($content));
            
            return $content;
            
        } catch (\Exception $e) {
            Log::error('Error in generateTicketForBrowser: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function centerText($text, $width)
    {
        $padding = max(0, $width - strlen($text));
        $leftPadding = intval($padding / 2);
        return str_repeat(" ", $leftPadding) . $text;
    }
    
    private function alignText($leftText, $rightText, $width)
    {
        $spaces = $width - strlen($leftText) - strlen($rightText);
        return $leftText . str_repeat(" ", max(1, $spaces)) . $rightText;
    }

    private function generateTicketESCPOS($sale)
    {
        // Configuración de la empresa
        $settings = InvoiceSetting::getSettings();
        
        $esc = chr(27); // ESC
        $gs = chr(29);  // GS
        
        $content = '';
        
        // Inicializar impresora
        $content .= $esc . "@"; // Inicializar
        $content .= $esc . "a" . chr(1); // Centrar texto
        
        // Header de la empresa
        $content .= $esc . "!" . chr(16); // Texto grande
        $content .= ($settings->company_name ?? 'BODEGA APP PARAGUAY') . "\n";
        $content .= $esc . "!" . chr(0); // Texto normal
        $content .= "RUC: " . ($settings->company_ruc ?? '80124567-1') . "\n";
        $content .= ($settings->company_address ?? 'Av. Brasil 123, Asunción, Paraguay') . "\n";
        $content .= "Tel: " . ($settings->company_phone ?? '+595 21 123-4567') . "\n";
        $content .= str_repeat("-", 32) . "\n\n";
        
        // Tipo de documento
        $content .= $esc . "!" . chr(8); // Texto mediano
        $content .= "TICKET DE VENTA\n";
        $content .= $esc . "!" . chr(0); // Texto normal
        $content .= "Nro: " . $sale->sale_number . "\n";
        $content .= "Fecha: " . $sale->sale_date->format('d/m/Y H:i:s') . "\n";
        $content .= "Vendedor: " . ($sale->user->name ?? 'Administrador Sistema') . "\n";
        $content .= str_repeat(".", 32) . "\n\n";
        
        // Alinear a la izquierda para productos
        $content .= $esc . "a" . chr(0);
        
        // Productos
        foreach ($sale->saleItems as $item) {
            $productName = substr($item->product_name, 0, 20);
            $quantity = number_format($item->quantity, 0);
            $price = number_format($item->unit_price, 0);
            $total = number_format($item->total_price, 0);
            
            $content .= $productName . "\n";
            $content .= sprintf("%s x Gs. %s%sGs. %s\n", 
                $quantity, 
                $price,
                str_repeat(" ", max(1, 15 - strlen($quantity . ' x Gs. ' . $price))),
                $total
            );
        }
        
        $content .= str_repeat("-", 32) . "\n";
        
        // Totales - centrar
        $content .= $esc . "a" . chr(1);
        $content .= "Subtotal:" . str_repeat(" ", 15) . "Gs. " . number_format($sale->subtotal, 0) . "\n";
        $content .= "IVA (10%):" . str_repeat(" ", 14) . "Gs. " . number_format($sale->tax_amount, 0) . "\n";
        $content .= "\n";
        $content .= $esc . "!" . chr(8); // Texto grande para total
        $content .= "TOTAL:" . str_repeat(" ", 17) . "Gs. " . number_format($sale->total_amount, 0) . "\n";
        $content .= $esc . "!" . chr(0); // Texto normal
        $content .= "\n";
        $content .= str_repeat(".", 32) . "\n";
        
        // Información de pago
        $content .= "INFORMACIÓN DE PAGO\n";
        $content .= "Método:" . str_repeat(" ", 17) . $this->getPaymentMethodName($sale->payment_method) . "\n";
        $content .= "Recibido:" . str_repeat(" ", 15) . "Gs. " . number_format($sale->amount_paid, 0) . "\n";
        if ($sale->change_amount > 0) {
            $content .= "Cambio:" . str_repeat(" ", 17) . "Gs. " . number_format($sale->change_amount, 0) . "\n";
        }
        $content .= str_repeat(".", 32) . "\n\n";
        
        // Footer
        $content .= "Gracias por su compra. Visite nuestro sitio web:\n";
        $content .= "www.bodegaapp.com.py\n\n";
        $content .= $esc . "!" . chr(8); // Texto grande
        $content .= "¡Gracias por su compra!\n";
        $content .= $esc . "!" . chr(0); // Texto normal
        $content .= "\nVisite nuestro sitio web\n";
        $content .= "www.bodegaapp.com.py\n";
        $content .= str_repeat(".", 32) . "\n\n";
        
        // Cortar papel (si la impresora lo soporta)
        $content .= $gs . "V" . chr(65) . chr(3);
        
        return $content;
    }
    
    private function getPaymentMethodName($method)
    {
        $methods = [
            'CASH' => 'Efectivo',
            'CARD' => 'Tarjeta',
            'TRANSFER' => 'Transferencia',
            'CREDIT' => 'Crédito'
        ];
        
        return $methods[$method] ?? $method;
    }
}