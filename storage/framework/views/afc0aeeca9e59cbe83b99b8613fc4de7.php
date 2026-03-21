<?php
/* ── Conversión de número a letras (Guaraníes) ── */
if (!function_exists('numToWords')) {
function numToWords(int $n): string {
    if ($n === 0) return 'CERO';
    $b20 = ['','UNO','DOS','TRES','CUATRO','CINCO','SEIS','SIETE','OCHO','NUEVE',
            'DIEZ','ONCE','DOCE','TRECE','CATORCE','QUINCE','DIECISÉIS','DIECISIETE','DIECIOCHO','DIECINUEVE'];
    $tens = ['','','VEINTE','TREINTA','CUARENTA','CINCUENTA','SESENTA','SETENTA','OCHENTA','NOVENTA'];
    $huns = ['','CIENTO','DOSCIENTOS','TRESCIENTOS','CUATROCIENTOS','QUINIENTOS',
             'SEISCIENTOS','SETECIENTOS','OCHOCIENTOS','NOVECIENTOS'];
    $parts = [];
    if ($n >= 1000000) {
        $m = intdiv($n, 1000000);
        $parts[] = $m === 1 ? 'UN MILLÓN' : numToWords($m).' MILLONES';
        $n %= 1000000;
    }
    if ($n >= 1000) {
        $k = intdiv($n, 1000);
        $parts[] = $k === 1 ? 'MIL' : numToWords($k).' MIL';
        $n %= 1000;
    }
    if ($n >= 100) {
        $h = intdiv($n, 100);
        $parts[] = $n === 100 ? 'CIEN' : $huns[$h];
        $n %= 100;
    }
    if ($n >= 20) {
        $t = intdiv($n, 10); $u = $n % 10;
        $parts[] = $u ? $tens[$t].' Y '.$b20[$u] : $tens[$t];
    } elseif ($n > 0) {
        $parts[] = $b20[$n];
    }
    return implode(' ', $parts);
}
} // end if !function_exists

$totalLetras = numToWords((int) round($invoice->total_amount)) . ' GUARANÍES';

/* ── Logo ── */
$logoPath   = $settings->company_logo
    ? storage_path('app/public/' . $settings->company_logo)
    : null;
$logoExists = $logoPath && file_exists($logoPath);
?>


<table class="header-table">
    <tr>
        <td class="header-left">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <?php if($logoExists): ?>
                    <td style="width:24mm; vertical-align:middle; padding-right:3px;">
                        <img src="<?php echo e($logoPath); ?>"
                             style="max-height:15mm; max-width:22mm; display:block;">
                    </td>
                    <?php endif; ?>
                    <td style="vertical-align:top;">
                        <div class="company-name"><?php echo e($companyName); ?></div>
                        <?php if($companyActivity): ?>
                            <div class="company-sub"><?php echo e($companyActivity); ?></div>
                        <?php endif; ?>
                        <?php if($companyPhone): ?>
                            <div class="company-sub">Cel.: <?php echo e($companyPhone); ?></div>
                        <?php endif; ?>
                        <?php if($companyAddress): ?>
                            <div class="company-sub"><?php echo e($companyAddress); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </td>
        <td class="header-right">
            <?php if($invoice->fiscalStamp): ?>
                <div class="stamp-label">TIMBRADO Nº</div>
                <div class="stamp-number"><?php echo e($invoice->fiscalStamp->stamp_number); ?></div>
                <div class="ruc-line">RUC: <?php echo e($companyRuc); ?></div>
                <div class="factura-title">FACTURA</div>
                <div class="vigencia-line">Fecha Inicio Vigencia: <?php echo e($invoice->fiscalStamp->valid_from->format('d/m/Y')); ?></div>
                <div class="vigencia-line">Válido Hasta: <?php echo e($invoice->fiscalStamp->valid_until->format('d/m/Y')); ?></div>
            <?php else: ?>
                <div class="ruc-line">RUC: <?php echo e($companyRuc); ?></div>
                <div class="factura-title">FACTURA</div>
            <?php endif; ?>
            <div class="invoice-number-box"><?php echo e($invoice->invoice_number); ?></div>
        </td>
    </tr>
</table>


<table class="client-table">
    <tr>
        <td style="width:55%;">
            Fecha de Emisión: <strong><?php echo e($fecha->day); ?></strong> de <strong><?php echo e($mesNombre); ?></strong> de <strong><?php echo e($fecha->year); ?></strong>
        </td>
        <td style="width:45%;">
            Cond. de Venta:
            <span class="check-box"><?php echo e($esContado ? 'X' : ''); ?></span> Contado &nbsp;
            <span class="check-box"><?php echo e($esCredito ? 'X' : ''); ?></span> Crédito
        </td>
    </tr>
    <tr>
        <td>Nombre o Razón Social: <strong><?php echo e($invoice->customer_name ?? ''); ?></strong></td>
        <td>Nota de Remisión Nº:</td>
    </tr>
    <tr>
        <td>R.U.C.: <strong><?php echo e($invoice->customer_ruc ?? ''); ?></strong></td>
        <td>Teléfono:</td>
    </tr>
    <tr>
        <td colspan="2">Dirección: <strong><?php echo e($invoice->customer_address ?? ''); ?></strong></td>
    </tr>
</table>


<table class="items-table">
    <thead>
        <tr>
            <th class="col-cant" rowspan="2">Cant.</th>
            <th class="col-desc" rowspan="2">Descripción</th>
            <th class="col-pu"   rowspan="2">P/U</th>
            <th colspan="3">VALOR DE VENTA</th>
        </tr>
        <tr>
            <th class="col-exent">EXENTAS</th>
            <th class="col-5">5 %</th>
            <th class="col-10">10 %</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="col-cant"><?php echo e(number_format($item->quantity, 0)); ?></td>
            <td class="col-desc"><?php echo e($item->product_name); ?></td>
            <td class="col-pu"><?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></td>
            <td class="col-exent"><?php echo e($item->iva_type === 'EXENTO' ? number_format($item->total_price, 0, ',', '.') : ''); ?></td>
            <td class="col-5"><?php echo e($item->iva_type === 'IVA_5'   ? number_format($item->total_price, 0, ',', '.') : ''); ?></td>
            <td class="col-10"><?php echo e($item->iva_type === 'IVA_10'  ? number_format($item->total_price, 0, ',', '.') : ''); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php for($i = 0; $i < $emptyRows; $i++): ?>
        <tr>
            <td class="col-cant">&nbsp;</td>
            <td class="col-desc">&nbsp;</td>
            <td class="col-pu">&nbsp;</td>
            <td class="col-exent">&nbsp;</td>
            <td class="col-5">&nbsp;</td>
            <td class="col-10">&nbsp;</td>
        </tr>
        <?php endfor; ?>
    </tbody>
</table>


<table class="totals-table">
    <tr>
        <td class="label-cell">VALOR PARCIAL</td>
        <td class="amount-cell"><?php echo e(number_format($valorParcial, 0, ',', '.')); ?></td>
    </tr>
    <tr>
        <td class="label-cell" style="font-size:10px;">TOTAL A PAGAR Gs.</td>
        <td class="amount-cell" style="font-size:10px; font-weight:bold;"><?php echo e(number_format($invoice->total_amount, 0, ',', '.')); ?></td>
    </tr>
    <tr>
        <td colspan="2" style="padding:2px 5px; font-size:7.5px; border:1px solid #000;">
            SON: <strong><?php echo e($totalLetras); ?></strong>
        </td>
    </tr>
</table>


<table class="iva-footer">
    <tr>
        <td style="width:50%;">
            TOTAL IVA: &nbsp; <strong><?php echo e(number_format($invoice->total_iva ?? 0, 0, ',', '.')); ?></strong>
        </td>
        <td style="width:50%; text-align:right; font-weight:bold;">
            <?php echo e($copyLabel); ?>

        </td>
    </tr>
    <tr>
        <td colspan="2">
            Liquidación del IVA:&nbsp;&nbsp;
            (5 %) <strong><?php echo e(number_format($invoice->total_iva_5 ?? 0, 0, ',', '.')); ?></strong>
            &nbsp;&nbsp;&nbsp;
            (10 %) <strong><?php echo e(number_format($invoice->total_iva_10 ?? 0, 0, ',', '.')); ?></strong>
        </td>
    </tr>
</table>

<?php if($settings->footer_text ?? null): ?>
<div class="footer-text"><?php echo e($settings->footer_text); ?></div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\bodega-app\resources\views/pdf/partials/invoice-content.blade.php ENDPATH**/ ?>