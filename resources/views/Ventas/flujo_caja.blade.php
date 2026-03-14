@extends('layouts.app')

@section('content')
<style>
    /* VARIABLES DE DISEÑO PIZZETOS ACTUALIZADAS */
    :root {
        --pizzetos-amber: #fbbf24;
        --pizzetos-radius: 45px;
        --pizzetos-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.05);
    }

    .pizzetos-card {
        background: #ffffff !important;
        border-radius: var(--pizzetos-radius) !important;
        border: 1px solid #f1f5f9 !important;
        box-shadow: var(--pizzetos-shadow) !important;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .pizzetos-title { font-weight: 900 !important; font-style: italic !important; text-transform: uppercase !important; letter-spacing: -1px !important; }
    .pizzetos-label { font-size: 9px !important; font-weight: 900 !important; text-transform: uppercase !important; color: #94a3b8 !important; letter-spacing: 1px; font-style: italic; margin-bottom: 8px; }

    /* Estilos para los desgloses de pago en la tabla */
    .pay-badge-split {
        display: flex;
        align-items: center;
        border-radius: 10px;
        overflow: hidden;
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        border: 1px solid #f1f5f9;
        margin-bottom: 4px;
    }
    .pay-method { padding: 3px 8px; }
    .pay-amount { padding: 3px 8px; background: #fff; color: #1e293b; border-left: 1px solid #f1f5f9; }

    .bg-efectivo { background: #f0fdf4; color: #16a34a; }
    .bg-tarjeta { background: #eff6ff; color: #2563eb; }
    .bg-transf { background: #faf5ff; color: #9333ea; }

    .pizzetos-btn {
        background: var(--pizzetos-amber) !important;
        border-radius: 20px !important;
        padding: 1.2rem;
        font-weight: 900 !important;
        font-style: italic !important;
        text-transform: uppercase !important;
        color: #000 !important;
        border: none !important;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
        display: block;
        width: 100%;
    }
    .pizzetos-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(251, 191, 36, 0.3); }
</style>

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
     class="fixed top-6 right-6 z-50 bg-[#00b300] text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 italic font-black uppercase tracking-tighter">
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('download_pdf'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        window.open("{{ route('flujo.caja.pdf', session('download_pdf')) }}", "_blank");
    });
</script>
@endif

<div class="w-full space-y-8 pb-12 px-2">

    @if($cajaAbierta)
        {{-- HEADER CON FOLIO VIRTUAL --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-[10px] pizzetos-title border border-green-200">Terminal Online</span>
                {{-- USANDO FOLIO VIRTUAL --}}
                <h1 class="text-6xl pizzetos-title text-slate-900 mt-4 leading-none">Caja {{ $cajaAbierta->folio_virtual }}</h1>
                <p class="text-sm font-bold text-slate-400 uppercase italic">Responsable: <span class="text-amber-500">{{ $cajaAbierta->cajero_nombre }}</span></p>
            </div>
            
            <div class="pizzetos-card" style="min-width: 250px; text-align: center; border-bottom: 8px solid var(--pizzetos-amber) !important; padding: 1.5rem !important;">
                <span class="pizzetos-label">Fondo de Inicio</span>
                <div class="text-4xl pizzetos-title text-slate-800">${{ number_format($cajaAbierta->monto_inicial, 2) }}</div>
            </div>
        </div>

        {{-- MÉTRICAS PRINCIPALES --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="pizzetos-card">
                <span class="pizzetos-label">Venta Bruta Total</span>
                <div class="text-3xl pizzetos-title text-slate-900">${{ number_format($stats['venta_total_bruta'], 2) }}</div>
            </div>
            <div class="pizzetos-card">
                <span class="pizzetos-label" style="color: var(--pizzetos-amber) !important;">Tickets</span>
                <div class="text-4xl pizzetos-title" style="color: var(--pizzetos-amber) !important;">{{ $stats['num_ventas'] }}</div>
            </div>
            <div class="pizzetos-card">
                <span class="pizzetos-label text-red-400">Egresos (Gastos)</span>
                <div class="text-3xl pizzetos-title text-red-500">-${{ number_format($stats['total_gastos'], 2) }}</div>
            </div>
            <div class="pizzetos-card" style="border-bottom: 8px solid #10b981 !important;">
                <span class="pizzetos-label text-green-500">Efectivo Real</span>
                <div class="text-4xl pizzetos-title text-green-600">${{ number_format($stats['efectivo_real_en_sobre'], 2) }}</div>
            </div>
        </div>

        {{-- NUEVAS CARDS: DESGLOSE POR MÉTODO --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="pizzetos-card" style="border-left: 8px solid #16a34a !important;">
                <span class="pizzetos-label">Acumulado Efectivo</span>
                <div class="text-3xl pizzetos-title text-slate-800">${{ number_format($stats['efectivo_ventas'], 2) }}</div>
            </div>
            <div class="pizzetos-card" style="border-left: 8px solid #2563eb !important;">
                <span class="pizzetos-label">Acumulado Tarjeta</span>
                <div class="text-3xl pizzetos-title text-slate-800">${{ number_format($stats['tarjeta'], 2) }}</div>
            </div>
            <div class="pizzetos-card" style="border-left: 8px solid #9333ea !important;">
                <span class="pizzetos-label">Acumulado Transferencia</span>
                <div class="text-3xl pizzetos-title text-slate-800">${{ number_format($stats['transferencia'], 2) }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- TABLA HISTORIAL DE PEDIDOS --}}
            <div class="lg:col-span-2">
                <div class="pizzetos-card" style="padding: 0 !important; overflow: hidden;">
                    <div style="padding: 2rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="text-2xl pizzetos-title text-slate-800">Historial de Pedidos</h3>
                        <div style="width: 80px; height: 6px; background: var(--pizzetos-amber); border-radius: 10px;"></div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left italic">
                            <thead class="bg-slate-50">
                                <tr class="pizzetos-label">
                                    <th class="px-8 py-5">Orden (Folio)</th>
                                    <th class="px-8 py-5">Cliente / Servicio</th>
                                    <th class="px-8 py-5">Métodos y Cantidades</th>
                                    <th class="px-8 py-5 text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($ventas_detalle as $venta)
                                    <tr class="{{ $venta->status == 3 ? 'opacity-30' : '' }}">
                                        {{-- USANDO FOLIO VIRTUAL DE PEDIDO --}}
                                        <td class="px-8 py-6 font-black text-slate-900 text-lg">#{{ $venta->folio_virtual }}</td>
                                        <td class="px-8 py-6 leading-tight">
                                            <div class="flex flex-col">
                                                <span class="text-slate-800 font-black uppercase text-sm tracking-tighter">{{ $venta->nombre_cliente_formateado }}</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('h:i a') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            @foreach(explode(' + ', $venta->montos_detalle ?? '') as $detalle)
                                                @php
                                                    $partes = explode(': ', $detalle);
                                                    $metodo = $partes[0] ?? '';
                                                    $monto = $partes[1] ?? '';
                                                    $clase = str_contains($metodo, 'Efectivo') ? 'bg-efectivo' : (str_contains($metodo, 'Tarjeta') ? 'bg-tarjeta' : 'bg-transf');
                                                @endphp
                                                <div class="pay-badge-split w-fit">
                                                    <span class="pay-method {{ $clase }}">{{ $metodo }}</span>
                                                    <span class="pay-amount">{{ $monto }}</span>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="px-8 py-6 text-right font-black text-2xl text-slate-900 tracking-tighter">
                                            ${{ number_format($venta->total, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR: GASTOS Y ARQUEO FINAL --}}
            <div class="space-y-8">
                {{-- DETALLE DE GASTOS --}}
                <div class="pizzetos-card" style="padding: 2rem !important;">
                    <h3 class="pizzetos-label text-center mb-6">Detalle de Gastos</h3>
                    <div class="space-y-3">
                        @forelse($gastos_detalle as $g)
                            <div style="background: #f8fafc; padding: 1rem; border-radius: 15px; border: 1px solid #f1f5f9;">
                                <div class="flex justify-between items-start">
                                    <span class="text-[8px] font-black text-slate-400 uppercase italic">{{ $g->responsable }}</span>
                                    <span class="font-black text-red-500 italic">-${{ number_format($g->precio, 2) }}</span>
                                </div>
                                <p class="text-[10px] font-bold text-slate-700 uppercase mt-1">{{ $g->descripcion }}</p>
                            </div>
                        @empty
                            <p class="text-center text-[10px] font-black text-slate-300 uppercase py-4">Sin gastos</p>
                        @endforelse
                    </div>
                </div>

                {{-- PANEL ARQUEO --}}
                <div x-data="{ modal: false, contado: '', esperado: {{ $stats['efectivo_real_en_sobre'] }} }">
                    <div class="pizzetos-card" style="border-top: 10px solid var(--pizzetos-amber) !important; background: #fafafa !important;">
                        <h3 class="text-2xl pizzetos-title text-center mb-8">Arqueo Final</h3>
                        <form id="formCerrar" action="{{ route('flujo.caja.cerrar', $cajaAbierta->id_caja) }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="text-center">
                                <label class="pizzetos-label block mb-3">Conteo Físico Real</label>
                                <input type="number" step="0.01" name="monto_final" x-model="contado" required 
                                       style="width: 100%; background: #fff; border: 2px solid #f1f5f9; border-radius: 20px; padding: 1.2rem; text-align: center; font-size: 2.5rem; font-weight: 900; font-style: italic; outline: none;">
                            </div>

                            <div style="background: #fff; padding: 1.5rem; border-radius: 20px; border: 1px solid #f1f5f9;">
                                <div class="flex justify-between pizzetos-label mb-3">
                                    <span>Balance Sistema:</span>
                                    <span class="text-slate-700">${{ number_format($stats['efectivo_real_en_sobre'], 2) }}</span>
                                </div>
                                <div class="flex justify-between pizzetos-title border-t border-slate-100 pt-4 items-center">
                                    <span class="text-amber-500 text-[10px]">DIFERENCIA:</span>
                                    <span style="font-size: 1.4rem;" :class="(contado - esperado) > 0 ? 'text-green-500' : ((contado - esperado) < 0 ? 'text-red-500' : 'text-slate-700')" x-text="contado === '' ? '$0.00' : '$' + (contado - esperado).toFixed(2)"></span>
                                </div>
                            </div>

                            {{-- BOTÓN DE PDF (AVANCE) --}}
                            <a href="{{ route('flujo.caja.pdf', $cajaAbierta->id_caja) }}" target="_blank" 
                               class="flex items-center justify-center gap-2 w-full py-4 font-black uppercase italic rounded-xl bg-slate-100 text-slate-500 text-[10px] hover:bg-slate-200 transition-all mb-2">
                                <svg style="width:16px;height:16px" fill="currentColor" viewBox="0 0 24 24"><path d="M12 16l-4-4h3V4h2v8h3l-4 4zm9-4h-2v4H5v-4H3v4c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-4z"/></svg>
                                Imprimir Avance
                            </a>

                            <button type="button" @click="if(contado !== '') { modal = true } else { alert('Ingresa el monto.') }" 
                                    class="pizzetos-btn w-full shadow-lg shadow-amber-100">
                                Cerrar Turno
                            </button>

                            {{-- MODAL --}}
                            <div x-show="modal" x-cloak style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; z-index: 1000; padding: 1rem;">
                                <div class="pizzetos-card" style="max-width: 400px; text-align: center;">
                                    <h3 class="text-3xl pizzetos-title text-slate-900 mb-6 italic">¿Cerrar Caja?</h3>
                                    <p class="text-slate-400 font-bold italic text-[11px] mb-10 uppercase">Se bloquearán los folios y se debe retirar el fondo físicamente.</p>
                                    <div class="flex gap-4">
                                        <button @click="modal = false" type="button" class="flex-1 py-4 font-black uppercase italic rounded-xl bg-slate-100 text-slate-400 text-[10px]">No</button>
                                        <button type="button" @click="document.getElementById('formCerrar').submit()" class="flex-1 py-4 font-black uppercase italic rounded-xl bg-red-600 text-white text-[10px]">Sí, Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection