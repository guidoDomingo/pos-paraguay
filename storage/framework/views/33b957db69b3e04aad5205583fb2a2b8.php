<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura <?php echo e($invoice->invoice_number); ?></title>
    <style>
        /* Reset y base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.2;
            color: #000;
        }

        .invoice-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 10mm;
            background: white;
        }

        /* Header de la empresa */
        .company-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .company-trade-name {
            font-size: 14px;
            margin-top: 2px;
        }

        .company-info {
            margin-top: 5px;
            font-size: 11px;
        }

        /* Datos fiscales */
        .fiscal-data {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 11px;
            font-weight: bold;
        }

        .fiscal-left, .fiscal-right {
            width: 48%;
        }

        /* Información de la factura */
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border: 1px solid #000;
            padding: 8px;
        }

        .invoice-number {
            font-size: 16px;
            font-weight: bold;
        }

        .invoice-details {
            text-align: right;
            font-size: 11px;
        }

        /* Datos del cliente */
        .customer-section {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 15px;
        }

        .customer-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .customer-data {
            display: flex;
            justify-content: space-between;
        }

        /* Tabla de productos */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .items-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .items-table .qty,
        .items-table .price {
            text-align: right;
            width: 15%;
        }

        .items-table .desc {
            width: 40%;
        }

        .items-table .code {
            width: 15%;
        }

        .items-table .total {
            text-align: right;
            width: 15%;
        }

        /* Totales */
        .totals-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .totals-left {
            width: 60%;
        }

        .totals-right {
            width: 35%;
            border: 1px solid #000;
            padding: 8px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-final {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        /* Liquidación de IVA */
        .iva-section {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 15px;
        }

        .iva-title {
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .iva-table {
            width: 100%;
            font-size: 11px;
        }

        .iva-table td {
            padding: 3px 8px;
        }

        /* Condiciones y observaciones */
        .conditions-section {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .condition-item {
            margin-bottom: 5px;
        }

        /* Footer */
        .invoice-footer {
            border-top: 1px solid #000;
            padding-top: 10px;
            font-size: 10px;
            text-align: center;
        }

        /* Utilidades para impresión */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .invoice-container {
                margin: 0;
                padding: 5mm;
            }
            
            .page-break {
                page-break-before: always;
            }
        }

        /* Bordes más gruesos para elementos importantes */
        .thick-border {
            border: 2px solid #000 !important;
        }

        /* Texto en negrita */
        .bold {
            font-weight: bold;
        }

        /* Alineación */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        /* Espaciado */
        .mb-5 {
            margin-bottom: 5px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header de la empresa -->
        <div class="company-header">
            <div class="company-name"><?php echo e($invoice->company->name); ?></div>
            <?php if($invoice->company->trade_name && $invoice->company->trade_name !== $invoice->company->name): ?>
            <div class="company-trade-name"><?php echo e($invoice->company->trade_name); ?></div>
            <?php endif; ?>
            <div class="company-info">
                <?php echo e($invoice->company->address); ?><br>
                <?php if($invoice->company->phone): ?>Tel: <?php echo e($invoice->company->phone); ?> <?php endif; ?>
                <?php if($invoice->company->email): ?>| Email: <?php echo e($invoice->company->email); ?><?php endif; ?>
            </div>
        </div>

        <!-- Datos fiscales obligatorios DNIT -->
        <div class="fiscal-data">
            <div class="fiscal-left">
                <div>RUC: <?php echo e($invoice->company->getFormattedRucAttribute()); ?></div>
                <div>CONTRIBUYENTE: <?php echo e($invoice->company->taxpayer_type); ?></div>
            </div>
            <div class="fiscal-right">
                <div>TIMBRADO: <?php echo e($invoice->stamp_number); ?></div>
                <div>VÁLIDO DESDE: <?php echo e($invoice->fiscalStamp->valid_from->format('d/m/Y')); ?> HASTA: <?php echo e($invoice->fiscalStamp->valid_until->format('d/m/Y')); ?></div>
            </div>
        </div>

        <!-- Información de la factura -->
        <div class="invoice-info thick-border">
            <div>
                <div class="invoice-number text-uppercase">FACTURA</div>
                <div class="bold">Nº <?php echo e($invoice->invoice_number); ?></div>
                <div>Establecimiento: <?php echo e($invoice->establishment); ?></div>
                <div>Punto de Expedición: <?php echo e($invoice->point_of_sale); ?></div>
            </div>
            <div class="invoice-details">
                <div class="bold">FECHA: <?php echo e($invoice->invoice_date->format('d/m/Y')); ?></div>
                <div>CONDICIÓN: <?php echo e($invoice->condition); ?></div>
            </div>
        </div>

        <!-- Datos del cliente -->
        <div class="customer-section">
            <div class="customer-title text-uppercase">Datos del Cliente</div>
            <div class="customer-data">
                <div>
                    <div><strong>Señor(es):</strong> <?php echo e(strtoupper($invoice->customer_name)); ?></div>
                    <?php if($invoice->customer_ruc): ?>
                    <div><strong>RUC:</strong> <?php echo e($invoice->customer_ruc); ?></div>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if($invoice->customer_address): ?>
                    <div><strong>Dirección:</strong> <?php echo e(strtoupper($invoice->customer_address)); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="code">CÓDIGO</th>
                    <th class="desc">DESCRIPCIÓN</th>
                    <th class="qty">CANTIDAD</th>
                    <th class="price">PRECIO UNIT.</th>
                    <th class="total">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="code"><?php echo e($item->product_code); ?></td>
                    <td class="desc"><?php echo e(strtoupper($item->product_name)); ?></td>
                    <td class="qty"><?php echo e(number_format($item->quantity, 0, ',', '.')); ?></td>
                    <td class="price"><?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></td>
                    <td class="total"><?php echo e(number_format($item->total_price, 0, ',', '.')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <!-- Rellenar filas vacías si es necesario para mantener formato -->
                <?php for($i = count($invoice->items); $i < 15; $i++): ?>
                <tr>
                    <td class="code">&nbsp;</td>
                    <td class="desc">&nbsp;</td>
                    <td class="qty">&nbsp;</td>
                    <td class="price">&nbsp;</td>
                    <td class="total">&nbsp;</td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <!-- Sección de totales y liquidación de IVA -->
        <div class="totals-section">
            <div class="totals-left">
                <!-- Liquidación del IVA -->
                <div class="iva-section">
                    <div class="iva-title">LIQUIDACIÓN DEL I.V.A.</div>
                    <table class="iva-table">
                        <tr>
                            <td class="bold">GRAVADA 10%:</td>
                            <td class="text-right"><?php echo e(number_format($invoice->subtotal_iva_10, 0, ',', '.')); ?></td>
                            <td class="bold">I.V.A. 10%:</td>
                            <td class="text-right"><?php echo e(number_format($invoice->total_iva_10, 0, ',', '.')); ?></td>
                        </tr>
                        <tr>
                            <td class="bold">GRAVADA 5%:</td>
                            <td class="text-right"><?php echo e(number_format($invoice->subtotal_iva_5, 0, ',', '.')); ?></td>
                            <td class="bold">I.V.A. 5%:</td>
                            <td class="text-right"><?php echo e(number_format($invoice->total_iva_5, 0, ',', '.')); ?></td>
                        </tr>
                        <tr>
                            <td class="bold">EXENTAS:</td>
                            <td class="text-right"><?php echo e(number_format($invoice->subtotal_exento, 0, ',', '.')); ?></td>
                            <td class="bold">TOTAL I.V.A.:</td>
                            <td class="text-right"><?php echo e(number_format($invoice->total_iva, 0, ',', '.')); ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Condiciones de venta -->
                <div class="conditions-section">
                    <div class="condition-item"><strong>CONDICIÓN DE VENTA:</strong> <?php echo e($invoice->condition); ?></div>
                    <?php if($invoice->observations): ?>
                    <div class="condition-item"><strong>OBSERVACIONES:</strong> <?php echo e(strtoupper($invoice->observations)); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="totals-right">
                <div class="total-row">
                    <span>SUB-TOTAL:</span>
                    <span><?php echo e(number_format($invoice->subtotal_exento + $invoice->subtotal_iva_5 + $invoice->subtotal_iva_10, 0, ',', '.')); ?></span>
                </div>
                <div class="total-row">
                    <span>DESCUENTOS:</span>
                    <span>0</span>
                </div>
                <div class="total-row">
                    <span>TOTAL I.V.A.:</span>
                    <span><?php echo e(number_format($invoice->total_iva, 0, ',', '.')); ?></span>
                </div>
                <div class="total-row total-final">
                    <span>TOTAL A PAGAR:</span>
                    <span><?php echo e(number_format($invoice->total_amount, 0, ',', '.')); ?></span>
                </div>
            </div>
        </div>

        <!-- Total en letras -->
        <div class="conditions-section">
            <div class="condition-item">
                <strong>SON GUARANÍES:</strong> 
                <?php echo e(App\Services\NumberToWordsService::convert($invoice->total_amount)); ?>

            </div>
        </div>

        <!-- Firmas -->
        <div style="display: flex; justify-content: space-between; margin-top: 40px; margin-bottom: 20px;">
            <div style="text-align: center; width: 45%; border-top: 1px solid #000; padding-top: 5px;">
                <div class="bold">RECIBÍ CONFORME</div>
                <div style="font-size: 10px;">FIRMA Y ACLARACIÓN</div>
            </div>
            <div style="text-align: center; width: 45%; border-top: 1px solid #000; padding-top: 5px;">
                <div class="bold">ENTREGUÉ CONFORME</div>
                <div style="font-size: 10px;">FIRMA Y ACLARACIÓN</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div>ORIGINAL: CLIENTE | DUPLICADO: VENDEDOR</div>
            <?php if($invoice->company->config && $invoice->company->config->invoice_footer_text): ?>
            <div style="margin-top: 5px;"><?php echo e(strtoupper($invoice->company->config->invoice_footer_text)); ?></div>
            <?php endif; ?>
            <div style="margin-top: 10px; font-size: 9px;">
                Facturado por: <?php echo e(strtoupper($invoice->sale->user->name)); ?> | 
                Fecha de emisión: <?php echo e(now()->format('d/m/Y H:i:s')); ?>

            </div>
        </div>
    </div>

    <script>
        // Auto-print cuando se carga la página
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html><?php /**PATH C:\laragon\www\bodega-app\resources\views/invoices/dnit-format.blade.php ENDPATH**/ ?>