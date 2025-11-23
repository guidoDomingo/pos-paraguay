<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket {{ $sale->sale_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.3;
            margin: 0;
            padding: 10px;
            max-width: 300px;
        }

        .ticket-container {
            text-align: center;
        }

        .company-header {
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .company-info {
            font-size: 10px;
            margin-top: 5px;
        }

        .ticket-info {
            text-align: left;
            margin-bottom: 10px;
            font-size: 11px;
        }

        .items-section {
            text-align: left;
            margin-bottom: 10px;
        }

        .item {
            margin-bottom: 5px;
            font-size: 11px;
        }

        .item-line {
            display: flex;
            justify-content: space-between;
        }

        .item-details {
            font-size: 10px;
            color: #666;
            margin-left: 10px;
        }

        .totals-section {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-bottom: 10px;
        }

        .total-line {
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

        .payment-info {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .ticket-footer {
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        @media print {
            body {
                margin: 0;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header de la empresa -->
        <div class="company-header">
            <div class="company-name">{{ $sale->company->name }}</div>
            @if($sale->company->trade_name && $sale->company->trade_name !== $sale->company->name)
            <div style="font-size: 12px;">{{ $sale->company->trade_name }}</div>
            @endif
            <div class="company-info">
                {{ $sale->company->address }}<br>
                RUC: {{ $sale->company->getFormattedRucAttribute() }}<br>
                @if($sale->company->phone)Tel: {{ $sale->company->phone }}@endif
            </div>
        </div>

        <!-- Información del ticket -->
        <div class="ticket-info">
            <div class="bold text-center" style="font-size: 14px; margin-bottom: 10px;">TICKET DE VENTA</div>
            <div><strong>Nº:</strong> {{ $sale->sale_number }}</div>
            <div><strong>Fecha:</strong> {{ $sale->sale_date->format('d/m/Y H:i:s') }}</div>
            <div><strong>Cajero:</strong> {{ $sale->user->name }}</div>
            @if($sale->customer)
            <div><strong>Cliente:</strong> {{ $sale->customer->name }}</div>
            @endif
        </div>

        <!-- Productos -->
        <div class="items-section">
            @foreach($sale->items as $item)
            <div class="item">
                <div class="item-line">
                    <span>{{ $item->product_name }}</span>
                    <span>₲ {{ number_format($item->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="item-details">
                    {{ number_format($item->quantity, 0) }} x ₲ {{ number_format($item->unit_price, 0, ',', '.') }}
                    @if($item->iva_type !== 'EXENTO')
                    ({{ $item->iva_type }})
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Totales -->
        <div class="totals-section">
            <div class="total-line">
                <span>Subtotal:</span>
                <span>₲ {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($sale->tax_amount > 0)
            <div class="total-line">
                <span>IVA:</span>
                <span>₲ {{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($sale->discount_amount > 0)
            <div class="total-line">
                <span>Descuento:</span>
                <span>-₲ {{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-line total-final">
                <span>TOTAL:</span>
                <span>₲ {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Información de pago -->
        <div class="payment-info">
            <div><strong>Pago:</strong> 
                @switch($sale->payment_method)
                    @case('CASH')
                        Efectivo
                        @break
                    @case('CARD')
                        Tarjeta
                        @break
                    @case('TRANSFER')
                        Transferencia
                        @break
                    @case('CREDIT')
                        Crédito
                        @break
                    @default
                        {{ $sale->payment_method }}
                @endswitch
            </div>
            
            @if($sale->payment_method === 'CASH' && isset($cashReceived))
            <div><strong>Recibido:</strong> ₲ {{ number_format($cashReceived, 0, ',', '.') }}</div>
            <div><strong>Vuelto:</strong> ₲ {{ number_format($cashReceived - $sale->total_amount, 0, ',', '.') }}</div>
            @endif
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            <div style="margin-bottom: 10px;">
                ¡Gracias por su compra!
            </div>
            
            @if($sale->company->config && $sale->company->config->ticket_footer_text)
            <div style="margin-bottom: 10px;">
                {{ $sale->company->config->ticket_footer_text }}
            </div>
            @endif
            
            <div style="font-size: 9px;">
                Este documento no es válido<br>
                como comprobante fiscal
            </div>
            
            <div style="margin-top: 10px; font-size: 9px;">
                Caja: {{ $sale->cashRegister?->id ?? 'N/A' }}<br>
                {{ now()->format('d/m/Y H:i:s') }}
            </div>
            
            @if($sale->canBeInvoiced())
            <div style="margin-top: 10px; font-size: 9px; font-weight: bold;">
                * Solicite su factura legal *
            </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-print cuando se carga la página
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>