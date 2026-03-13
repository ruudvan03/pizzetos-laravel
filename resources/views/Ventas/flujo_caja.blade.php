@extends('layouts.app')

@section('content')
{{-- Notificaciones --}}
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
     class="fixed top-6 right-6 z-50 bg-[#00b300] text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 italic font-black uppercase tracking-tighter">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
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

<div class="w-full font-sans p-2">

    @if(!$cajaAbierta)
        {{-- Pantalla de Apertura (Simplificada y robusta) --}}
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="bg-amber-100 p-8 rounded-[3rem] mb-6">
                <svg class="w-16 h-16 text-amber-600" fill="currentColor" viewBox="0 0 512 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V192c0-35.3-28.7-64-64-64H80c-8.8 0-16-7.2-16-16s7.2-16 16-16H448c17.7 0 32-14.3 32-32s-14.3-32-32-32H64z"/></svg>
            </div>
            <h2 class="text-4xl font-black text-slate-800 italic uppercase tracking-tighter">Terminal Inactiva</h2>
            <form action="{{ route('flujo.caja.abrir') }}" method="POST" class="mt-8 bg-white p-10 rounded-[3rem] shadow-2xl border border-slate-100 w-full max-w-md">
                @csrf
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Fondo Inicial de Caja</label>
                <input type="number" step="0.01" name="monto_inicial" required value="3000.00" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] p-6 text-3xl font-black italic text-center outline-none focus:border-amber-400 mb-6">
                <button type="submit" class="w-full bg-amber-400 hover:bg-amber-500 text-slate-900 font-black py-6 rounded-[2rem] shadow-xl uppercase italic tracking-widest transition-transform hover:scale-105">Abrir Turno</button>
            </form>
        </div>
    @else
        {{-- DASHBOARD ACTIVO --}}
        <div class="space-y-10">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-green-500 w-3 h-3 rounded-full animate-pulse"></span>
                        <span class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] italic">Apertura: {{ \Carbon\Carbon::parse($cajaAbierta->fecha_apertura)->format('d.m.Y / h:i a') }}</span>
                    </div>
                    <h1 class="text-6xl font-black text-slate-900 italic uppercase tracking-tighter leading-none">Caja #{{ $cajaAbierta->id_caja }}</h1>
                    <p class="text-lg font-bold text-slate-400 uppercase italic tracking-widest mt-2">Responsable: <span class="text-amber-500">{{ $cajaAbierta->cajero_nombre }}</span></p>
                </div>

                <div class="bg-amber-400 p-8 rounded-[2.5rem] shadow-2xl border-b-[10px] border-amber-600 text-center min-w-[280px]">
                    <span class="text-[10px] font-black uppercase text-amber-900 tracking-[0.3em] italic block mb-1">Fondo de Reserva</span>
                    <span class="text-4xl font-black text-black italic tracking-tighter">${{ number_format($cajaAbierta->monto_inicial, 2) }}</span>
                </div>
            </div>

            {{-- Grid de KPIs con bordes redondeados forzados --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-10 rounded-[3.5rem] shadow-xl border border-slate-50 relative overflow-hidden">
                    <span class="text-[11px] font-black uppercase text-slate-400 tracking-widest block mb-4 italic">Venta Total Bruta</span>
                    <h3 class="text-4xl font-black text-slate-900 italic tracking-tighter">${{ number_format($stats['venta_total_bruta'], 2) }}</h3>
                </div>

                <div class="bg-slate-800 p-10 rounded-[3.5rem] shadow-2xl border-b-8 border-slate-900">
                    <span class="text-[11px] font-black uppercase text-amber-400 tracking-widest block mb-4 italic">Folios Hoy</span>
                    <h3 class="text-5xl font-black text-white italic tracking-tighter leading-none">{{ $stats['num_pedidos'] }}</h3>
                </div>

                <div class="bg-white p-10 rounded-[3.5rem] shadow-xl border border-slate-50">
                    <span class="text-[11px] font-black uppercase text-red-400 tracking-widest block mb-4 italic">Egresos (Gastos)</span>
                    <h3 class="text-4xl font-black text-red-500 italic tracking-tighter">-${{ number_format($stats['total_gastos'], 2) }}</h3>
                </div>

                <div class="bg-green-600 p-10 rounded-[3.5rem] shadow-2xl border-b-8 border-green-800 text-white">
                    <span class="text-[11px] font-black uppercase text-green-200 tracking-widest block mb-4 italic">Efectivo Real</span>
                    <h3 class="text-5xl font-black italic tracking-tighter leading-none">${{ number_format($stats['efectivo_real_en_sobre'], 2) }}</h3>
                    <p class="text-[10px] mt-4 font-black uppercase text-green-200 italic tracking-widest opacity-80">Neto (EF Ventas - Gastos)</p>
                </div>
            </div>

            {{-- Detalle Inferior --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                {{-- Tabla de Auditoría --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[4rem] shadow-xl border border-slate-100 overflow-hidden">
                        <div class="p-10 border-b border-slate-50 flex justify-between items-center">
                            <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter">Auditoría de Folios</h3>
                            <div class="h-2 w-24 bg-amber-400 rounded-full"></div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50">
                                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">
                                        <th class="px-10 py-6">Folio</th>
                                        <th class="px-10 py-6">Cliente / Servicio</th>
                                        <th class="px-10 py-6">Pagos</th>
                                        <th class="px-10 py-6 text-right">Monto</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 italic">
                                    @foreach($ventas_detalle as $venta)
                                    <tr class="{{ $venta->status == 3 ? 'opacity-25 grayscale' : '' }} hover:bg-slate-50/50 transition-colors">
                                        <td class="px-10 py-8 font-black text-slate-900 text-lg">#{{ $venta->id_venta }}</td>
                                        <td class="px-10 py-8">
                                            <div class="flex flex-col">
                                                <span class="text-slate-800 font-black uppercase tracking-tighter text-base">{{ $venta->nombre_cliente_formateado }}</span>
                                                <span class="text-xs font-bold text-slate-400 uppercase mt-1">{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('h:i a') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-8">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach(explode(', ', $venta->metodos_pago ?? '') as $idx => $m)
                                                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $m == 'Efectivo' ? 'bg-green-100 text-green-700' : ($m == 'Tarjeta' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                                        {{ $m }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            @if($venta->referencias && $venta->referencias != 'S/R')
                                                <p class="text-[10px] font-black text-amber-600 uppercase mt-2">Ref: {{ $venta->referencias }}</p>
                                            @endif
                                        </td>
                                        <td class="px-10 py-8 text-right font-black text-2xl text-slate-900 tracking-tighter">
                                            ${{ number_format($venta->total, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Sidebar: Métodos y Gastos --}}
                <div class="space-y-8">
                    {{-- Balances por método --}}
                    <div class="bg-white rounded-[3.5rem] p-10 shadow-xl border border-slate-100">
                        <h3 class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] mb-8 italic">Balance por Método</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between p-6 bg-green-50 rounded-[2rem] border border-green-100 font-black italic">
                                <span class="text-green-700 uppercase text-xs">Efectivo</span>
                                <span class="text-green-900 text-xl tracking-tighter">${{ number_format($stats['efectivo_ventas'], 2) }}</span>
                            </div>
                            <div class="flex justify-between p-6 bg-blue-50 rounded-[2rem] border border-blue-100 font-black italic">
                                <span class="text-blue-700 uppercase text-xs">Tarjetas</span>
                                <span class="text-blue-900 text-xl tracking-tighter">${{ number_format($stats['tarjeta'], 2) }}</span>
                            </div>
                            <div class="flex justify-between p-6 bg-purple-50 rounded-[2rem] border border-purple-100 font-black italic">
                                <span class="text-purple-700 uppercase text-xs">Transferencia</span>
                                <span class="text-purple-900 text-xl tracking-tighter">${{ number_format($stats['transferencia'], 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Arqueo Final (El panel Slate oscuro) --}}
                    <div x-data="{ modal: false, contado: '', esperado: {{ $stats['efectivo_real_en_sobre'] }} }">
                        <div class="bg-slate-800 rounded-[4rem] p-12 shadow-2xl border-t-[12px] border-amber-400">
                            <h3 class="text-3xl font-black text-white uppercase italic tracking-tighter text-center mb-10">Arqueo Final</h3>
                            <form id="formCerrar" action="{{ route('flujo.caja.cerrar', $cajaAbierta->id_caja) }}" method="POST" class="space-y-8">
                                @csrf
                                <div class="text-center">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 italic">Efectivo Físico Real</label>
                                    <div class="relative">
                                        <span class="absolute left-6 top-6 text-amber-400 font-black text-2xl italic leading-none">$</span>
                                        <input type="number" step="0.01" name="monto_final" x-model="contado" required class="w-full bg-white/5 border-2 border-white/10 rounded-[2.5rem] pl-14 pr-6 py-8 focus:border-amber-400 text-white text-5xl font-black italic tracking-tighter transition-all outline-none">
                                    </div>
                                </div>

                                <div class="bg-white/5 rounded-[2rem] p-8 space-y-4 border border-white/10 italic">
                                    <div class="flex justify-between text-xs font-bold uppercase"><span class="text-slate-400">Balance:</span><span class="text-white">${{ number_format($stats['efectivo_real_en_sobre'], 2) }}</span></div>
                                    <div class="flex justify-between items-center pt-6 border-t border-white/10">
                                        <span class="text-amber-400 font-black text-[10px] uppercase tracking-widest">Diferencia:</span>
                                        <span class="text-3xl font-black tracking-tighter" :class="(contado - esperado) > 0 ? 'text-green-400' : ((contado - esperado) < 0 ? 'text-red-400' : 'text-white')" x-text="contado === '' ? '$0.00' : '$' + (contado - esperado).toFixed(2)"></span>
                                    </div>
                                </div>

                                <button type="button" @click="if(contado !== '') { modal = true } else { alert('Ingresa el monto contado físico.') }" class="w-full bg-amber-400 hover:bg-amber-500 text-slate-900 font-black py-8 rounded-[2.5rem] shadow-xl uppercase italic tracking-widest text-sm transition-transform hover:scale-105">Ejecutar Cierre</button>
                                
                                <div class="text-center mt-4">
                                    <a href="{{ route('flujo.caja.pdf', $cajaAbierta->id_caja) }}" target="_blank" class="text-amber-400/50 hover:text-amber-400 text-[10px] font-black uppercase underline italic tracking-widest transition-colors">Vista Previa Reporte</a>
                                </div>

                                {{-- Modal de Confirmación --}}
                                <div x-show="modal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-slate-900/95 backdrop-blur-xl transition-all">
                                    <div class="bg-white rounded-[4rem] shadow-2xl w-full max-w-md p-14 text-center">
                                        <div class="w-24 h-24 bg-red-100 rounded-[2.5rem] mx-auto mb-8 flex items-center justify-center border-b-8 border-red-200">
                                            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <h3 class="text-4xl font-black text-slate-900 italic uppercase tracking-tighter mb-4 leading-none">Confirmar</h3>
                                        <p class="text-slate-400 text-sm font-bold uppercase italic tracking-widest mb-10 leading-relaxed">El fondo de reserva de ${{ number_format($cajaAbierta->monto_inicial, 2) }} debe ser retirado físicamente ahora.</p>
                                        <div class="flex gap-4">
                                            <button @click="modal = false" type="button" class="flex-1 bg-slate-100 text-slate-400 font-black py-6 rounded-[2rem] text-xs uppercase tracking-widest italic leading-none transition-colors hover:bg-slate-200">Cancelar</button>
                                            <button type="button" @click="document.getElementById('formCerrar').submit()" class="flex-1 bg-red-600 text-white font-black py-6 rounded-[2rem] text-xs uppercase tracking-widest italic shadow-xl shadow-red-200 leading-none transition-transform hover:scale-105">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection