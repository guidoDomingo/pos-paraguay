<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $sale->ticket_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 15px;
            font-size: 12px;
            line-height: 1.4;
            width: 300px;
            max-width: 300px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            line-height: 1.4;
        }
        .company-info {
            font-size: 10px;
            margin: 3px 0;
            color: #444;
        }
        .ticket-info {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #333;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 3px;
        }
        .ticket-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .ticket-details {
            font-size: 10px;
            line-height: 1.3;
        }
        .items {
            margin-bottom: 15px;
        }
        .item {
            margin-bottom: 8px;
            padding: 5px;
            border-bottom: 1px dotted #ccc;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-name {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 2px;
            word-wrap: break-word;
        }
        .item-details {
            display: table;
            width: 100%;
            font-size: 10px;
        }
        .item-qty-price {
            display: table-cell;
            text-align: left;
            width: 60%;
        }
        .item-total {
            display: table-cell;
            text-align: right;
            width: 40%;
            font-weight: bold;
        }
        .iva-info {
            font-size: 8px; 
            color: #666;
            font-style: italic;
            margin-top: 2px;
        }
        .totals {
            border-top: 2px dashed #333;
            padding-top: 10px;
            margin-top: 10px;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 3px;
        }
        .total-line {
            display: table;
            width: 100%;
            margin: 3px 0;
        }
        .total-label {
            display: table-cell;
            text-align: left;
            font-size: 11px;
        }
        .total-amount {
            display: table-cell;
            text-align: right;
            font-size: 11px;
            font-weight: bold;
        }
        .total-final {
            border-top: 2px solid #333;
            padding-top: 5px;
            margin-top: 8px;
            font-size: 13px;
            font-weight: bold;
        }
        .total-final .total-label,
        .total-final .total-amount {
            font-size: 13px;
        }
        .payment-info {
            margin: 15px 0;
            padding: 10px;
            border: 1px dashed #333;
            border-radius: 3px;
            background: #fff;
        }
        .payment-title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
            text-decoration: underline;
        }
        .payment-line {
            display: table;
            width: 100%;
            margin: 3px 0;
            font-size: 11px;
        }
        .payment-label {
            display: table-cell;
            text-align: left;
            width: 50%;
        }
        .payment-value {
            display: table-cell;
            text-align: right;
            width: 50%;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            margin-top: 20px;
            border-top: 1px dashed #333;
            padding-top: 10px;
        }
        .footer-text {
            margin: 5px 0;
            font-style: italic;
        }
        .center {
            text-align: center;
        }
        .dashed-line {
            border-bottom: 1px dashed #333;
            margin: 8px 0;
        }
        .thank-you {
            font-weight: bold;
            font-size: 11px;
            margin: 8px 0;
        }
        .timestamp {
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ $settings->company_name }}</div>
        @if($settings->company_ruc)
            <div class="company-info">RUC: {{ $settings->company_ruc }}</div>
        @endif
        @if($settings->company_address)
            <div class="company-info">{{ $settings->company_address }}</div>
        @endif
        @if($settings->company_phone)
            <div class="company-info">Tel: {{ $settings->company_phone }}</div>
        @endif
    </div>

    <!-- Ticket Info -->
    <div class="ticket-info">
        <div class="ticket-title">TICKET DE VENTA</div>
        <div class="ticket-details">
            <div><strong>Nro:</strong> {{ $sale->sale_number }}</div>
            <div><strong>Fecha:</strong> {{ $sale->sale_date->format('d/m/Y H:i:s') }}</div>
            <div><strong>Vendedor:</strong> {{ $sale->user->name }}</div>
            @if($sale->customer_name && $sale->customer_name !== 'Cliente General')
                <div><strong>Cliente:</strong> {{ $sale->customer_name }}</div>
            @endif
        </div>
    </div>

    <!-- Items -->
    <div class="items">
        @foreach($sale->items as $item)
            <div class="item">
                <div class="item-name">{{ $item->product_name }}</div>
                <div class="item-details">
                    <div class="item-qty-price">
                        {{ number_format($item->quantity, 0) }} x Gs. {{ number_format($item->unit_price, 0, ',', '.') }}
                    </div>
                    <div class="item-total">
                        Gs. {{ number_format($item->total_price, 0, ',', '.') }}
                    </div>
                </div>
                @if($item->iva_type !== 'EXENTO')
                    <div class="iva-info">{{ $item->iva_type }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Totals -->
    <div class="totals">
        <div class="total-line">
            <div class="total-label">Subtotal:</div>
            <div class="total-amount">Gs. {{ number_format($sale->subtotal, 0, ',', '.') }}</div>
        </div>
        @if($sale->tax_amount > 0)
        <div class="total-line">
            <div class="total-label">IVA (10%):</div>
            <div class="total-amount">Gs. {{ number_format($sale->tax_amount, 0, ',', '.') }}</div>
        </div>
        @endif
        @if($sale->discount_amount > 0)
        <div class="total-line">
            <div class="total-label">Descuento:</div>
            <div class="total-amount">-Gs. {{ number_format($sale->discount_amount, 0, ',', '.') }}</div>
        </div>
        @endif
        <div class="total-line total-final">
            <div class="total-label">TOTAL:</div>
            <div class="total-amount">Gs. {{ number_format($sale->total_amount, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="payment-info">
        <div class="payment-title">INFORMACIÓN DE PAGO</div>
        <div class="payment-line">
            <div class="payment-label">Método:</div>
            <div class="payment-value">
                {{ $sale->payment_method === 'CASH' ? 'Efectivo' : ($sale->payment_method === 'CARD' ? 'Tarjeta' : 'Transferencia') }}
            </div>
        </div>
        @if($sale->payment_method === 'CASH')
            <div class="payment-line">
                <div class="payment-label">Recibido:</div>
                <div class="payment-value">Gs. {{ number_format($sale->amount_paid, 0, ',', '.') }}</div>
            </div>
            @if($sale->change_amount > 0)
            <div class="payment-line">
                <div class="payment-label">Cambio:</div>
                <div class="payment-value">Gs. {{ number_format($sale->change_amount, 0, ',', '.') }}</div>
            </div>
            @endif
        @endif
    </div>

    @if($sale->notes)
    <div class="payment-info">
        <div class="payment-title">OBSERVACIONES</div>
        <div style="font-size: 10px; text-align: left;">{{ $sale->notes }}</div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="dashed-line"></div>
        @if($settings->footer_text)
            <div class="footer-text">{{ $settings->footer_text }}</div>
        @endif
        @if($settings->company_website)
            <div class="footer-text">{{ $settings->company_website }}</div>
        @endif
        <div class="thank-you">¡Gracias por su compra!</div>
        <div class="footer-text">Visite nuestro sitio web</div>
        <div class="timestamp">{{ now()->format('d/m/Y H:i:s') }}</div>
        <div class="dashed-line"></div>
    </div>
</body>
</html>