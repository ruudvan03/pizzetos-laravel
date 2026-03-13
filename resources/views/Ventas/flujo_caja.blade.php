@extends('layouts.app')

@section('content')
<style>
    /* Estilos base para asegurar que se vea igual en servidor que en local */
    .pizzetos-card {
        background: #ffffff;
        border-radius: 40px !important;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        padding: 2.5rem;
    }
    .pizzetos-card-dark {
        background: #1e293b;
        border-radius: 40px !important;
        padding: 2.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .pizzetos-btn-amber {
        background: #fbbf24;
        border-radius: 20px;
        transition: all 0.3s ease;
        font-weight: 900;
        text-transform: uppercase;
        font-style: italic;
    }
    .pizzetos-btn-amber:hover { background: #f59e0b; transform: translateY(-2px); }
    .text-huge { font-size: 2.5rem; line-height: 1; }
    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 99px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>

<div class="w-full space-y-8 pb-10">

    @if(!$cajaAbierta)
        <div class="flex flex-col items-center justify-center py-20">
            <div class="pizzetos-card w-full max-w-md text-center">
                <div class="bg-amber-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-amber-600" fill="currentColor" viewBox="0 0 512 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V192c0-35.3-28.7-64-64-64H80c-8.8 0-16-7.2-16-16s7.2-16 16-16H448c17.7 0 32-14.3 32-32s-14.3-32-32-32H64z"/></svg>
                </div>
                <h2 class="text-2xl font-black text-slate-800 italic uppercase mb-2">Apertura de Turno</h2>
                <p class="text-slate-400 text-sm mb-8 font-medium italic">Ingresa el fondo inicial para comenzar.</p>
                
                <form action="{{ route('flujo.caja.abrir') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="relative">
                        <span class="absolute left-6 top-5 text-slate-300 font-black text-xl">$</span>
                        <input type="number" step="0.01" name="monto_inicial" required value="3000.00" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-5 pl-12 text-2xl font-black text-slate-700 outline-none focus:border-amber-400">
                    </div>
                    <button type="submit" class="w-full pizzetos-btn-amber py-5 text-slate-900 shadow-lg">Abrir Caja</button>
                </form>
            </div>
        </div>
    @else
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 px-2">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <span class="status-badge bg-green-100 text-green-700">Sistema en Línea</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Apertura: {{ \Carbon\Carbon::parse($cajaAbierta->fecha_apertura)->format('h:i a') }}</span>
                </div>
                <h1 class="text-5xl font-black text-slate-900 italic uppercase tracking-tighter">Caja #{{ $cajaAbierta->id_caja }}</h1>
                <p class="text-sm font-bold text-slate-400 uppercase italic tracking-widest">Responsable: <span class="text-amber-500">{{ $cajaAbierta->cajero_nombre }}</span></p>
            </div>
            
            <div class="pizzetos-card-dark flex flex-col items-center justify-center min-w-[240px] !py-6 !bg-amber-400 border-b-8 border-amber-600 shadow-amber-200">
                <span class="text-[10px] font-black uppercase text-amber-900 tracking-widest mb-1 italic">Fondo de Reserva</span>
                <span class="text-4xl font-black text-black italic tracking-tighter leading-none">${{ number_format($cajaAbierta->monto_inicial, 2) }}</span>
            </div>
        </div>

        {{-- RESUMEN KPI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-2">
            <div class="pizzetos-card">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block italic">Venta Bruta</span>
                <h3 class="text-3xl font-black text-slate-800 italic tracking-tighter leading-none">${{ number_format($stats['venta_total_bruta'], 2) }}</h3>
            </div>
            <div class="pizzetos-card !bg-slate-800 text-white border-none shadow-slate-200">
                <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-3 block italic">Folios Emitidos</span>
                <h3 class="text-4xl font-black italic tracking-tighter leading-none">{{ $stats['num_pedidos'] }}</h3>
            </div>
            <div class="pizzetos-card">
                <span class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-3 block italic">Gastos Reportados</span>
                <h3 class="text-3xl font-black text-red-500 italic tracking-tighter leading-none">-${{ number_format($stats['total_gastos'], 2) }}</h3>
            </div>
            <div class="pizzetos-card !bg-green-600 text-white border-none shadow-green-200 border-b-8 border-green-800">
                <span class="text-[10px] font-black text-green-100 uppercase tracking-widest mb-3 block italic">Efectivo Real</span>
                <h3 class="text-4xl font-black italic tracking-tighter leading-none">${{ number_format($stats['efectivo_real_en_sobre'], 2) }}</h3>
                <p class="text-[8px] mt-3 font-bold uppercase opacity-70">Neto: Ventas EF - Gastos</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-2">
            {{-- TABLA --}}
            <div class="lg:col-span-2">
                <div class="pizzetos-card !p-0 overflow-hidden">
                    <div class="p-8 flex items-center justify-between border-b border-slate-50">
                        <h3 class="text-xl font-black text-slate-800 italic uppercase tracking-tighter">Auditoría de Operaciones</h3>
                        <div class="h-1.5 w-16 bg-amber-400 rounded-full"></div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest italic">
                                    <th class="px-8 py-5">Folio</th>
                                    <th class="px-8 py-5">Cliente / Servicio</th>
                                    <th class="px-8 py-5">Pagos</th>
                                    <th class="px-8 py-5 text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm italic">
                                @foreach($ventas_detalle as $venta)
                                    <tr class="hover:bg-slate-50/50 transition-colors {{ $venta->status == 3 ? 'opacity-30 grayscale' : '' }}">
                                        <td class="px-8 py-6 font-black text-slate-900">#{{ $venta->id_venta }}</td>
                                        <td class="px-8 py-6 leading-tight">
                                            <div class="flex flex-col">
                                                <span class="text-slate-800 font-black uppercase text-xs tracking-tighter">{{ $venta->nombre_cliente_formateado }}</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase mt-1">{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('h:i a') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach(explode(', ', $venta->metodos_pago ?? '') as $m)
                                                    <span class="px-2 py-1 rounded-md text-[8px] font-black uppercase tracking-widest border {{ $m == 'Efectivo' ? 'bg-green-50 text-green-700 border-green-200' : ($m == 'Tarjeta' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200') }}">
                                                        {{ $m }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <span class="text-xl font-black text-slate-900 tracking-tighter italic leading-none">${{ number_format($venta->total, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="space-y-6">
                <div class="pizzetos-card">
                    <h3 class="text-xs font-black text-slate-400 uppercase italic tracking-widest mb-6">Resumen de Métodos</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between p-4 bg-slate-50 rounded-2xl font-black italic text-xs">
                            <span class="text-slate-400">EFECTIVO</span><span class="text-slate-900">${{ number_format($stats['efectivo_ventas'], 2) }}</span>
                        </div>
                        <div class="flex justify-between p-4 bg-slate-50 rounded-2xl font-black italic text-xs">
                            <span class="text-slate-400">TARJETAS</span><span class="text-slate-900">${{ number_format($stats['tarjeta'], 2) }}</span>
                        </div>
                        <div class="flex justify-between p-4 bg-slate-50 rounded-2xl font-black italic text-xs">
                            <span class="text-slate-400">TRANSF.</span><span class="text-slate-900">${{ number_format($stats['transferencia'], 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- PANEL CIERRE --}}
                <div x-data="{ modal: false, contado: '', esperado: {{ $stats['efectivo_real_en_sobre'] }} }">
                    <div class="pizzetos-card-dark !bg-slate-900 border-t-8 border-amber-400">
                        <h3 class="text-2xl font-black text-white italic uppercase tracking-tighter text-center mb-8">Arqueo Final</h3>
                        <form id="formCerrar" action="{{ route('flujo.caja.cerrar', $cajaAbierta->id_caja) }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] text-center mb-4 italic">Conteo Físico Real</label>
                                <div class="relative">
                                    <span class="absolute left-6 top-5 text-amber-400 font-black text-2xl leading-none italic">$</span>
                                    <input type="number" step="0.01" name="monto_final" x-model="contado" required class="w-full bg-white/5 border border-white/10 rounded-2xl p-6 text-4xl font-black text-white italic tracking-tighter outline-none focus:border-amber-400">
                                </div>
                            </div>

                            <div class="bg-white/5 rounded-2xl p-6 space-y-4 font-black uppercase italic text-[10px]">
                                <div class="flex justify-between"><span class="text-slate-500">Balance Sist:</span><span class="text-white">${{ number_format($stats['efectivo_real_en_sobre'], 2) }}</span></div>
                                <div class="flex justify-between pt-4 border-t border-white/10 text-sm">
                                    <span class="text-amber-400">Diferencia:</span>
                                    <span :class="(contado - esperado) > 0 ? 'text-green-400' : ((contado - esperado) < 0 ? 'text-red-400' : 'text-white')" x-text="contado === '' ? '$0.00' : '$' + (contado - esperado).toFixed(2)"></span>
                                </div>
                            </div>

                            <button type="button" @click="if(contado !== '') { modal = true } else { alert('Ingresa el monto contado.') }" class="w-full pizzetos-btn-amber py-6 text-sm">Finalizar Turno</button>
                            
                            <div class="text-center">
                                <a href="{{ route('flujo.caja.pdf', $cajaAbierta->id_caja) }}" target="_blank" class="text-white/30 hover:text-amber-400 text-[9px] font-black uppercase underline italic tracking-widest transition-all">Vista Previa PDF</a>
                            </div>

                            {{-- MODAL --}}
                            <div x-show="modal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm">
                                <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-sm p-12 text-center">
                                    <h3 class="text-2xl font-black text-slate-900 italic uppercase mb-4 leading-none">¿Confirmar Cierre?</h3>
                                    <p class="text-slate-400 text-xs font-bold uppercase italic mb-10">Se bloquearán los folios y se debe retirar el fondo de reserva de ${{ number_format($cajaAbierta->monto_inicial, 2) }}.</p>
                                    <div class="flex gap-4">
                                        <button @click="modal = false" type="button" class="flex-1 bg-slate-100 text-slate-400 font-black py-4 rounded-xl text-[10px] uppercase italic">Cancelar</button>
                                        <button type="button" @click="document.getElementById('formCerrar').submit()" class="flex-1 bg-red-600 text-white font-black py-4 rounded-xl text-[10px] uppercase italic shadow-lg shadow-red-200">Confirmar</button>
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