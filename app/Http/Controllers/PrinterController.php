<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PrinterController extends Controller
{
    public function getAvailablePrinters(): JsonResponse
    {
        try {
            $printers = [];
            
            if (PHP_OS_FAMILY === 'Windows') {
                // Para Windows, usar PowerShell para obtener impresoras
                $command = 'powershell.exe -Command "Get-WmiObject -Class Win32_Printer | Select-Object Name, PortName, DriverName | ConvertTo-Json"';
                $output = shell_exec($command);
                
                if ($output) {
                    $printersData = json_decode($output, true);
                    
                    // Si hay solo una impresora, PowerShell no devuelve array
                    if (!isset($printersData[0])) {
                        $printersData = [$printersData];
                    }
                    
                    foreach ($printersData as $printer) {
                        if (isset($printer['Name'])) {
                            $printers[] = [
                                'name' => $printer['Name'],
                                'port' => $printer['PortName'] ?? 'N/A',
                                'driver' => $printer['DriverName'] ?? 'N/A',
                                'type' => $this->determinePrinterType($printer['Name'], $printer['DriverName'] ?? '')
                            ];
                        }
                    }
                }
                
                // Agregar impresora por defecto si no hay ninguna
                if (empty($printers)) {
                    $printers[] = [
                        'name' => 'Impresora por Defecto',
                        'port' => 'DEFAULT',
                        'driver' => 'Sistema',
                        'type' => 'general'
                    ];
                }
                
            } else {
                // Para Linux/Unix
                $command = 'lpstat -p -d 2>/dev/null || echo "No printers found"';
                $output = shell_exec($command);
                
                if ($output && !str_contains($output, 'No printers found')) {
                    $lines = explode("\n", trim($output));
                    foreach ($lines as $line) {
                        if (preg_match('/printer\s+(\S+)/', $line, $matches)) {
                            $printers[] = [
                                'name' => $matches[1],
                                'port' => 'CUPS',
                                'driver' => 'CUPS Driver',
                                'type' => 'general'
                            ];
                        }
                    }
                }
                
                if (empty($printers)) {
                    $printers[] = [
                        'name' => 'Impresora por Defecto',
                        'port' => 'DEFAULT',
                        'driver' => 'Sistema',
                        'type' => 'general'
                    ];
                }
            }
            
            // Agregar impresoras PDF virtuales
            $printers[] = [
                'name' => 'PDF (Guardar como archivo)',
                'port' => 'PDF',
                'driver' => 'PDF Driver',
                'type' => 'pdf'
            ];
            
            return response()->json([
                'success' => true,
                'printers' => $printers,
                'count' => count($printers)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener impresoras: ' . $e->getMessage(),
                'printers' => [
                    [
                        'name' => 'Impresora por Defecto',
                        'port' => 'DEFAULT',
                        'driver' => 'Sistema',
                        'type' => 'general'
                    ]
                ]
            ]);
        }
    }
    
    private function determinePrinterType(string $printerName, string $driverName): string
    {
        $name = strtolower($printerName . ' ' . $driverName);
        
        if (str_contains($name, 'thermal') || str_contains($name, 'pos') || str_contains($name, 'receipt')) {
            return 'thermal';
        }
        
        if (str_contains($name, 'pdf') || str_contains($name, 'print to file')) {
            return 'pdf';
        }
        
        if (str_contains($name, 'fax')) {
            return 'fax';
        }
        
        return 'general';
    }
    
    public function testPrinter(Request $request): JsonResponse
    {
        $request->validate([
            'printer_name' => 'required|string'
        ]);
        
        try {
            $printerName = $request->input('printer_name');
            
            // Verificar si es impresora PDF
            if (str_contains(strtolower($printerName), 'pdf')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Impresora PDF configurada correctamente. Los documentos se guardarán como archivos PDF.'
                ]);
            }
            
            if (PHP_OS_FAMILY === 'Windows') {
                // Para impresoras térmicas (como EPSON TM-U220), usar comandos ESC/POS
                if (str_contains(strtolower($printerName), 'epson') || 
                    str_contains(strtolower($printerName), 'tm-') ||
                    str_contains(strtolower($printerName), 'receipt')) {
                    
                    return $this->testThermalPrinter($printerName);
                } else {
                    return $this->testStandardPrinter($printerName);
                }
            } else {
                // En Linux, usar lp command
                return $this->testLinuxPrinter($printerName);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al probar la impresora: ' . $e->getMessage()
            ]);
        }
    }
    
    private function testThermalPrinter(string $printerName): JsonResponse
    {
        try {
            // Crear contenido con comandos ESC/POS básicos
            $esc = chr(27);
            $testContent = $esc . "@"; // Inicializar impresora
            $testContent .= $esc . "a" . chr(1); // Centrar texto
            $testContent .= "PRUEBA DE IMPRESORA\n";
            $testContent .= str_repeat("=", 32) . "\n";
            $testContent .= $esc . "a" . chr(0); // Alinear a la izquierda
            $testContent .= "Fecha: " . date('d/m/Y H:i:s') . "\n";
            $testContent .= "Sistema POS Paraguay\n";
            $testContent .= "Impresora: " . substr($printerName, 0, 20) . "\n";
            $testContent .= str_repeat("-", 32) . "\n";
            $testContent .= "Esta es una pagina de prueba.\n";
            $testContent .= "Si puede ver este texto,\n";
            $testContent .= "la impresora termica esta\n";
            $testContent .= "configurada correctamente.\n";
            $testContent .= str_repeat("=", 32) . "\n";
            $testContent .= $esc . "d" . chr(3); // Alimentar 3 líneas
            $testContent .= $esc . "i"; // Cortar papel (si está disponible)
            
            // Escribir directamente al puerto de la impresora
            $success = $this->sendToPrinterPort($printerName, $testContent);
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Página de prueba enviada a la impresora térmica ' . $printerName . '. Revise la impresora.'
                ]);
            } else {
                // Si falla el método directo, intentar con archivo temporal
                return $this->testStandardPrinter($printerName);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al probar impresora térmica: ' . $e->getMessage()
            ]);
        }
    }
    
    private function testStandardPrinter(string $printerName): JsonResponse
    {
        try {
            // Crear un documento de prueba simple para impresoras estándar
            $testContent = "=== PRUEBA DE IMPRESORA ===\r\n";
            $testContent .= "Fecha: " . date('d/m/Y H:i:s') . "\r\n";
            $testContent .= "Sistema POS Paraguay\r\n";
            $testContent .= "Impresora: {$printerName}\r\n";
            $testContent .= "===========================\r\n";
            $testContent .= "\r\n";
            $testContent .= "Esta es una pagina de prueba.\r\n";
            $testContent .= "Si puede ver este texto,\r\n";
            $testContent .= "la impresora esta configurada\r\n";
            $testContent .= "correctamente.\r\n";
            $testContent .= "\r\n";
            $testContent .= "===========================\r\n";
            
            $tempFile = tempnam(sys_get_temp_dir(), 'printer_test_') . '.txt';
            file_put_contents($tempFile, $testContent);
            
            // Método usando PowerShell para mayor compatibilidad
            $psCommand = "powershell.exe -Command \"" .
                "Add-Type -AssemblyName System.Drawing; " .
                "Add-Type -AssemblyName System.Windows.Forms; " .
                "\$printer = New-Object System.Drawing.Printing.PrintDocument; " .
                "\$printer.PrinterSettings.PrinterName = '{$printerName}'; " .
                "if (\$printer.PrinterSettings.IsValid) { " .
                "Get-Content '{$tempFile}' | Out-Printer -Name '{$printerName}'; " .
                "Write-Output 'SUCCESS' } else { Write-Output 'INVALID' }\"";
            
            $output = shell_exec($psCommand);
            
            // Limpiar archivo temporal
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            
            if (str_contains($output, 'SUCCESS')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Página de prueba enviada correctamente a ' . $printerName . '. Revise la impresora.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo enviar el documento a la impresora. Verifique que la impresora esté conectada y configurada correctamente.'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al probar impresora estándar: ' . $e->getMessage()
            ]);
        }
    }
    
    private function testLinuxPrinter(string $printerName): JsonResponse
    {
        try {
            $testContent = "=== PRUEBA DE IMPRESORA ===\n";
            $testContent .= "Fecha: " . date('d/m/Y H:i:s') . "\n";
            $testContent .= "Sistema POS Paraguay\n";
            $testContent .= "Impresora: {$printerName}\n";
            $testContent .= "===========================\n";
            
            $escapedPrinter = escapeshellarg($printerName);
            $command = "echo " . escapeshellarg($testContent) . " | lp -d {$escapedPrinter}";
            $output = shell_exec($command);
            
            return response()->json([
                'success' => true,
                'message' => 'Página de prueba enviada correctamente a ' . $printerName
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al probar impresora Linux: ' . $e->getMessage()
            ]);
        }
    }
    
    private function sendToPrinterPort(string $printerName, string $content): bool
    {
        try {
            // Intentar escribir directamente al puerto de la impresora
            // Esto funciona especialmente bien con impresoras térmicas
            $handle = fopen("\\\\.\\{$printerName}", 'w');
            
            if ($handle === false) {
                return false;
            }
            
            fwrite($handle, $content);
            fclose($handle);
            
            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function getPrinterStatus(string $printer): JsonResponse
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                // Obtener estado detallado de la impresora en Windows
                $command = "powershell.exe -Command \"Get-WmiObject -Class Win32_Printer | Where-Object {\\$_.Name -eq '{$printer}'} | Select-Object Name, WorkOffline, PrinterStatus, DetectedErrorState, ExtendedPrinterStatus | ConvertTo-Json\"";
                $output = shell_exec($command);
                
                if ($output) {
                    $status = json_decode($output, true);
                    
                    return response()->json([
                        'success' => true,
                        'printer' => $printer,
                        'status' => [
                            'online' => !($status['WorkOffline'] ?? true),
                            'status_code' => $status['PrinterStatus'] ?? 'Unknown',
                            'error_state' => $status['DetectedErrorState'] ?? 'Unknown',
                            'extended_status' => $status['ExtendedPrinterStatus'] ?? 'Unknown',
                            'description' => $this->getPrinterStatusDescription($status['PrinterStatus'] ?? 0)
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se pudo obtener el estado de la impresora'
                    ]);
                }
            } else {
                // En Linux, usar lpstat
                $command = "lpstat -p " . escapeshellarg($printer);
                $output = shell_exec($command);
                
                return response()->json([
                    'success' => true,
                    'printer' => $printer,
                    'status' => [
                        'output' => $output,
                        'description' => 'Estado desde lpstat'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estado: ' . $e->getMessage()
            ]);
        }
    }
    
    private function getPrinterStatusDescription(int $statusCode): string
    {
        switch ($statusCode) {
            case 1: return 'Otro';
            case 2: return 'Desconocido';
            case 3: return 'Inactiva';
            case 4: return 'Imprimiendo';
            case 5: return 'Calentando';
            case 6: return 'Detenida';
            case 7: return 'Imprimiendo y Desconectada';
            default: return 'Estado no definido';
        }
    }
}