<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #<?php echo e($invoice->invoice_number); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 10px;
            font-size: 11px;
            line-height: 1.3;
        }
        .invoice-container {
            border: 2px solid #000;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .company-info {
            font-size: 10px;
            margin: 2px 0;
        }
        .invoice-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0;
            padding: 5px;
            background: #f0f0f0;
        }
        .fiscal-info {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 10px;
            background: #f9f9f9;
        }
        .fiscal-info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 10px;
        }
        .fiscal-info-row strong {
            min-width: 120px;
        }
        .section-title {
            font-weight: bold;
            font-size: 11px;
            margin: 10px 0 5px 0;
            padding: 3px 5px;
            background: #e0e0e0;
            border-left: 3px solid #000;
        }
        .info-row {
            margin: 3px 0;
            font-size: 10px;
        }
        .info-row strong {
            display: inline-block;
            min-width: 100px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
        }
        .items-table th {
            background-color: #e0e0e0;
            padding: 5px 3px;
            text-align: left;
            border: 1px solid #000;
            font-weight: bold;
        }
        .items-table td {
            padding: 4px 3px;
            border: 1px solid #000;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-section {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #000;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 10px;
        }
        .totals-row.total-final {
            font-weight: bold;
            font-size: 12px;
            background: #e0e0e0;
            padding: 5px;
            margin-top: 5px;
        }
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 8px;
        }
        .stamp-box {
            border: 2px solid #000;
            padding: 8px;
            text-align: center;
            margin: 10px 0;
            background: #fff;
        }
        .stamp-number {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .stamp-dates {
            font-size: 9px;
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header with Company Info -->
        <div class="header">
            <div class="company-name"><?php echo e($sale->company->name ?? $settings->company_name); ?></div>
            <div class="company-info">
                <strong>RUC:</strong> <?php echo e($sale->company->ruc ?? $settings->company_ruc); ?>

            </div>
            <?php if($sale->company->address ?? $settings->company_address): ?>
                <div class="company-info"><?php echo e($sale->company->address ?? $settings->company_address); ?></div>
            <?php endif; ?>
            <?php if($sale->company->phone ?? $settings->company_phone): ?>
                <div class="company-info">Tel: <?php echo e($sale->company->phone ?? $settings->company_phone); ?></div>
            <?php endif; ?>
            <?php if($sale->company->economic_activity ?? $settings->company_activity): ?>
                <div class="company-info"><?php echo e($sale->company->economic_activity ?? $settings->company_activity); ?></div>
            <?php endif; ?>
        </div>

        <!-- Invoice Title -->
        <div class="invoice-title">FACTURA</div>

        <!-- Fiscal Stamp Information -->
        <?php if($invoice->fiscalStamp): ?>
        <div class="stamp-box">
            <div style="font-weight: bold; font-size: 10px;">TIMBRADO</div>
            <div class="stamp-number"><?php echo e($invoice->fiscalStamp->stamp_number); ?></div>
            <div class="stamp-dates">
                Validez: <?php echo e($invoice->fiscalStamp->valid_from->format('d/m/Y')); ?> al <?php echo e($invoice->fiscalStamp->valid_until->format('d/m/Y')); ?>

            </div>
        </div>
        <?php endif; ?>

        <!-- Invoice Number -->
        <div class="fiscal-info">
            <div class="fiscal-info-row">
                <strong>Nº Factura:</strong>
                <span style="font-weight: bold; font-size: 12px;"><?php echo e($invoice->invoice_number); ?></span>
            </div>
            <div class="fiscal-info-row">
                <strong>Fecha:</strong>
                <span><?php echo e($invoice->invoice_date->format('d/m/Y H:i')); ?></span>
            </div>
            <div class="fiscal-info-row">
                <strong>Condición de Venta:</strong>
                <span><?php echo e(strtoupper($invoice->condition)); ?></span>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section-title">DATOS DEL CLIENTE</div>
        <div style="padding: 5px 0;">
            <div class="info-row">
                <strong>Nombre:</strong> <?php echo e($invoice->customer_name ?? 'CONTRIBUYENTE GENERAL'); ?>

            </div>
            <?php if($invoice->customer_ruc): ?>
            <div class="info-row">
                <strong>RUC/CI:</strong> <?php echo e($invoice->customer_ruc); ?>

            </div>
            <?php endif; ?>
            <?php if($invoice->customer_address): ?>
            <div class="info-row">
                <strong>Dirección:</strong> <?php echo e($invoice->customer_address); ?>

            </div>
            <?php endif; ?>
        </div>

        <!-- Items Detail -->
        <div class="section-title">DETALLE DE LA OPERACIÓN</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Cant.</th>
                    <th style="width: 50%;">Descripción</th>
                    <th style="width: 15%;" class="text-right">P. Unit.</th>
                    <th style="width: 10%;" class="text-center">IVA</th>
                    <th style="width: 15%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-center"><?php echo e(number_format($item->quantity, 0)); ?></td>
                    <td><?php echo e($item->product_name); ?></td>
                    <td class="text-right"><?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></td>
                    <td class="text-center">
                        <?php if($item->iva_type === 'IVA_10'): ?>
                            10%
                        <?php elseif($item->iva_type === 'IVA_5'): ?>
                            5%
                        <?php else: ?>
                            EXENTO
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?php echo e(number_format($item->total_price, 0, ',', '.')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Totals Section - Formato Paraguayo -->
        <div class="totals-section">
            <?php if($invoice->subtotal_exento > 0): ?>
            <div class="totals-row">
                <span>TOTAL EXENTA</span>
                <span><?php echo e(number_format($invoice->subtotal_exento, 0, ',', '.')); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if($invoice->subtotal_iva_5 > 0 || $invoice->total_iva_5 > 0): ?>
            <div class="totals-row">
                <span>TOTAL GRAV. 5%</span>
                <span><?php echo e(number_format($invoice->subtotal_iva_5 + $invoice->total_iva_5, 0, ',', '.')); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if($invoice->subtotal_iva_10 > 0 || $invoice->total_iva_10 > 0): ?>
            <div class="totals-row">
                <span>TOTAL GRAV. 10%</span>
                <span><?php echo e(number_format($invoice->subtotal_iva_10 + $invoice->total_iva_10, 0, ',', '.')); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if($invoice->total_iva_5 > 0): ?>
            <div class="totals-row">
                <span>LIQ. I.V.A. 5%</span>
                <span><?php echo e(number_format($invoice->total_iva_5, 0, ',', '.')); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if($invoice->total_iva_10 > 0): ?>
            <div class="totals-row">
                <span>LIQ. I.V.A. 10%</span>
                <span><?php echo e(number_format($invoice->total_iva_10, 0, ',', '.')); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if($invoice->total_iva > 0): ?>
            <div class="totals-row">
                <span><strong>TOTAL LIQ. I.V.A.</strong></span>
                <span><strong><?php echo e(number_format($invoice->total_iva, 0, ',', '.')); ?></strong></span>
            </div>
            <?php endif; ?>
            
            <div style="border-top: 2px dashed #000; margin: 8px 0;"></div>
            
            <?php if($sale->payment_method === 'CASH' && $sale->amount_paid > 0): ?>
            <div class="totals-row">
                <span>RECIBIDO</span>
                <span><?php echo e(number_format($sale->amount_paid, 0, ',', '.')); ?></span>
            </div>
            <div class="totals-row">
                <span>VUELTO</span>
                <span><?php echo e(number_format($sale->change_amount, 0, ',', '.')); ?></span>
            </div>
            <div style="border-top: 2px dashed #000; margin: 8px 0;"></div>
            <?php endif; ?>
            
            <div class="totals-row total-final">
                <span>TOTAL</span>
                <span><?php echo e(number_format($invoice->total_amount, 0, ',', '.')); ?></span>
            </div>
        </div>

        <!-- Payment Method -->
        <?php if($sale->payment_method): ?>
        <div style="margin-top: 10px; font-size: 10px; text-align: center;">
            <strong>Forma de Pago:</strong> 
            <?php echo e($sale->payment_method === 'CASH' ? 'Efectivo' : ($sale->payment_method === 'CARD' ? 'Tarjeta' : 'Transferencia')); ?>

        </div>
        <?php endif; ?>

        <!-- Observations -->
        <?php if($invoice->observations): ?>
        <div style="margin-top: 10px; font-size: 9px;">
            <strong>Observaciones:</strong> <?php echo e($invoice->observations); ?>

        </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="footer">
            <div>Original: Cliente | Duplicado: Emisor</div>
            <?php if($settings->footer_text): ?>
                <div style="margin-top: 5px;"><?php echo e($settings->footer_text); ?></div>
            <?php endif; ?>
            <div style="margin-top: 5px;">Impreso: <?php echo e(now()->format('d/m/Y H:i:s')); ?></div>
            <?php if($sale->user): ?>
                <div>Cajero: <?php echo e($sale->user->name); ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\bodega-app\resources\views/pdf/invoice.blade.php ENDPATH**/ ?>