<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura <?php echo e($invoice->invoice_number); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #000;
            background: #fff;
            width: 72mm;
            padding: 4mm 3mm;
        }
        .center  { text-align: center; }
        .right   { text-align: right; }
        .bold    { font-weight: bold; }
        .dashed  { border-top: 1px dashed #000; margin: 4px 0; }
        .dotted  { border-top: 1px dotted #000; margin: 3px 0; }

        /* Cabecera empresa */
        .co-name { font-size: 13px; font-weight: bold; text-transform: uppercase; }
        .co-sub  { font-size: 9px; margin-top: 1px; }

        /* Timbrado */
        .stamp-section { margin: 4px 0; }
        .stamp-label   { font-size: 8px; }
        .stamp-num     { font-size: 11px; font-weight: bold; }
        .factura-title { font-size: 16px; font-weight: bold; letter-spacing: 2px; }
        .inv-number    {
            border: 2px solid #000;
            display: inline-block;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 3px;
            letter-spacing: 1px;
        }

        /* Datos cliente */
        .client-section { margin: 4px 0; font-size: 9px; }
        .client-row     { margin: 2px 0; }
        .field-lbl      { font-size: 8px; color: #333; }

        /* Items */
        .item-row   { margin: 2px 0; padding-bottom: 2px; }
        .item-name  { font-weight: bold; font-size: 9.5px; word-break: break-word; }
        .item-line  { display: table; width: 100%; font-size: 9px; }
        .item-left  { display: table-cell; width: 60%; }
        .item-right { display: table-cell; width: 40%; text-align: right; font-weight: bold; }
        .iva-tag    { font-size: 7.5px; color: #555; }

        /* Totales */
        .total-line { display: table; width: 100%; margin: 2px 0; font-size: 10px; }
        .tl-label   { display: table-cell; }
        .tl-value   { display: table-cell; text-align: right; font-weight: bold; }
        .total-final .tl-label,
        .total-final .tl-value { font-size: 12px; font-weight: bold; }

        /* IVA */
        .iva-section { font-size: 8px; margin: 2px 0; }

        /* Letras */
        .son-section { font-size: 8px; margin: 3px 0; font-style: italic; }

        /* Pie */
        .footer-section { font-size: 8px; text-align: center; margin-top: 4px; }
        .copies-line    { font-size: 8px; font-weight: bold; margin: 3px 0; }
    </style>
</head>
<body>

<?php
    if (!function_exists('numToWordsTicket')) {
    function numToWordsTicket(int $n): string {
        if ($n === 0) return 'CERO';
        $b20 = ['','UNO','DOS','TRES','CUATRO','CINCO','SEIS','SIETE','OCHO','NUEVE',
                'DIEZ','ONCE','DOCE','TRECE','CATORCE','QUINCE','DIECISÉIS','DIECISIETE','DIECIOCHO','DIECINUEVE'];
        $tens = ['','','VEINTE','TREINTA','CUARENTA','CINCUENTA','SESENTA','SETENTA','OCHENTA','NOVENTA'];
        $huns = ['','CIENTO','DOSCIENTOS','TRESCIENTOS','CUATROCIENTOS','QUINIENTOS',
                 'SEISCIENTOS','SETECIENTOS','OCHOCIENTOS','NOVECIENTOS'];
        $parts = [];
        if ($n >= 1000000) { $m = intdiv($n,1000000); $parts[] = $m===1?'UN MILLÓN':numToWordsTicket($m).' MILLONES'; $n%=1000000; }
        if ($n >= 1000)    { $k = intdiv($n,1000);    $parts[] = $k===1?'MIL':numToWordsTicket($k).' MIL'; $n%=1000; }
        if ($n >= 100)     { $h = intdiv($n,100); $parts[] = $n===100?'CIEN':$huns[$h]; $n%=100; }
        if ($n >= 20)      { $t=intdiv($n,10);$u=$n%10; $parts[]=  $u?$tens[$t].' Y '.$b20[$u]:$tens[$t]; }
        elseif ($n > 0)    { $parts[] = $b20[$n]; }
        return implode(' ', $parts);
    }
    }

    $companyName     = $settings->company_name     ?? '';
    $companyRuc      = $settings->company_ruc      ?? '';
    $companyPhone    = $settings->company_phone    ?? '';
    $companyAddress  = $settings->company_address  ?? '';
    $companyActivity = $settings->company_activity ?? '';

    $fecha     = \Carbon\Carbon::parse($invoice->invoice_date);
    $meses     = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                  'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    $mesNombre = $meses[$fecha->month - 1];
    $esContado = strtoupper($invoice->condition ?? '') === 'CONTADO';

    $valorParcial = ($invoice->subtotal_exento ?? 0)
                  + ($invoice->subtotal_iva_5  ?? 0)
                  + ($invoice->subtotal_iva_10 ?? 0);

    $totalLetras = numToWordsTicket((int) round($invoice->total_amount)) . ' GUARANÍES';

    $logoPath   = $settings->company_logo ? storage_path('app/public/' . $settings->company_logo) : null;
    $logoExists = $logoPath && file_exists($logoPath);
?>


<?php if($logoExists): ?>
<div class="center" style="margin-bottom:4px;">
    <img src="<?php echo e($logoPath); ?>" style="max-height:18mm; max-width:40mm;">
</div>
<?php endif; ?>


<div class="center">
    <div class="co-name"><?php echo e($companyName); ?></div>
    <?php if($companyActivity): ?><div class="co-sub"><?php echo e($companyActivity); ?></div><?php endif; ?>
    <?php if($companyPhone): ?><div class="co-sub">Tel: <?php echo e($companyPhone); ?></div><?php endif; ?>
    <?php if($companyAddress): ?><div class="co-sub"><?php echo e($companyAddress); ?></div><?php endif; ?>
</div>

<div class="dashed"></div>


<div class="center stamp-section">
    <?php if($invoice->fiscalStamp): ?>
        <div class="stamp-label">TIMBRADO Nº</div>
        <div class="stamp-num"><?php echo e($invoice->fiscalStamp->stamp_number); ?></div>
        <div class="co-sub">RUC: <?php echo e($companyRuc); ?></div>
        <div class="factura-title">FACTURA</div>
        <div class="co-sub" style="font-size:8px;">
            Vigencia: <?php echo e($invoice->fiscalStamp->valid_from->format('d/m/Y')); ?> al <?php echo e($invoice->fiscalStamp->valid_until->format('d/m/Y')); ?>

        </div>
    <?php else: ?>
        <div class="co-sub">RUC: <?php echo e($companyRuc); ?></div>
        <div class="factura-title">FACTURA</div>
    <?php endif; ?>
    <div class="inv-number"><?php echo e($invoice->invoice_number); ?></div>
</div>

<div class="dashed"></div>


<div class="client-section">
    <div class="client-row">
        <span class="field-lbl">Fecha:</span>
        <span class="bold"><?php echo e($fecha->day); ?> de <?php echo e($mesNombre); ?> de <?php echo e($fecha->year); ?></span>
    </div>
    <div class="client-row">
        <span class="field-lbl">Cond.:</span>
        <span class="bold"><?php echo e($esContado ? 'CONTADO' : 'CRÉDITO'); ?></span>
    </div>
    <div class="client-row">
        <span class="field-lbl">Cliente:</span>
        <span class="bold"><?php echo e($invoice->customer_name ?? 'Consumidor Final'); ?></span>
    </div>
    <?php if($invoice->customer_ruc): ?>
    <div class="client-row">
        <span class="field-lbl">RUC:</span>
        <span class="bold"><?php echo e($invoice->customer_ruc); ?></span>
    </div>
    <?php endif; ?>
    <?php if($invoice->customer_address): ?>
    <div class="client-row">
        <span class="field-lbl">Dir.:</span>
        <span><?php echo e($invoice->customer_address); ?></span>
    </div>
    <?php endif; ?>
</div>

<div class="dashed"></div>


<?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="item-row">
    <div class="item-name"><?php echo e($item->product_name); ?></div>
    <div class="item-line">
        <div class="item-left">
            <?php echo e(number_format($item->quantity, 0)); ?> x Gs. <?php echo e(number_format($item->unit_price, 0, ',', '.')); ?>

            <span class="iva-tag">
                <?php if($item->iva_type === 'IVA_10'): ?> (10%)
                <?php elseif($item->iva_type === 'IVA_5'): ?> (5%)
                <?php else: ?>
                <?php endif; ?>
            </span>
        </div>
        <div class="item-right">Gs. <?php echo e(number_format($item->total_price, 0, ',', '.')); ?></div>
    </div>
</div>
<div class="dotted"></div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div class="dashed"></div>


<div class="total-line">
    <div class="tl-label">Valor Parcial:</div>
    <div class="tl-value">Gs. <?php echo e(number_format($valorParcial, 0, ',', '.')); ?></div>
</div>
<div class="dashed"></div>
<div class="total-line total-final">
    <div class="tl-label">TOTAL:</div>
    <div class="tl-value">Gs. <?php echo e(number_format($invoice->total_amount, 0, ',', '.')); ?></div>
</div>
<div class="dashed"></div>


<div class="son-section">SON: <?php echo e($totalLetras); ?></div>

<div class="dashed"></div>


<div class="iva-section">
    <div>TOTAL IVA: <span class="bold">Gs. <?php echo e(number_format($invoice->total_iva ?? 0, 0, ',', '.')); ?></span></div>
    <div>
        Liq. IVA: (5%) Gs. <span class="bold"><?php echo e(number_format($invoice->total_iva_5 ?? 0, 0, ',', '.')); ?></span>
        &nbsp; (10%) Gs. <span class="bold"><?php echo e(number_format($invoice->total_iva_10 ?? 0, 0, ',', '.')); ?></span>
    </div>
</div>

<div class="dashed"></div>


<div class="footer-section">
    <div class="copies-line">Original: Comprador | Copia: Arch. Tributario</div>
    <?php if($settings->footer_text ?? null): ?>
        <div style="margin-top:3px;"><?php echo e($settings->footer_text); ?></div>
    <?php endif; ?>
    <div style="margin-top:4px; font-size:7.5px;"><?php echo e($fecha->format('d/m/Y H:i')); ?></div>
</div>

<?php if($preview ?? false): ?>
<script>window.onload = function(){ window.print(); }</script>
<?php endif; ?>

</body>
</html>
<?php /**PATH C:\laragon\www\bodega-app\resources\views/pdf/invoice-ticket.blade.php ENDPATH**/ ?>