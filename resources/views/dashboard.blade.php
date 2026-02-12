@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-14">
        <h2 class="text-5xl font-black text-gray-900 italic tracking-tighter uppercase mb-2 leading-none">Panel de Control</h2>
        <div class="h-1.5 w-32 bg-amber-400 rounded-full"></div>
        <p class="text-gray-400 font-bold text-lg mt-4 uppercase tracking-[0.3em] text-sm italic">Estado de la sucursal</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-14">
        <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
            <div class="flex items-center justify-between mb-8">
                <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center group-hover:bg-amber-400 group-hover:text-black transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase text-gray-300 tracking-[0.3em]">Hoy</span>
            </div>
            <span class="text-gray-400 font-black text-[10px] uppercase tracking-[0.3em]">Ventas del día</span>
            <h3 class="text-4xl font-black text-gray-900 mt-2 tracking-tighter italic leading-none">$0.00</h3>
        </div>

        <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
            <div class="flex items-center justify-between mb-8">
                <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center group-hover:bg-amber-400 group-hover:text-black transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase text-gray-300 tracking-[0.3em]">Estado</span>
            </div>
            <span class="text-gray-400 font-black text-[10px] uppercase tracking-[0.3em]">Pedidos totales</span>
            <h3 class="text-4xl font-black text-gray-900 mt-2 tracking-tighter italic leading-none">0</h3>
        </div>

        <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
            <div class="flex items-center justify-between mb-8">
                <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center group-hover:bg-amber-400 group-hover:text-black transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase text-gray-300 tracking-[0.3em]">Staff</span>
            </div>
            <span class="text-gray-400 font-black text-[10px] uppercase tracking-[0.3em]">Colaboradores</span>
            <h3 class="text-4xl font-black text-gray-900 mt-2 tracking-tighter italic leading-none">{{ \App\Models\User::count() }}</h3>
        </div>
    </div>

    <div class="bg-white p-12 rounded-[3.5rem] border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-10">
            <h4 class="text-2xl font-black text-gray-800 italic uppercase tracking-tighter">Actividad Reciente</h4>
            <div class="h-1.5 w-24 bg-amber-400 rounded-full"></div>
        </div>
        
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-24 h-24 bg-amber-50 rounded-[2rem] flex items-center justify-center mb-6 border border-amber-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <p class="text-gray-400 font-black uppercase text-[10px] tracking-[0.4em]">No hay registros por el momento</p>
        </div>
    </div>
</div>
@endsection