<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket Folio {{ $venta->id_venta }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; margin: 0; padding: 10px; width: 300px; color: #000; font-size: 14px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .titulo { font-size: 24px; font-weight: 900; margin-bottom: 5px; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 3px 0; vertical-align: top; }
        .item-name { padding-left: 10px; font-size: 12px; font-style: italic; }
    </style>
</head>
<body>

    <div class="text-center">
        <div class="titulo">PIZZETOS</div>
        <div>Ticket de Venta</div>
        <div class="bold" style="font-size: 16px;">FOLIO: {{ $venta->id_venta }}</div>
        <div>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y, h:i A') }}</div>
        <div class="bold" style="font-size: 18px; margin-top:5px;">
            @if($venta->tipo_servicio == 1) COMEDOR (Mesa: {{ $venta->mesa }}) @endif
            @if($venta->tipo_servicio == 2) PARA LLEVAR @endif
            @if($venta->tipo_servicio == 3) DOMICILIO @endif
            @if($venta->tipo_servicio == 4) ESPECIAL @endif
        </div>
    </div>

    <div class="line"></div>

    <div style="font-size: 13px;">
        <span class="bold">Cliente:</span><br>
        {{ $venta->nombreClie ?? ($domicilio->nombre ?? 'Publico en general') }}
        
        @if(isset($domicilio))
            <br>Dirección: {{ $domicilio->calle }}, Col: {{ $domicilio->colonia }}, Ref: {{ $domicilio->referencia }}, Mz: {{ $domicilio->manzana }}, Lt: {{ $domicilio->lote }}
        @endif
    </div>

    <div class="line"></div>

    <table>
        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th style="text-align: left; width: 15%;">Cant</th>
                <th style="text-align: left; width: 60%;">Prod</th>
                <th style="text-align: right; width: 25%;">$$</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $d)
            <tr>
                <td>{{ $d->cantidad }}</td>
                <td>Producto BD</td>
                <td class="text-right">${{ number_format($d->precio_unitario * $d->cantidad, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="bold" style="font-size: 18px;">TOTAL:</td>
            <td class="text-right bold" style="font-size: 18px;">${{ number_format($venta->total, 2) }}</td>
        </tr>
    </table>

    <div style="margin-top: 10px; font-size: 13px;">
        <div class="bold">FORMA DE PAGO:</div>
        @foreach($pagos as $p)
            <div>{{ $p->metodo }}: Pago con ${{ number_format($p->monto, 2) }}</div>
            @if($p->referencia) <div>Ref: {{ $p->referencia }}</div> @endif
        @endforeach
    </div>

    <div class="line"></div>
    <div class="text-center">¡Gracias por su compra!</div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>