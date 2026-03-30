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
    
    /**
     * Devuelve los datos ESC/POS en base64 para RawBT (Android)
     */
    public function escposBase64($saleId)
    {
        try {
            $sale = Sale::with(['saleItems.product', 'customer', 'user'])->find($saleId);

            if (!$sale) {
                return response()->json(['success' => false, 'error' => 'Venta no encontrada'], 404);
            }

            $escpos = $this->generateTicketESCPOS($sale);

            return response()->json([
                'success' => true,
                'base64'  => base64_encode($escpos),
                'sale'    => $sale->sale_number,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Impresión de prueba al puerto COM Bluetooth configurado
     */
    public function printTest()
    {
        try {
            $settings = InvoiceSetting::getSettings();
            $comPort  = $settings->ticket_printer ?? null;

            if (!$comPort) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No hay puerto COM configurado. Guardá la configuración primero.',
                ], 422);
            }

            $esc = chr(27);
            $gs  = chr(29);
            $lf  = chr(10);
            $W   = 32;

            $lines = [
                $this->pad(strtoupper($settings->company_name ?: 'MI EMPRESA'), $W, 'center'),
                str_repeat('-', $W),
                $this->pad('*** IMPRESION DE PRUEBA ***', $W, 'center'),
                str_repeat('-', $W),
                'Puerto    : ' . $comPort,
                'Impresora : 3nStar PPT305BT',
                'Fecha     : ' . date('d/m/Y H:i:s'),
                str_repeat('-', $W),
                $this->pad('La impresora funciona OK!', $W, 'center'),
                '',
                '',
                '',
            ];

            $content = $esc . '@';
            foreach ($lines as $line) {
                $content .= iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', $line) . $lf;
            }
            $content .= $gs . 'V' . chr(1);

            $result = $this->sendToComPort($comPort, $content);

            if (!$result['success']) {
                return response()->json(['success' => false, 'error' => $result['error']], 500);
            }

            Log::info("Print test enviado a $comPort");
            return response()->json(['success' => true, 'message' => "Prueba enviada a $comPort"]);

        } catch (\Exception $e) {
            Log::error('Error en printTest: '.$e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Impresión directa al puerto COM Bluetooth (3nStar PPT305BT y similares)
     */
    public function printBluetooth($saleId)
    {
        try {
            $sale = Sale::with(['saleItems.product', 'customer', 'user'])->find($saleId);

            if (!$sale) {
                return response()->json(['success' => false, 'error' => 'Venta no encontrada'], 404);
            }

            $settings   = InvoiceSetting::getSettings();
            $comPort    = $settings->ticket_printer ?? null;

            if (!$comPort) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No hay puerto COM configurado. Configurá la impresora en Configuración → Facturación.',
                ], 422);
            }

            $escPos = $this->generateTicketESCPOS($sale);

            $result = $this->sendToComPort($comPort, $escPos);

            if (!$result['success']) {
                return response()->json(['success' => false, 'error' => $result['error']], 500);
            }

            Log::info("Ticket enviado a $comPort para venta {$sale->sale_number}");

            return response()->json([
                'success' => true,
                'message' => "Ticket enviado a $comPort",
                'sale'    => $sale->sale_number,
            ]);

        } catch (\Exception $e) {
            Log::error('Error en printBluetooth: '.$e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Envía datos binarios a un puerto COM usando PowerShell SerialPort.
     * Más confiable que fopen() para puertos Bluetooth bajo Apache/PHP.
     */
    private function sendToComPort(string $comPort, string $data): array
    {
        try {
            // Escribir los bytes en un archivo temporal
            $tmpFile = tempnam(sys_get_temp_dir(), 'escpos_') . '.bin';
            file_put_contents($tmpFile, $data);

            $tmpFileWin = str_replace('/', '\\', $tmpFile);

            // PowerShell: leer el archivo como bytes y enviarlo por SerialPort
            $ps = implode('; ', [
                "\$bytes = [System.IO.File]::ReadAllBytes('$tmpFileWin')",
                "\$port  = New-Object System.IO.Ports.SerialPort('$comPort', 9600, 'None', 8, 'One')",
                "\$port.ReadTimeout  = 2000",
                "\$port.WriteTimeout = 5000",
                "\$port.Open()",
                "\$port.Write(\$bytes, 0, \$bytes.Length)",
                "\$port.Close()",
            ]);

            $cmd    = 'powershell.exe -NonInteractive -WindowStyle Hidden -Command "' . str_replace('"', '\"', $ps) . '" 2>&1';
            $output = [];
            $code   = 0;
            exec($cmd, $output, $code);

            @unlink($tmpFile);

            Log::info("sendToComPort $comPort → code=$code", $output);

            if ($code !== 0) {
                $errMsg = implode(' ', $output);
                return ['success' => false, 'error' => "Error al enviar a $comPort (código $code): $errMsg"];
            }

            return ['success' => true];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Detecta puertos COM Bluetooth disponibles en Windows
     */
    public function detectBluetoothPorts()
    {
        try {
            $output = [];
            exec('powershell.exe -Command "Get-WMIObject Win32_SerialPort | Select-Object DeviceID, Name | ConvertTo-Json" 2>NUL', $output);
            $json  = implode('', $output);
            $ports = [];

            if ($json) {
                $data = json_decode($json, true);
                // PowerShell devuelve objeto si hay 1, array si hay varios
                if (isset($data['DeviceID'])) {
                    $data = [$data];
                }
                foreach ((array) $data as $p) {
                    if (!empty($p['DeviceID'])) {
                        $ports[] = [
                            'port' => $p['DeviceID'],
                            'name' => $p['Name'] ?? $p['DeviceID'],
                        ];
                    }
                }
            }

            return response()->json(['success' => true, 'ports' => $ports]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'ports' => [], 'error' => $e->getMessage()]);
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
        $settings = InvoiceSetting::getSettings();

        $esc = chr(27);
        $gs  = chr(29);
        $lf  = chr(10);
        $W   = 32; // ancho en caracteres para 80mm

        $lines = [];

        // Cabecera empresa
        $lines[] = $this->pad(strtoupper($settings->company_name ?? 'MI EMPRESA'), $W, 'center');
        if ($settings->company_ruc) {
            $lines[] = $this->pad('RUC: ' . $settings->company_ruc, $W, 'center');
        }
        if ($settings->company_address) {
            $lines[] = $this->pad($settings->company_address, $W, 'center');
        }
        if ($settings->company_phone) {
            $lines[] = $this->pad('Tel: ' . $settings->company_phone, $W, 'center');
        }
        $lines[] = str_repeat('-', $W);
        $lines[] = $this->pad('TICKET DE VENTA', $W, 'center');
        $lines[] = str_repeat('-', $W);
        $lines[] = 'Nro   : ' . $sale->sale_number;
        $lines[] = 'Fecha : ' . $sale->sale_date->format('d/m/Y H:i');
        $lines[] = 'Cajero: ' . ($sale->user->name ?? 'Admin');
        $lines[] = str_repeat('.', $W);

        // Productos
        foreach ($sale->saleItems as $item) {
            $name = mb_substr($item->product_name, 0, $W);
            $lines[] = $name;
            $left  = number_format($item->quantity, 0) . ' x Gs.' . number_format($item->unit_price, 0);
            $right = 'Gs.' . number_format($item->total_price, 0);
            $lines[] = $this->pad2col($left, $right, $W);
        }

        $lines[] = str_repeat('-', $W);

        // Totales
        $lines[] = $this->pad2col('Subtotal:', 'Gs.' . number_format($sale->subtotal, 0), $W);
        $lines[] = $this->pad2col('IVA (10%):', 'Gs.' . number_format($sale->tax_amount, 0), $W);
        $lines[] = str_repeat('-', $W);
        $lines[] = $this->pad2col('TOTAL:', 'Gs.' . number_format($sale->total_amount, 0), $W);
        $lines[] = str_repeat('=', $W);

        // Pago
        $lines[] = $this->pad2col('Metodo:', $this->getPaymentMethodName($sale->payment_method), $W);
        $lines[] = $this->pad2col('Recibido:', 'Gs.' . number_format($sale->amount_paid, 0), $W);
        if ($sale->change_amount > 0) {
            $lines[] = $this->pad2col('Cambio:', 'Gs.' . number_format($sale->change_amount, 0), $W);
        }
        $lines[] = str_repeat('.', $W);
        $lines[] = $this->pad('Gracias por su compra!', $W, 'center');
        $lines[] = '';
        $lines[] = '';
        $lines[] = '';

        // Convertir cada línea de UTF-8 a CP850 (encoding de impresoras térmicas)
        $content = $esc . '@'; // inicializar impresora
        foreach ($lines as $line) {
            $encoded  = iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', $line);
            $content .= $encoded . $lf;
        }

        // Corte de papel
        $content .= $gs . 'V' . chr(1); // corte parcial

        return $content;
    }

    private function pad(string $text, int $width, string $align = 'left'): string
    {
        $len = mb_strlen($text);
        if ($len >= $width) return mb_substr($text, 0, $width);
        $pad = $width - $len;
        if ($align === 'center') return str_repeat(' ', (int)floor($pad / 2)) . $text;
        if ($align === 'right')  return str_repeat(' ', $pad) . $text;
        return $text . str_repeat(' ', $pad);
    }

    private function pad2col(string $left, string $right, int $width): string
    {
        $spaces = $width - mb_strlen($left) - mb_strlen($right);
        return $left . str_repeat(' ', max(1, $spaces)) . $right;
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