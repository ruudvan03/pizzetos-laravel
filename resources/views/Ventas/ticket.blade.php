<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $venta->id_venta }}</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 13px; 
            margin: 0 auto; 
            padding: 10px; 
            width: 280px; 
            color: #000; 
            text-transform: uppercase; 
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-xl { font-size: 22px; letter-spacing: 1px; }
        .text-lg { font-size: 16px; }
        .mt-1 { margin-top: 5px; }
        .mb-1 { margin-bottom: 5px; }
        .border-top { border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { text-align: left; vertical-align: top; padding: 3px 0; }
        th { border-bottom: 1px dashed #000; font-weight: bold; padding-bottom: 3px; font-size: 12px;}
        
        .sub-item { font-size: 12px; color: #333; }
        .sub-text { padding-left: 8px; }
        
        .flex-between { display: flex; justify-content: space-between; }
        
        @media print {
            body { padding: 0; width: 100%; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center mb-1">
        <div class="font-bold text-xl mb-1">PIZZETOS</div>
        <div style="font-size: 12px;">TICKET DE VENTA</div>
        <div class="font-bold mt-1">FOLIO: {{ $venta->id_venta }}</div>
        <div style="font-size: 12px;">{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y h:i A') }}</div>
        
        <div class="font-bold text-lg mt-1 mb-1 border-top border-bottom py-1" style="padding: 5px 0;">
            @if($venta->tipo_servicio == 1)
                * COMEDOR - MESA {{ $venta->mesa }} *
            @elseif($venta->tipo_servicio == 2)
                * PARA LLEVAR *
            @elseif($venta->tipo_servicio == 3)
                * SERVICIO A DOMICILIO *
            @endif
        </div>
    </div>

    {{-- DATOS DEL CLIENTE --}}
    @if($venta->tipo_servicio == 3 && $domicilio)
        <div class="mb-1" style="font-size: 12px;">
            <div class="font-bold" style="font-size: 14px;">CLIENTE:</div>
            <div>{{ trim($domicilio->cnombre . ' ' . $domicilio->capellido) }}</div>
            <div><span class="font-bold">TEL:</span> {{ $domicilio->telefono }}</div>
            <div class="mt-1"><span class="font-bold">DIR:</span> {{ $domicilio->calle }}</div>
            <div><span class="font-bold">COL:</span> {{ $domicilio->colonia }}</div>
            <div><span class="font-bold">MZ:</span> {{ $domicilio->manzana }} | <span class="font-bold">LT:</span> {{ $domicilio->lote }}</div>
            @if($domicilio->referencia)
                <div><span class="font-bold">REF:</span> {{ $domicilio->referencia }}</div>
            @endif
        </div>
        <div class="border-top"></div>
    @elseif($venta->tipo_servicio == 1 && $venta->nombreClie)
        <div class="mb-1" style="font-size: 12px;">
            <div class="font-bold">CLIENTE:</div>
            <div>{{ $venta->nombreClie }}</div>
        </div>
        <div class="border-top"></div>
    @endif

    <table class="mb-1">
        <thead>
            <tr>
                <th style="width: 15%;">CANT</th>
                <th style="width: 60%;">DESCRIPCIÓN</th>
                <th style="width: 25%; text-align: right;">IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($final_items as $item)
                <tr>
                    <td class="font-bold" style="white-space: nowrap;">{{ $item->cantidad }}</td>
                    <td class="font-bold">{{ $item->nombre }}</td>
                    <td class="text-right font-bold">${{ number_format($item->total, 2) }}</td>
                </tr>
                
                @foreach($item->subs as $sub)
                <tr class="sub-item">
                    <td></td>
                    <td class="sub-text" colspan="2" style="white-space: pre-wrap;">{{ $sub }}</td>
                </tr>
                @endforeach
                
                {{-- Espacio separador entre productos --}}
                <tr><td colspan="3" style="height: 6px;"></td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="border-top border-bottom" style="padding: 5px 0;">
        <div class="flex-between">
            <div class="font-bold text-lg">TOTAL A PAGAR:</div>
            <div class="font-bold text-lg">${{ number_format($venta->total, 2) }}</div>
        </div>
    </div>

    @if($venta->comentarios)
        <div class="text-center font-bold border-bottom" style="padding: 5px 0; font-size: 12px;">
            ** {{ $venta->comentarios }} **
        </div>
    @endif

    {{-- ZONA DE PAGOS --}}
    @if($venta->status == 0)
        <div class="text-center font-bold" style="border: 2px solid #000; padding: 5px; margin-top: 10px;">
            CUENTA ABIERTA<br>PENDIENTE DE PAGO
        </div>
    @else
        <div style="margin-top: 10px;">
            <div class="font-bold mb-1" style="font-size: 14px;">MÉTODO DE PAGO:</div>
            
            @foreach($pagos as $pago)
                <div style="margin-bottom: 4px;">
                    @if($pago->id_metpago == 1)
                        <div class="flex-between font-bold">
                            <span>TARJETA</span>
                            <span>${{ number_format($pago->monto, 2) }}</span>
                        </div>
                    @elseif($pago->id_metpago == 2)
                        <div class="flex-between font-bold">
                            <span>EFECTIVO</span>
                            <span>${{ number_format($pago->monto, 2) }}</span>
                        </div>
                        @if($pago->referencia && is_numeric($pago->referencia) && $pago->referencia > $pago->monto)
                            <div class="flex-between" style="font-size: 11px; color: #333;">
                                <span>RECIBIDO:</span>
                                <span>${{ number_format($pago->referencia, 2) }}</span>
                            </div>
                            <div class="flex-between" style="font-size: 11px; color: #333;">
                                <span>CAMBIO:</span>
                                <span>${{ number_format($pago->referencia - $pago->monto, 2) }}</span>
                            </div>
                        @endif
                    @elseif($pago->id_metpago == 3)
                        <div class="flex-between font-bold">
                            <span>TRANSFERENCIA</span>
                            <span>${{ number_format($pago->monto, 2) }}</span>
                        </div>
                        @if($pago->referencia)
                            <div style="font-size: 11px; color: #333;">REF: {{ $pago->referencia }}</div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="text-center mt-1 pt-1" style="margin-top: 20px; font-size: 12px; font-weight: bold;">
        ¡GRACIAS POR SU PREFERENCIA!
    </div>

</body>
</html>