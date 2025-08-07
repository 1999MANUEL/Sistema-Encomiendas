<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Factura Encomienda</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
            /* Ancho fijo 80mm, alto automático */
        }

        body {
            font-family: 'Courier New', 'Arial Black', monospace;
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin: 0;
            padding: 4mm;
            width: 72mm;
            /* Ancho exacto de impresión */
            min-height: 100vh;
            line-height: 1.3;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Configuración específica para impresora térmica */
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                width: 72mm;
                padding: 2mm;
                font-size: 11px;
                font-weight: bold;
            }

            .receipt {
                width: 100%;
                max-width: 72mm;
            }
        }

        .receipt {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: 900;
            margin: 2px 0;
            text-shadow: 1px 1px 0px #333;
            letter-spacing: 1px;
        }

        .header p {
            margin: 3px 0;
            font-size: 12px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: 900;
            font-size: 13px;
            border-bottom: 2px solid #000;
            margin-bottom: 4px;
            padding: 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 11px;
            font-weight: bold;
        }

        .description {
            margin: 6px 0;
            font-size: 11px;
            font-weight: bold;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .package-item {
            border-bottom: 2px dotted #000;
            padding: 4px 0;
            margin-bottom: 4px;
        }

        .package-header {
            font-weight: 900;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .package-detail {
            font-size: 10px;
            font-weight: bold;
            margin: 2px 0;
            padding-left: 5px;
        }

        .totals {
            border-top: 3px solid #000;
            border-bottom: 2px solid #000;
            padding: 6px 0;
            margin-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            font-size: 11px;
            font-weight: bold;
        }

        .total-final {
            font-weight: 900;
            font-size: 14px;
            border-top: 2px solid #000;
            padding-top: 4px;
            margin-top: 6px;
            text-transform: uppercase;
            padding: 4px;
        }

        .estado-pago {
            font-weight: 900;
            font-size: 12px;
            margin: 4px 0;
            padding: 3px;
            border: 2px solid #000;
            text-align: center;
        }

        .pagado {
        }

        .con-saldo {
        }

        .saldo-info {
            font-size: 10px;
            font-weight: bold;
            margin: 2px 0;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin-top: 12px;
            border-top: 2px dashed #000;
            padding-top: 6px;
        }

        .separator {
            text-align: center;
            margin: 6px 0;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .value {
            font-weight: 900;
        }

        /* Estilos adicionales para mayor contraste */
        strong {
            font-weight: 900;
            text-shadow: 0.5px 0.5px 0px #333;
        }

        /* Mejorar legibilidad en impresión */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            body {
                font-weight: bold;
            }
            
            .header h1 {
                font-size: 15px;
                font-weight: 900;
            }
            
            .section-title {
                font-weight: 900;
            }
            
            .total-final {
                font-weight: 900;
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <h1>TRANSPORTES</h1>
            <h1>WILL MAR</h1>
            <p><strong>SANTA CRUZ - ORURO</strong></p>
            <p><strong>FACTURA ENCOMIENDA</strong></p>
            <h1>{{ strip_tags($encomienda->numero_guia) ?? 'No especificado' }}</h1>
            <p><strong>Fecha: {{ now()->format('d/m/Y H:i') }}</strong></p>
        </div>

        <div class="section">
            <div class="section-title">DESCRIPCION GENERAL</div>
            <div class="description">
                <strong>{{ strip_tags($encomienda->descripcion_contenido) ?? 'No especificado' }}</strong>
            </div>
        </div>

        <div class="section">
            <div class="section-title">DATOS DE ENVIO</div>
            <div class="description">
                <strong>Remitente:</strong> {{ strip_tags($encomienda->nombre_remitente) ?? 'No especificado' }}
            </div>
            <div class="description">
                <strong>Destinatario:</strong> {{ strip_tags($encomienda->nombre_destinatario) ?? 'No especificado' }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">DETALLE PAQUETES</div>
            @foreach($encomienda->paquetes as $index => $paquete)
            <div class="package-item">
                <div class="package-header">PAQUETE #{{ $index + 1 }}</div>
                <div class="package-detail"><strong>Peso:</strong> {{ number_format($paquete->peso, 2) }} kg</div>
                <div class="package-detail"><strong>Dim:</strong> {{ $paquete->alto }}x{{ $paquete->ancho }}x{{ $paquete->largo }} cm</div>
                <div class="package-detail"><strong>Contenido:</strong> {{ $paquete->descripcion ?? 'No especificado' }}</div>
                <div class="package-detail"><strong>Precio:</strong> Bs {{ number_format($paquete->precio, 2) }}</div>
            </div>
            @endforeach
        </div>

        <div class="separator">
            <strong>================================</strong>
        </div>

        <div class="totals">
            <div class="total-row">
                <span><strong>Peso total:</strong></span>
                <span class="value">{{ number_format($encomienda->paquetes->sum('peso'), 2) }} kg</span>
            </div>
            <div class="total-row">
                <span><strong>Volumen total:</strong></span>
                <span class="value">{{ number_format($encomienda->paquetes->sum('alto') * $encomienda->paquetes->sum('ancho') * $encomienda->paquetes->sum('largo') / 1000000, 2) }} m³</span>
            </div>
            <div class="total-row">
                <span><strong>Cantidad paquetes:</strong></span>
                <span class="value">{{ $encomienda->paquetes->count() }}</span>
            </div>

            <div class="total-row total-final">
                <span><strong>TOTAL A PAGAR:</strong></span>
                <span class="value">Bs {{ number_format($encomienda->paquetes->sum('precio'), 2) }}</span>
            </div>

            <div class="estado-pago @if ($encomienda->estado_pago === 'pagado') pagado @elseif ($encomienda->estado_pago === 'con_saldo') con-saldo @endif">
                <strong>ESTADO DE PAGO:</strong>
                @if ($encomienda->estado_pago === 'pagado')
                    <div class="value"><strong>*** PAGADO ***</strong></div>
                @elseif ($encomienda->estado_pago === 'con_saldo')
                    <div class="value"><strong>CON SALDO</strong></div>
                    <div class="saldo-info"><strong>Monto Pagado:</strong> Bs {{ number_format($encomienda->monto_pagado, 2) }}</div>
                    <div class="saldo-info"><strong>Saldo Pendiente:</strong> <strong>Bs {{ number_format($encomienda->saldo_pendiente, 2) }}</strong></div>
                @else
                    {{ $encomienda->estado_pago }}
                @endif

                @if ($encomienda->tiene_valor_declarado)
                <div class="saldo-info"><strong>Valor Declarado:</strong> Bs {{ number_format($encomienda->valor_declarado, 2) }}</div>
                @endif
            </div>
        </div>

        <div class="separator">
            <strong>================================</strong>
        </div>

        <div class="footer">
            <p><strong>Transportes Will Mar © {{ now()->year }}</strong></p>
            <p><strong>Factura generada automaticamente</strong></p>
            <p><strong>Gracias por su confianza</strong></p>
        </div>
    </div>
</body>

</html>