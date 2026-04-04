# ============================================================
#  Agente de Impresion POS - Windows
#  Ejecutar: powershell -ExecutionPolicy Bypass -File print-agent.ps1
#  Mantene esta ventana abierta mientras usas el sistema POS.
# ============================================================

Add-Type -TypeDefinition @"
using System;
using System.Runtime.InteropServices;

public class RawPrinterHelper {
    [DllImport("winspool.Drv", EntryPoint="OpenPrinterA", SetLastError=true)]
    public static extern bool OpenPrinter(string szPrinter, out IntPtr hPrinter, IntPtr pd);

    [DllImport("winspool.Drv", EntryPoint="ClosePrinter", SetLastError=true)]
    public static extern bool ClosePrinter(IntPtr hPrinter);

    [DllImport("winspool.Drv", EntryPoint="StartDocPrinterA", SetLastError=true)]
    public static extern int StartDocPrinter(IntPtr hPrinter, int level, [In, MarshalAs(UnmanagedType.LPStruct)] DOCINFOA di);

    [DllImport("winspool.Drv", EntryPoint="EndDocPrinter", SetLastError=true)]
    public static extern bool EndDocPrinter(IntPtr hPrinter);

    [DllImport("winspool.Drv", EntryPoint="StartPagePrinter", SetLastError=true)]
    public static extern bool StartPagePrinter(IntPtr hPrinter);

    [DllImport("winspool.Drv", EntryPoint="EndPagePrinter", SetLastError=true)]
    public static extern bool EndPagePrinter(IntPtr hPrinter);

    [DllImport("winspool.Drv", EntryPoint="WritePrinter", SetLastError=true)]
    public static extern bool WritePrinter(IntPtr hPrinter, IntPtr pBytes, int dwCount, out int dwWritten);

    [StructLayout(LayoutKind.Sequential, CharSet=CharSet.Ansi)]
    public class DOCINFOA {
        [MarshalAs(UnmanagedType.LPStr)] public string pDocName;
        [MarshalAs(UnmanagedType.LPStr)] public string pOutputFile;
        [MarshalAs(UnmanagedType.LPStr)] public string pDataType;
    }

    public static bool SendBytesToPrinter(string printerName, byte[] bytes) {
        IntPtr hPrinter;
        if (!OpenPrinter(printerName, out hPrinter, IntPtr.Zero)) return false;
        var di = new DOCINFOA { pDocName = "RAW", pDataType = "RAW" };
        if (StartDocPrinter(hPrinter, 1, di) == 0) { ClosePrinter(hPrinter); return false; }
        StartPagePrinter(hPrinter);
        IntPtr pBytes = Marshal.AllocCoTaskMem(bytes.Length);
        Marshal.Copy(bytes, 0, pBytes, bytes.Length);
        int written;
        bool ok = WritePrinter(hPrinter, pBytes, bytes.Length, out written);
        Marshal.FreeCoTaskMem(pBytes);
        EndPagePrinter(hPrinter);
        EndDocPrinter(hPrinter);
        ClosePrinter(hPrinter);
        return ok;
    }
}
"@ -Language CSharp

$http = [System.Net.HttpListener]::new()
$http.Prefixes.Add('http://localhost:18000/')
$http.Start()

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Agente de Impresion POS corriendo..." -ForegroundColor Green
Write-Host "  http://localhost:18000/" -ForegroundColor Green
Write-Host "  Cerrrar esta ventana para detener." -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

while ($true) {
    try {
        $ctx  = $http.GetContext()
        $req  = $ctx.Request
        $res  = $ctx.Response

        $res.Headers.Add("Access-Control-Allow-Origin",  "*")
        $res.Headers.Add("Access-Control-Allow-Methods", "POST, GET, OPTIONS")
        $res.Headers.Add("Access-Control-Allow-Headers", "Content-Type")

        if ($req.HttpMethod -eq "OPTIONS") {
            $res.StatusCode = 200
            $res.Close()
            continue
        }

        # GET /printers — lista de impresoras instaladas
        if ($req.Url.LocalPath -eq "/printers" -and $req.HttpMethod -eq "GET") {
            $names   = Get-Printer | Select-Object -ExpandProperty Name
            $payload = ($names | ConvertTo-Json -Compress)
            if (-not $payload) { $payload = "[]" }
            $buf = [System.Text.Encoding]::UTF8.GetBytes($payload)
            $res.ContentType      = "application/json"
            $res.ContentLength64  = $buf.Length
            $res.OutputStream.Write($buf, 0, $buf.Length)
            Write-Host "GET /printers -> $payload"
        }

        # POST /print — imprimir datos ESC/POS en base64
        elseif ($req.Url.LocalPath -eq "/print" -and $req.HttpMethod -eq "POST") {
            $reader = [System.IO.StreamReader]::new($req.InputStream, [System.Text.Encoding]::UTF8)
            $body   = $reader.ReadToEnd()
            $json   = $body | ConvertFrom-Json

            $bytes   = [System.Convert]::FromBase64String($json.data)
            $printer = $json.printer

            if (-not $printer) {
                $printer = (Get-WmiObject -Query "SELECT * FROM Win32_Printer WHERE Default=True").Name
            }

            Write-Host "POST /print -> impresora: '$printer' ($($bytes.Length) bytes)"
            $ok = [RawPrinterHelper]::SendBytesToPrinter($printer, $bytes)

            $payload = if ($ok) { '{"success":true}' } else { '{"success":false,"error":"Error al enviar bytes a la impresora"}' }
            Write-Host "Resultado: $payload"
            $buf = [System.Text.Encoding]::UTF8.GetBytes($payload)
            $res.ContentType      = "application/json"
            $res.ContentLength64  = $buf.Length
            $res.OutputStream.Write($buf, 0, $buf.Length)
        }

        else {
            $res.StatusCode = 404
            $buf = [System.Text.Encoding]::UTF8.GetBytes('{"error":"not found"}')
            $res.ContentLength64 = $buf.Length
            $res.OutputStream.Write($buf, 0, $buf.Length)
        }

        $res.Close()
    }
    catch {
        Write-Host "Error: $_" -ForegroundColor Red
    }
}
