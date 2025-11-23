<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #333;
        }
        .company-info {
            margin: 5px 0;
            color: #666;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-details {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        .customer-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        .totals table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals td {
            padding: 5px 10px;
            border-bottom: 1px solid #ddd;
        }
        .totals .total-row {
            font-weight: bold;
            font-size: 14px;
            background: #f5f5f5;
        }
        .footer {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .logo {
            max-height: 60px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        @if($settings->company_logo)
            <img src="{{ asset('storage/' . $settings->company_logo) }}" alt="Logo" class="logo">
        @endif
        <h1 class="company-name">{{ $settings->company_name }}</h1>
        @if($settings->company_ruc)
            <div class="company-info">RUC: {{ $settings->company_ruc }}</div>
        @endif
        @if($settings->company_address)
            <div class="company-info">{{ $settings->company_address }}</div>
        @endif
        @if($settings->company_phone)
            <div class="company-info">Tel: {{ $settings->company_phone }}</div>
        @endif
        @if($settings->company_email)
            <div class="company-info">Email: {{ $settings->company_email }}</div>
        @endif
    </div>

    <!-- Invoice Info -->
    <div style="display: table; width: 100%; margin-bottom: 20px;">
        <div style="display: table-cell; width: 50%;">
            <div class="invoice-details">
                <h3 style="margin-top: 0; color: #333;">FACTURA</h3>
                <p><strong>Número:</strong> {{ $sale->invoice_number }}</p>
                <p><strong>Fecha:</strong> {{ $sale->sale_date->format('d/m/Y') }}</p>
                <p><strong>Hora:</strong> {{ $sale->sale_date->format('H:i:s') }}</p>
                <p><strong>Vendedor:</strong> {{ $sale->user->name }}</p>
            </div>
        </div>
        <div style="display: table-cell; width: 50%; vertical-align: top; padding-left: 20px;">
            <div class="customer-info">
                <h4 style="margin-top: 0; color: #333;">Datos del Cliente</h4>
                <p><strong>Nombre:</strong> {{ $sale->customer_name ?: 'Cliente General' }}</p>
                @if($sale->customer_document)
                    <p><strong>RUC/CI:</strong> {{ $sale->customer_document }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Precio Unit.</th>
                <th class="text-center">IVA</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product_code }}</td>
                <td>{{ $item->product_name }}</td>
                <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
                <td class="text-right">Gs. {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->iva_type }}</td>
                <td class="text-right">Gs. {{ number_format($item->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">Gs. {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($sale->tax_amount > 0)
            <tr>
                <td>IVA:</td>
                <td class="text-right">Gs. {{ number_format($sale->tax_amount, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($sale->discount_amount > 0)
            <tr>
                <td>Descuento:</td>
                <td class="text-right">- Gs. {{ number_format($sale->discount_amount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>Gs. {{ number_format($sale->total_amount, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Payment Info -->
    <div style="clear: both; margin-top: 40px;">
        <h4>Información de Pago</h4>
        <p><strong>Método de Pago:</strong> {{ $sale->payment_method === 'CASH' ? 'Efectivo' : ($sale->payment_method === 'CARD' ? 'Tarjeta' : 'Transferencia') }}</p>
        @if($sale->payment_method === 'CASH' && $sale->change_amount > 0)
            <p><strong>Efectivo Recibido:</strong> Gs. {{ number_format($sale->amount_paid, 0, ',', '.') }}</p>
            <p><strong>Cambio:</strong> Gs. {{ number_format($sale->change_amount, 0, ',', '.') }}</p>
        @endif
    </div>

    <!-- Terms and Conditions -->
    @if($settings->terms_conditions)
    <div style="margin-top: 30px;">
        <h4>Términos y Condiciones</h4>
        <p style="font-size: 10px;">{{ $settings->terms_conditions }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        @if($settings->footer_text)
            <p>{{ $settings->footer_text }}</p>
        @endif
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>