@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-10">
    
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-slate-100 pb-8">
        <div>
            <h1 class="text-6xl font-black text-slate-900 italic tracking-tighter uppercase leading-none">
                RESUMEN <span class="text-amber-400 text-7xl">.</span>
            </h1>
            <p class="text-slate-400 font-bold uppercase tracking-[0.4em] text-[10px] italic mt-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Operación en vivo: {{ now()->format('d M, Y | h:i A') }}
            </p>
        </div>
        <div class="flex gap-4">
            <div class="text-right">
                <span class="block text-[10px] font-black text-slate-400 uppercase italic">Tickets de hoy</span>
                <span class="text-3xl font-black text-slate-900 italic">{{ $numVentas }}</span>
            </div>
        </div>
    </div>

    {{-- GRID DE MÉTRICAS PRINCIPALES --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        {{-- CARD: CAJA REAL --}}
        <div class="bg-gradient-to-br from-white to-slate-50 p-10 rounded-[3.5rem] border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-2xl transition-all duration-500">
            <div class="absolute -right-4 -top-4 opacity-[0.03] group-hover:rotate-12 transition-transform duration-700">
                <svg class="w-40 h-40 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
            </div>
            <span class="text-[10px] font-black uppercase text-slate-400 italic tracking-widest block mb-4">Efectivo Real en Caja</span>
            <h2 class="text-6xl font-black text-slate-900 italic tracking-tighter leading-none">
                ${{ number_format($efectivoCaja, 2) }}
            </h2>
            <div class="mt-8 flex items-center gap-2">
                <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[9px] font-black uppercase italic">Ventas - Gastos</span>
            </div>
        </div>

        {{-- CARD: VENTAS TOTALES --}}
        <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
            <span class="text-[10px] font-black uppercase text-slate-400 italic tracking-widest block mb-4">Venta Total Bruta</span>
            <h2 class="text-6xl font-black text-slate-900 italic tracking-tighter leading-none">
                ${{ number_format($ventasHoy, 2) }}
            </h2>
            <div class="mt-8 h-1.5 w-full bg-slate-50 rounded-full overflow-hidden">
                <div class="h-full bg-amber-400 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        {{-- CARD: GASTOS --}}
        <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500">
            <span class="text-[10px] font-black uppercase text-red-400 italic tracking-widest block mb-4 text-nowrap">Salidas de Dinero</span>
            <h2 class="text-6xl font-black text-red-500 italic tracking-tighter leading-none">
                -${{ number_format($gastosHoy, 2) }}
            </h2>
            <p class="text-[9px] font-bold text-slate-300 mt-8 uppercase italic tracking-widest">Total de gastos registrados hoy</p>
        </div>
    </div>

    {{-- SECCIÓN: MÉTODOS DE PAGO (ESTILO CARDS FLUJO DE VENTA) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card Efectivo --}}
        <div class="bg-emerald-500 p-8 rounded-[2.5rem] text-white shadow-lg shadow-emerald-100 relative overflow-hidden">
            <div class="absolute right-0 bottom-0 opacity-20 translate-y-4 translate-x-4">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 1.13-1.12 1.92-2.8 1.92-1.98 0-2.85-.93-3-2.3H4.21c.15 2.11 1.74 3.41 3.79 3.91V21h3v-2.15c2.02-.42 3.51-1.6 3.51-3.66 0-2.35-1.9-3.61-4.71-4.29z"/></svg>
            </div>
            <span class="text-[9px] font-black uppercase italic tracking-widest opacity-80">Cobros en Efectivo</span>
            <h3 class="text-4xl font-black italic mt-2">${{ number_format($efectivoVentas, 2) }}</h3>
        </div>

        {{-- Card Tarjeta --}}
        <div class="bg-blue-600 p-8 rounded-[2.5rem] text-white shadow-lg shadow-blue-100 relative overflow-hidden">
            <div class="absolute right-0 bottom-0 opacity-20 translate-y-4 translate-x-4">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
            </div>
            <span class="text-[9px] font-black uppercase italic tracking-widest opacity-80">Cobros con Tarjeta</span>
            <h3 class="text-4xl font-black italic mt-2">${{ number_format($tarjetasHoy, 2) }}</h3>
        </div>

        {{-- Card Transferencia --}}
        <div class="bg-purple-600 p-8 rounded-[2.5rem] text-white shadow-lg shadow-purple-100 relative overflow-hidden">
            <div class="absolute right-0 bottom-0 opacity-20 translate-y-4 translate-x-4">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V9h2v7zm4 0h-2V7h2v9z"/></svg>
            </div>
            <span class="text-[9px] font-black uppercase italic tracking-widest opacity-80">Cobros Transferencia</span>
            <h3 class="text-4xl font-black italic mt-2">${{ number_format($transferenciasHoy, 2) }}</h3>
        </div>
    </div>
</div>
@endsection