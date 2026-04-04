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
     * Test: texto plano sin ESC/POS, para diagnosticar impresora en blanco
     */
    public function escposBase64PlainTest()
    {
        $lf = chr(10);
        $content  = "PRUEBA DE IMPRESION" . $lf;
        $content .= "--------------------" . $lf;
        $content .= "Si ves esto OK!" . $lf;
        $content .= date('d/m/Y H:i:s') . $lf;
        $content .= $lf . $lf . $lf;
        return response()->json(['success' => true, 'base64' => base64_encode($content)]);
    }

    /**
     * Devuelve ESC/POS de FACTURA en base64 para PrintBridge (Android)
     */
    public function escposBase64Invoice($saleId)
    {
        try {
            $sale = Sale::with(['saleItems.product', 'customer', 'user', 'invoice.fiscalStamp'])->find($saleId);

            if (!$sale) {
                return response()->json(['success' => false, 'error' => 'Venta no encontrada'], 404);
            }

            $escpos = $this->generateInvoiceESCPOS($sale);

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
     * Impresión de FACTURA al puerto COM Bluetooth (Windows)
     */
    public function printBluetoothInvoice($saleId)
    {
        try {
            $sale = Sale::with(['saleItems.product', 'customer', 'user', 'invoice.fiscalStamp'])->find($saleId);

            if (!$sale) {
                return response()->json(['success' => false, 'error' => 'Venta no encontrada'], 404);
            }

            $settings = InvoiceSetting::getSettings();
            $escPos   = $this->generateInvoiceESCPOS($sale);
            $result   = $this->sendEscPosToConfiguredPrinter($escPos, $settings);

            if (!$result['success']) {
                return response()->json(['success' => false, 'error' => $result['error']], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Factura enviada a impresora',
                'sale'    => $sale->sale_number,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
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
            $settings  = InvoiceSetting::getSettings();
            $winPrinter = $settings->default_printer ?? null;
            $comPort    = $settings->ticket_printer   ?? null;
            $printerLabel = $winPrinter ?: $comPort ?: '—';

            if (!$winPrinter && !$comPort) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No hay impresora configurada. Guardá la configuración primero.',
                ], 422);
            }

            $esc       = chr(27);
            $gs        = chr(29);
            $lf        = chr(10);
            $marginLen = (int)($settings->printer_left_margin ?? 4);
            $W         = (int)($settings->printer_width ?? 32) - $marginLen;
            $margin    = str_repeat(' ', $marginLen);

            $lines = [
                $this->pad(strtoupper($settings->company_name ?: 'MI EMPRESA'), $W, 'center'),
                str_repeat('-', $W),
                $this->pad('*** IMPRESION DE PRUEBA ***', $W, 'center'),
                str_repeat('-', $W),
                'Impresora : ' . $printerLabel,
                'Fecha     : ' . date('d/m/Y H:i:s'),
                str_repeat('-', $W),
                $this->pad('La impresora funciona OK!', $W, 'center'),
                '',
                '',
                '',
            ];

            $content = $esc . '@';
            foreach ($lines as $line) {
                $content .= iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', $margin . $line) . $lf;
            }
            $content .= $gs . 'V' . chr(1);

            $result = $this->sendEscPosToConfiguredPrinter($content, $settings);

            if (!$result['success']) {
                return response()->json(['success' => false, 'error' => $result['error']], 500);
            }

            Log::info("Print test enviado a $printerLabel");
            return response()->json(['success' => true, 'message' => "Prueba enviada a $printerLabel"]);

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

            $settings = InvoiceSetting::getSettings();
            $escPos   = $this->generateTicketESCPOS($sale);
            $result   = $this->sendEscPosToConfiguredPrinter($escPos, $settings);

            if (!$result['success']) {
                return response()->json(['success' => false, 'error' => $result['error']], 422);
            }

            Log::info("Ticket enviado para venta {$sale->sale_number}");

            return response()->json([
                'success' => true,
                'message' => 'Ticket enviado a impresora',
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
            Log::error('Error en DirectPrintController::printTicketESCPOS', [
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
     * Envía bytes RAW a una impresora Windows usando winspool API.
     */
    private function sendRawToWindowsPrinter(string $filePath, string $printerName): array
    {
        try {
            $escapedPrinter = str_replace("'", "''", $printerName);
            $escapedFile    = str_replace("'", "''", str_replace('/', '\\', $filePath));

            // Usamos class (tipo referencia) para DOCINFOA con MarshalAs — patrón conocido que funciona
            $psScript = <<<'PS'
Add-Type -TypeDefinition @'
using System;
using System.Runtime.InteropServices;
public class RawPrinterHelper {
    [StructLayout(LayoutKind.Sequential, CharSet=CharSet.Ansi)]
    public class DOCINFOA {
        [MarshalAs(UnmanagedType.LPStr)] public string pDocName;
        [MarshalAs(UnmanagedType.LPStr)] public string pOutputFile;
        [MarshalAs(UnmanagedType.LPStr)] public string pDataType;
    }
    [DllImport("winspool.Drv", EntryPoint="OpenPrinterA",   CharSet=CharSet.Ansi, SetLastError=true)]
    public static extern bool OpenPrinter(string szPrinter, out IntPtr hPrinter, IntPtr pd);
    [DllImport("winspool.Drv", EntryPoint="ClosePrinter",   SetLastError=true)]
    public static extern bool ClosePrinter(IntPtr hPrinter);
    [DllImport("winspool.Drv", EntryPoint="StartDocPrinterA", CharSet=CharSet.Ansi, SetLastError=true)]
    public static extern bool StartDocPrinter(IntPtr hPrinter, Int32 level, [In, MarshalAs(UnmanagedType.LPStruct)] DOCINFOA di);
    [DllImport("winspool.Drv", EntryPoint="EndDocPrinter",  SetLastError=true)]
    public static extern bool EndDocPrinter(IntPtr hPrinter);
    [DllImport("winspool.Drv", EntryPoint="StartPagePrinter", SetLastError=true)]
    public static extern bool StartPagePrinter(IntPtr hPrinter);
    [DllImport("winspool.Drv", EntryPoint="EndPagePrinter", SetLastError=true)]
    public static extern bool EndPagePrinter(IntPtr hPrinter);
    [DllImport("winspool.Drv", EntryPoint="WritePrinter",   SetLastError=true)]
    public static extern bool WritePrinter(IntPtr hPrinter, IntPtr pBytes, Int32 dwCount, out Int32 dwWritten);
}
'@ -ErrorAction Stop

$printerName = 'PRINTER_PLACEHOLDER'
$fileName    = 'FILE_PLACEHOLDER'

$bytes = [System.IO.File]::ReadAllBytes($fileName)
if ($bytes.Length -eq 0) { throw "Archivo ESC/POS vacio: $fileName" }

$hPrinter = [IntPtr]::Zero
if (-not [RawPrinterHelper]::OpenPrinter($printerName, [ref]$hPrinter, [IntPtr]::Zero)) {
    throw "No se pudo abrir: $printerName (error $([System.Runtime.InteropServices.Marshal]::GetLastWin32Error()))"
}

$di = New-Object RawPrinterHelper+DOCINFOA
$di.pDocName  = 'ESC/POS'
$di.pDataType = 'RAW'

if (-not [RawPrinterHelper]::StartDocPrinter($hPrinter, 1, $di)) {
    [RawPrinterHelper]::ClosePrinter($hPrinter)
    throw "StartDocPrinter fallo (error $([System.Runtime.InteropServices.Marshal]::GetLastWin32Error()))"
}

[RawPrinterHelper]::StartPagePrinter($hPrinter) | Out-Null

$pBytes = [System.Runtime.InteropServices.Marshal]::AllocCoTaskMem($bytes.Length)
[System.Runtime.InteropServices.Marshal]::Copy($bytes, 0, $pBytes, $bytes.Length)
$written = 0
$ok = [RawPrinterHelper]::WritePrinter($hPrinter, $pBytes, $bytes.Length, [ref]$written)
[System.Runtime.InteropServices.Marshal]::FreeCoTaskMem($pBytes)

[RawPrinterHelper]::EndPagePrinter($hPrinter) | Out-Null
[RawPrinterHelper]::EndDocPrinter($hPrinter) | Out-Null
[RawPrinterHelper]::ClosePrinter($hPrinter) | Out-Null

if (-not $ok -or $written -eq 0) {
    throw "WritePrinter fallo: ok=$ok written=$written"
}
Write-Output "OK:$written"
PS;

            $psScript = str_replace('PRINTER_PLACEHOLDER', $escapedPrinter, $psScript);
            $psScript = str_replace('FILE_PLACEHOLDER',    $escapedFile,    $psScript);

            $psFile = str_replace('/', '\\', tempnam(sys_get_temp_dir(), 'rawprint_') . '.ps1');
            file_put_contents($psFile, $psScript);

            $cmd    = "powershell.exe -NonInteractive -ExecutionPolicy Bypass -File \"$psFile\" 2>&1";
            $output = [];
            $code   = 0;
            exec($cmd, $output, $code);
            @unlink($psFile);

            $out = implode(' ', $output);
            Log::info("sendRawToWindowsPrinter '$printerName' → code=$code | $out");

            if ($code !== 0 || stripos($out, 'OK:') === false) {
                return ['success' => false, 'error' => "Error al enviar a '$printerName': $out"];
            }

            return ['success' => true];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Endpoint: lista impresoras instaladas en Windows
     */
    public function detectWindowsPrinters()
    {
        $printers = $this->getWindowsPrinters();
        $list = array_values(array_filter(array_map('trim', (array)$printers)));
        return response()->json(['success' => true, 'printers' => $list]);
    }

    /**
     * Envía ESC/POS según configuración: impresora Windows o COM port.
     */
    private function sendEscPosToConfiguredPrinter(string $escPos, InvoiceSetting $settings): array
    {
        $winPrinter = $settings->default_printer ?? null;
        $comPort    = $settings->ticket_printer  ?? null;

        if ($winPrinter) {
            $tmpFile = tempnam(sys_get_temp_dir(), 'escpos_') . '.prn';
            file_put_contents($tmpFile, $escPos);
            $tmpFileWin = str_replace('/', '\\', $tmpFile);
            $result = $this->sendRawToWindowsPrinter($tmpFileWin, $winPrinter);
            @unlink($tmpFile);
            return $result;
        }

        if ($comPort) {
            return $this->sendToComPort($comPort, $escPos);
        }

        return ['success' => false, 'error' => 'No hay impresora configurada. Configurá una en Configuración → Facturación.'];
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

        $esc    = chr(27);
        $gs     = chr(29);
        $lf     = chr(10);
        $marginLen = (int)($settings->printer_left_margin ?? 4);
        $W         = (int)($settings->printer_width ?? 32) - $marginLen;
        $margin    = str_repeat(' ', $marginLen);

        $lines = [];
        array_push($lines, ...$this->wrapLines($this->pad(strtoupper($settings->company_name ?? 'MI EMPRESA'), $W, 'center'), $W));
        if ($settings->company_ruc)     array_push($lines, ...$this->wrapLines($this->pad('RUC: ' . $settings->company_ruc, $W, 'center'), $W));
        if ($settings->company_address) array_push($lines, ...$this->wrapLines($this->pad($settings->company_address, $W, 'center'), $W));
        if ($settings->company_phone)   $lines[] = $this->pad('Tel: ' . $settings->company_phone, $W, 'center');
        $lines[] = str_repeat('-', $W);
        $lines[] = $this->pad('TICKET DE VENTA', $W, 'center');
        $lines[] = str_repeat('-', $W);
        $lines[] = 'Nro   : ' . $sale->sale_number;
        $lines[] = 'Fecha : ' . $sale->sale_date->format('d/m/Y H:i');
        array_push($lines, ...$this->wrapLines('Cajero: ' . ($sale->user->name ?? 'Admin'), $W, '        '));
        $lines[] = str_repeat('.', $W);

        foreach ($sale->saleItems as $item) {
            array_push($lines, ...$this->wrapLines($item->product_name, $W));
            $left  = number_format($item->quantity, 0) . ' x Gs.' . number_format($item->unit_price, 0);
            $right = 'Gs.' . number_format($item->total_price, 0);
            array_push($lines, ...$this->pad2colWrap($left, $right, $W));
        }

        $lines[] = str_repeat('-', $W);
        array_push($lines, ...$this->pad2colWrap('Subtotal:', 'Gs.' . number_format($sale->subtotal, 0), $W));
        array_push($lines, ...$this->pad2colWrap('IVA (10%):', 'Gs.' . number_format($sale->tax_amount, 0), $W));
        $lines[] = str_repeat('-', $W);
        array_push($lines, ...$this->pad2colWrap('TOTAL:', 'Gs.' . number_format($sale->total_amount, 0), $W));
        $lines[] = str_repeat('=', $W);
        array_push($lines, ...$this->pad2colWrap('Metodo:', $this->getPaymentMethodName($sale->payment_method), $W));
        array_push($lines, ...$this->pad2colWrap('Recibido:', 'Gs.' . number_format($sale->amount_paid, 0), $W));
        if ($sale->change_amount > 0) {
            array_push($lines, ...$this->pad2colWrap('Cambio:', 'Gs.' . number_format($sale->change_amount, 0), $W));
        }
        $lines[] = str_repeat('.', $W);
        $lines[] = $this->pad('Gracias por su compra!', $W, 'center');
        $lines[] = '';
        $lines[] = '';
        $lines[] = '';

        $content = $esc . '@';
        foreach ($lines as $line) {
            $content .= iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', $margin . $line) . $lf;
        }
        $content .= $gs . 'V' . chr(1);

        return $content;
    }

    private function generateInvoiceESCPOS($sale)
    {
        $settings = InvoiceSetting::getSettings();
        $invoice  = $sale->invoice;

        $esc    = chr(27);
        $gs     = chr(29);
        $lf     = chr(10);
        $marginLen = (int)($settings->printer_left_margin ?? 4);
        $W         = (int)($settings->printer_width ?? 32) - $marginLen;
        $margin    = str_repeat(' ', $marginLen);

        $lines = [];

        // Cabecera empresa
        array_push($lines, ...$this->wrapLines($this->pad(strtoupper($settings->company_name ?? 'MI EMPRESA'), $W, 'center'), $W));
        if ($settings->company_ruc)     array_push($lines, ...$this->wrapLines($this->pad('RUC: ' . $settings->company_ruc, $W, 'center'), $W));
        if ($settings->company_address) array_push($lines, ...$this->wrapLines($settings->company_address, $W));
        if ($settings->company_phone)   $lines[] = 'Tel: ' . $settings->company_phone;
        $lines[] = str_repeat('-', $W);

        // Timbrado
        if ($invoice) {
            $lines[] = $this->pad('Timbrado: ' . $invoice->stamp_number, $W, 'center');
            $fiscalStamp = $invoice->fiscalStamp ?? null;
            if ($fiscalStamp) {
                $lines[] = $this->pad(
                    \Carbon\Carbon::parse($fiscalStamp->valid_from)->format('d/m/Y')
                    . ' al '
                    . \Carbon\Carbon::parse($fiscalStamp->valid_until)->format('d/m/Y'),
                    $W, 'center'
                );
            }
        }

        $lines[] = $this->pad('FACTURA', $W, 'center');
        if ($invoice) $lines[] = $this->pad($invoice->invoice_number, $W, 'center');
        $lines[] = str_repeat('-', $W);

        $lines[] = 'Fecha : ' . $sale->sale_date->format('d/m/Y H:i');
        $lines[] = 'Cond  : ' . ($sale->sale_condition ?? 'CONTADO');
        array_push($lines, ...$this->wrapLines('Cajero: ' . ($sale->user->name ?? 'Admin'), $W, '        '));

        // Datos del cliente
        $clientName    = $invoice->customer_name    ?? $sale->customer_name    ?? ($sale->customer->name    ?? '');
        $clientRuc     = $invoice->customer_ruc     ?? $sale->customer_ruc     ?? ($sale->customer->ruc     ?? '');
        $clientAddress = $invoice->customer_address ?? $sale->customer_address ?? ($sale->customer->address ?? '');
        $clientPhone   = $sale->customer->phone ?? '';

        if ($clientName) {
            $lines[] = str_repeat('.', $W);
            array_push($lines, ...$this->wrapLines('Cliente: ' . $clientName, $W, '         '));
            if ($clientRuc)     array_push($lines, ...$this->wrapLines('RUC    : ' . $clientRuc, $W, '         '));
            if ($clientAddress) array_push($lines, ...$this->wrapLines('Dir    : ' . $clientAddress, $W, '         '));
            if ($clientPhone)   $lines[] = 'Tel    : ' . $clientPhone;
        }

        $lines[] = str_repeat('-', $W);
        $lines[] = $this->pad2col('DESCRIPCION', 'TOTAL', $W);
        $lines[] = str_repeat('.', $W);

        foreach ($sale->saleItems as $item) {
            array_push($lines, ...$this->wrapLines($item->product_name, $W));
            $left  = number_format($item->quantity, 0) . ' x Gs.' . number_format($item->unit_price, 0);
            $right = 'Gs.' . number_format($item->total_price, 0);
            array_push($lines, ...$this->pad2colWrap($left, $right, $W));
        }

        $lines[] = str_repeat('-', $W);

        if ($invoice && ($invoice->subtotal_exento > 0 || $invoice->subtotal_iva_5 > 0)) {
            if ($invoice->subtotal_exento > 0)
                array_push($lines, ...$this->pad2colWrap('Exentas:', 'Gs.' . number_format($invoice->subtotal_exento, 0), $W));
            if ($invoice->subtotal_iva_5 > 0)
                array_push($lines, ...$this->pad2colWrap('Grav. 5%:', 'Gs.' . number_format($invoice->subtotal_iva_5, 0), $W));
            if ($invoice->subtotal_iva_10 > 0)
                array_push($lines, ...$this->pad2colWrap('Grav. 10%:', 'Gs.' . number_format($invoice->subtotal_iva_10, 0), $W));
            $lines[] = str_repeat('-', $W);
        } else {
            array_push($lines, ...$this->pad2colWrap('Subtotal:', 'Gs.' . number_format($sale->subtotal, 0), $W));
            array_push($lines, ...$this->pad2colWrap('IVA (10%):', 'Gs.' . number_format($sale->tax_amount, 0), $W));
            $lines[] = str_repeat('-', $W);
        }

        array_push($lines, ...$this->pad2colWrap('TOTAL:', 'Gs.' . number_format($sale->total_amount, 0), $W));
        $lines[] = str_repeat('=', $W);
        array_push($lines, ...$this->pad2colWrap('Metodo:', $this->getPaymentMethodName($sale->payment_method), $W));
        array_push($lines, ...$this->pad2colWrap('Recibido:', 'Gs.' . number_format($sale->amount_paid, 0), $W));
        if ($sale->change_amount > 0) {
            array_push($lines, ...$this->pad2colWrap('Cambio:', 'Gs.' . number_format($sale->change_amount, 0), $W));
        }

        if ($invoice && $invoice->total_iva > 0) {
            $lines[] = str_repeat('.', $W);
            if ($invoice->total_iva_5  > 0) array_push($lines, ...$this->pad2colWrap('Liq. IVA 5%:', 'Gs.' . number_format($invoice->total_iva_5, 0), $W));
            if ($invoice->total_iva_10 > 0) array_push($lines, ...$this->pad2colWrap('Liq. IVA 10%:', 'Gs.' . number_format($invoice->total_iva_10, 0), $W));
            array_push($lines, ...$this->pad2colWrap('Total IVA:', 'Gs.' . number_format($invoice->total_iva, 0), $W));
        }

        $lines[] = str_repeat('.', $W);
        $lines[] = $this->pad('Gracias por su compra!', $W, 'center');
        $lines[] = '';
        $lines[] = '';
        $lines[] = '';

        $content = $esc . '@';
        foreach ($lines as $line) {
            $content .= iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', $margin . $line) . $lf;
        }
        $content .= $gs . 'V' . chr(1);

        return $content;
    }

    /**
     * Divide un texto largo en múltiples líneas que caben en $width caracteres.
     * Las líneas de continuación llevan $indent de sangría.
     */
    private function wrapLines(string $text, int $width, string $indent = '  '): array
    {
        if (mb_strlen($text) <= $width) {
            return [$text];
        }
        $words  = explode(' ', $text);
        $lines  = [];
        $current = '';
        $first  = true;
        foreach ($words as $word) {
            $maxW = $first ? $width : ($width - mb_strlen($indent));
            $test = $current === '' ? $word : $current . ' ' . $word;
            if (mb_strlen($test) <= $maxW) {
                $current = $test;
            } else {
                if ($current !== '') {
                    $lines[] = $first ? $current : $indent . $current;
                    $first = false;
                }
                $current = $word;
            }
        }
        if ($current !== '') {
            $lines[] = $first ? $current : $indent . $current;
        }
        return $lines ?: [$text];
    }

    /**
     * Dos columnas. Si no caben en una línea, el valor derecho va en la siguiente.
     */
    private function pad2colWrap(string $left, string $right, int $width): array
    {
        if (mb_strlen($left) + 1 + mb_strlen($right) <= $width) {
            return [$this->pad2col($left, $right, $width)];
        }
        return [$left, $this->pad($right, $width, 'right')];
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