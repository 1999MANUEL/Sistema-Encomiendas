<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manifiesto de Carga - Transportes Will Mar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background-color: #fff;
        }

        /* Container for Letter Size */
        .container {
            max-width: 8.5in;
            margin: 0 auto;
            padding: 0.5in;
            background: white;
        }

        /* Header Section */
        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .company-subtitle {
            font-size: 14px;
            color: #7f8c8d;
            margin: 5px 0;
            font-weight: 500;
        }

        .company-details {
            font-size: 11px;
            color: #34495e;
            margin-top: 8px;
            line-height: 1.3;
        }

        /* Document Title */
        .document-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            background-color: #ecf0f1;
            padding: 12px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Document Info Section */
        .document-info {
            display: flex;
          gap: 20px;
            justify-content: space-between;
   
            margin-bottom: 25px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .info-right {
            flex: 1;
        }
        .info-left{
            float: right;
        }

        .document-info strong {
            color: #2c3e50;
            font-weight: 600;
         
        }

        /* Table Styles */
        .manifest-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .manifest-table th {
            background-color: #34495e;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .manifest-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }

        .manifest-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .manifest-table tr:hover {
            background-color: #e9ecef;
        }

        .guia-column {
            width: 15%;
            text-align: center;
            font-weight: bold;
            color: #2c3e50;
        }

        .contenido-column {
            width: 60%;
            padding-left: 12px;
        }

        .contenido-column strong {
            color: #2c3e50;
            display: block;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .precio-column {
            width: 25%;
            text-align: right;
            font-weight: bold;
            color: #27ae60;
            padding-right: 12px;
        }

        /* Total Row */
        .total-row {
            background-color: #2c3e50 !important;
            color: white !important;
            font-weight: bold;
        }

        .total-row td {
            padding: 12px 8px;
            border: none;
            font-size: 13px;
        }

        .total-row strong {
            color: white;
        }

     .footer-section {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 50px;
    padding-top: 20px;
    border-top: 2px solid #ecf0f1;
}

        .signature-box {
            text-align: center;
            width: 30%;
            padding: 10px 0;
            font-size: 11px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            
        }

        .signature-line {
            border-bottom: 2px solid #34495e;
            margin-top: 35px;
            margin-bottom: 5px;
            width: 100%;
         
        }

        /* Print Styles */
        @media print {
            .container {
                padding: 10px;
            }
            
            body {
                font-size: 11px;
            }
            
            .manifest-table {
                box-shadow: none;
            }
        }


    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">TRANSPORTES</div>
            <div class="company-name">WILL MAR</div>
            <div class="company-subtitle">SANTA CRUZ - ORURO</div>
            <div class="company-details">
                ORURO - COCHABAMBA - LLAGUA<br>
           
            </div>
        </div>

        <!-- Document Title -->
        <div class="document-title">MANIFIESTO DE CARGA</div>

        <!-- Document Information -->
        <div class="document-info">
            <div class="info-left">
                <strong>Conductor:</strong> {{ $manifiesto->conductor->nombre_conductor ?? 'N/A' }}<br>
                <strong>Oficina Origen:</strong> {{ $manifiesto->sucursalOrigen->ciudad ?? 'N/A' }}<br>
                <strong>Oficina Destino:</strong> {{ $manifiesto->sucursalDestino->ciudad ?? 'N/A' }}
            </div>
            <div class="info-right">
                <strong>Placa Camión:</strong> {{ $manifiesto->vehiculo->placa ?? 'N/A' }}<br>
                <strong>Fecha de Envío:</strong> {{ $manifiesto->fecha_envio ? \Carbon\Carbon::parse($manifiesto->fecha_envio)->format('d/m/Y') : 'N/A' }}<br>
                <strong>Hora de Envío:</strong> {{ $manifiesto->hora_envio ?? 'N/A' }}
            </div>
        </div>

        <!-- Manifest Table -->
        <table class="manifest-table">
            <thead>
                <tr>
                    <th class="guia-column">GUÍA</th>
                    <th class="contenido-column">CONTENIDO</th>
                    <th class="precio-column">PRECIO (Bs)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($manifiesto->encomiendas as $e)
                <tr>
                    <td class="guia-column">{{ $e->numero_guia }}</td>
                    <td class="contenido-column">
                        <strong>{{ $e->nombre_remitente }}</strong><br>
                        {{ $e->descripcion_contenido }}
                    </td>
                    <td class="precio-column">{{ number_format($e->precio, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px; color: #7f8c8d;">No hay encomiendas registradas.</td>
                </tr>
                @endforelse
                
                <!-- Total Row -->
                <tr class="total-row">
                    <td colspan="2" style="text-align: right; padding-right: 20px;">
                        <strong>TOTAL:</strong>
                    </td>
                    <td class="precio-column">
                        <strong>{{ number_format($manifiesto->precio_total ?? 0, 2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Signatures Section - Vertical Layout -->
     <table width="100%" style="margin-top: 50px; text-align: center;">
    <tr>
        <td>
            <br><br>
            _______________________<br>
            <strong>OF. DE DESPACHO</strong>
        </td>
        <td>
            <br><br>
            _______________________<br>
            <strong>CONDUCTOR</strong>
        </td>
        <td>
            <br><br>
            _______________________<br>
            <strong>OF. RECEPCIÓN</strong>
        </td>
    </tr>
</table>

    </div>
</body>
</html>