<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja #{{ $caja->id_caja }}</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #1e293b; margin: 0; padding: 10px; }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { margin: 0; color: #0f172a; font-size: 26px; }
        .header p { margin: 4px 0; color: #64748b; font-size: 12px; }
        
        .section-title { background: #f1f5f9; padding: 10px 15px; font-weight: bold; border-radius: 6px; margin-bottom: 15px; font-size: 14px; }
        
        .row { width: 100%; margin-bottom: 20px; clear: both; overflow: hidden; }
        .kpi-box { float: left; width: 22%; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; text-align: center; margin-right: 2%; box-sizing: border-box; }
        .kpi-box:last-child { margin-right: 0; width: 28%; }
        
        .kpi-title { font-size: 9px; color: #94a3b8; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; letter-spacing: 0.5px;}
        .kpi-value { font-size: 18px; font-weight: 900; color: #0f172a; margin: 0; }
        .text-red { color: #dc2626 !important; }

        .payment-box { float: left; width: 31%; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-right: 2%; box-sizing: border-box;}
        .payment-box:last-child { margin-right: 0; width: 34%; }
        .payment-title { font-size: 13px; font-weight: bold; margin-bottom: 2px; }
        .payment-pct { font-size: 10px; color: #94a3b8; margin-bottom: 6px; }
        .payment-value { font-size: 16px; font-weight: 900; margin: 0; }
        
        .summary-box { background: #fef9c3; border: 2px solid #fde047; border-radius: 8px; padding: 25px; margin-top: 30px; }
        .summary-row { width: 100%; margin-bottom: 10px; font-size: 14px; clear: both; overflow: hidden; }
        .summary-label { float: left; width: 70%; color: #475569; }
        .summary-val { float: right; width: 28%; text-align: right; font-weight: 900; color: #0f172a; }
        
        .total-line { border-top: 1px solid #eab308; padding-top: 10px; margin-top: 10px; }
        
        .footer { text-align: center; margin-top: 60px; font-size: 9px; color: #cbd5e1; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Cierre de Caja #{{ $caja->id_caja }}</h1>
        <p>Apertura: {{ \Carbon\Carbon::parse($caja->fecha_apertura)->format('d \d\e F \d\e Y, h:i a') }}</p>
        <p>Cierre: {{ $caja->fecha_cierre ? \Carbon\Carbon::parse($caja->fecha_cierre)->format('d \d\e F \d\e Y, h:i a') : 'Aún abierta' }}</p>
        <p>Cajero: {{ $caja->cajero_nombre ?? 'Administrador' }}</p>
    </div>

    <div class="section-title">Información General</div>
    <div class="row">
        <div class="kpi-box">
            <div class="kpi-title">Fondo Inicial</div>
            <div class="kpi-value">${{ number_format($caja->monto_inicial, 2) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-title">Venta Total</div>
            <div class="kpi-value">${{ number_format($stats['venta_total'], 2) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-title">Número de Pedidos</div>
            <div class="kpi-value">{{ $stats['num_ventas'] }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-title text-red">Gastos</div>
            <div class="kpi-value text-red">-${{ number_format($stats['total_gastos'], 2) }}</div>
        </div>
    </div>

    @php
        $totalPagos = $stats['efectivo'] + $stats['tarjeta'] + $stats['transferencia'];
        $pctEfe = $totalPagos > 0 ? round(($stats['efectivo'] / $totalPagos) * 100, 1) : 0;
        $pctTar = $totalPagos > 0 ? round(($stats['tarjeta'] / $totalPagos) * 100, 1) : 0;
        $pctTra = $totalPagos > 0 ? round(($stats['transferencia'] / $totalPagos) * 100, 1) : 0;
    @endphp

    <div class="section-title">Desglose por Método de Pago</div>
    <div class="row">
        <div class="payment-box">
            <div class="payment-title">Efectivo</div>
            <div class="payment-pct">{{ $pctEfe }}% del total</div>
            <div class="payment-value">${{ number_format($stats['efectivo'], 2) }}</div>
        </div>
        <div class="payment-box">
            <div class="payment-title">Tarjeta</div>
            <div class="payment-pct">{{ $pctTar }}% del total</div>
            <div class="payment-value">${{ number_format($stats['tarjeta'], 2) }}</div>
        </div>
        <div class="payment-box">
            <div class="payment-title">Transferencia</div>
            <div class="payment-pct">{{ $pctTra }}% del total</div>
            <div class="payment-value">${{ number_format($stats['transferencia'], 2) }}</div>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-row">
            <div class="summary-label">Venta Total:</div>
            <div class="summary-val">${{ number_format($stats['venta_total'], 2) }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label text-red">Gastos:</div>
            <div class="summary-val text-red">-${{ number_format($stats['total_gastos'], 2) }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Ventas con Tarjeta:</div>
            <div class="summary-val">-${{ number_format($stats['tarjeta'], 2) }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Ventas por Transferencia:</div>
            <div class="summary-val">-${{ number_format($stats['transferencia'], 2) }}</div>
        </div>
        <div class="summary-row total-line">
            <div class="summary-label" style="font-weight: bold; color: #000;">Efectivo Esperado (Incluye caja inicial):</div>
            <div class="summary-val" style="font-size: 18px;">${{ number_format($caja->monto_inicial + $stats['efectivo'] - $stats['total_gastos'], 2) }}</div>
        </div>
    </div>

    <div class="footer">
        Documento generado automáticamente - {{ Carbon\Carbon::now()->format('d/m/Y, h:i:s a') }}
    </div>
</body>
</html>