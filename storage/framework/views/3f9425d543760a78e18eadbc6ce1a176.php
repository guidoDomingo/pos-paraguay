<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura <?php echo e($invoice->invoice_number); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #000;
            background: #fff;
        }

        /* ── PÁGINA COMPLETA ── */
        .page-wrap {
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }

        /* ── MITAD DE PÁGINA (una copia) ── */
        .copy-half {
            height: 144mm;
            overflow: hidden;
            padding: 3.5mm;
        }

        /* ── SEPARADOR CENTRAL ── */
        .cut-separator {
            height: 9mm;
            border-top: 2px dashed #444;
            border-bottom: 2px dashed #444;
            text-align: center;
            font-size: 7.5px;
            color: #555;
            letter-spacing: 2px;
            line-height: 9mm;
        }

        /* ── BLOQUE DE FACTURA ── */
        .invoice-block {
            border: 2px solid #000;
            padding: 4px 6px;
            height: 100%;
            overflow: hidden;
        }

        /* ── CABECERA ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000;
            margin-bottom: 3px;
        }
        .header-table td { vertical-align: top; padding: 2px 5px; }
        .header-left  { width: 58%; border-right: 2px solid #000; }
        .header-right { width: 42%; text-align: center; }

        .company-name     { font-size: 11px; font-weight: bold; text-decoration: underline; margin-bottom: 1px; color: #000; }
        .company-sub      { font-size: 7.5px; margin-bottom: 1px; color: #000; }

        .stamp-label   { font-size: 7.5px; font-weight: bold; }
        .stamp-number  { font-size: 9px; font-weight: bold; margin-bottom: 1px; }
        .ruc-line      { font-size: 8.5px; margin-bottom: 1px; }
        .factura-title { font-size: 17px; font-weight: bold; letter-spacing: 2px; margin: 1px 0; }
        .vigencia-line { font-size: 7px; margin-bottom: 1px; }

        .invoice-number-box {
            border: 2px solid #000;
            display: inline-block;
            padding: 2px 7px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 2px;
            letter-spacing: 1px;
        }

        /* ── DATOS DEL CLIENTE ── */
        .client-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 3px;
        }
        .client-table td {
            padding: 1px 4px;
            border: 1px solid #000;
            font-size: 8px;
        }
        .check-box {
            display: inline-block;
            width: 8px;
            height: 8px;
            border: 1px solid #000;
            margin-right: 2px;
            text-align: center;
            line-height: 8px;
            font-size: 7px;
            font-weight: bold;
        }

        /* ── TABLA DE ÍTEMS ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 7.5px;
        }
        .items-table th {
            border: 1px solid #000;
            padding: 1px 3px;
            text-align: center;
            font-weight: bold;
        }
        .items-table td {
            border: 1px solid #000;
            padding: 0px 3px;
            height: 10px;
        }
        .col-cant  { width: 7%;  text-align: center; }
        .col-desc  { width: 46%; }
        .col-pu    { width: 13%; text-align: right; }
        .col-exent { width: 12%; text-align: right; }
        .col-5     { width: 10%; text-align: right; }
        .col-10    { width: 12%; text-align: right; }

        /* ── TOTALES ── */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            border: 1px solid #000;
            padding: 1px 4px;
            font-size: 8px;
        }
        .label-cell  { font-weight: bold; width: 55%; }
        .amount-cell { text-align: right; width: 45%; }

        /* ── LIQUIDACIÓN IVA ── */
        .iva-footer {
            width: 100%;
            border-collapse: collapse;
        }
        .iva-footer td {
            border: 1px solid #000;
            padding: 1px 4px;
            font-size: 7.5px;
        }

        /* ── PIE ── */
        .footer-text {
            font-size: 7px;
            color: #555;
            text-align: center;
            margin-top: 2px;
        }
    </style>
</head>
<body>

<?php
    $companyName     = $settings->company_name     ?? '';
    $companyActivity = $settings->company_activity ?? '';
    $companyPhone    = $settings->company_phone    ?? '';
    $companyAddress  = $settings->company_address  ?? '';
    $companyRuc      = $settings->company_ruc      ?? '';

    $fecha     = \Carbon\Carbon::parse($invoice->invoice_date);
    $meses     = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                  'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    $mesNombre = $meses[$fecha->month - 1];

    $esContado = strtoupper($invoice->condition ?? '') === 'CONTADO';
    $esCredito = !$esContado;

    $valorParcial = ($invoice->subtotal_exento ?? 0)
                  + ($invoice->subtotal_iva_5  ?? 0)
                  + ($invoice->subtotal_iva_10 ?? 0);

    $isA4 = in_array(strtolower($settings->paper_size ?? 'letter'), ['a4', 'letter']);

    // Filas vacías para rellenar la mitad de la página
    // Calculado para ~133mm de área de ítems disponible
    $targetRows = $isA4 ? 14 : 10;
    $emptyRows  = max(0, $targetRows - count($invoice->items));
?>

<?php if($isA4): ?>

<div class="page-wrap">

    
    <div class="copy-half">
        <div class="invoice-block">
            <?php echo $__env->make('pdf.partials.invoice-content', [
                'copyLabel' => 'Original: Comprador'
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    
    <div class="cut-separator">
        ✂ &nbsp;&nbsp; SEPARAR POR LA LÍNEA PUNTEADA &nbsp;&nbsp; ✂
    </div>

    
    <div class="copy-half">
        <div class="invoice-block">
            <?php echo $__env->make('pdf.partials.invoice-content', [
                'copyLabel' => 'Primera Copia: Arch. Tributario'
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

</div>
<?php else: ?>

<div style="padding:5mm;">
    <div class="invoice-block" style="border:2px solid #000; padding:5px 7px;">
        <?php echo $__env->make('pdf.partials.invoice-content', [
            'copyLabel' => 'Original: Comprador | Primera Copia: Arch. Tributario'
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php endif; ?>

</body>
</html>
<?php /**PATH C:\laragon\www\bodega-app\resources\views/pdf/invoice.blade.php ENDPATH**/ ?>