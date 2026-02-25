@extends('layouts.app')

@section('content')
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-20px]" x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-20px]"
     class="fixed top-6 right-6 z-50 bg-[#00b300] text-white px-5 py-3.5 rounded shadow-lg flex items-center gap-3">
    <div class="bg-white rounded-full p-0.5">
        <svg class="w-4 h-4 text-[#00b300]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
    </div>
    <span class="font-medium text-[15px]">{{ session('success') }}</span>
</div>
@endif

<div x-data="{ mostrarModalCerrar: false }" class="w-full flex flex-col items-center justify-center min-h-[70vh]">
    
    <h2 class="text-[28px] font-bold text-[#1e293b] tracking-tight mb-8">Gestión de Caja</h2>

    @if(!$cajaAbierta)
        <div class="bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 p-8 w-full max-w-md">
            <h3 class="text-xl font-bold text-[#1e293b] mb-6">Apertura de Caja</h3>

            <form action="{{ route('flujo.caja.abrir') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm text-[#475569] mb-1.5">Monto Inicial <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="monto_inicial" required placeholder="0.00" class="w-full border border-gray-300 rounded-md px-3.5 py-2.5 text-[#334155] focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors">
                </div>

                <div>
                    <label class="block text-sm text-[#475569] mb-1.5">Observaciones</label>
                    <input type="text" name="observaciones" placeholder="Opcional" class="w-full border border-gray-300 rounded-md px-3.5 py-2.5 text-[#334155] focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-colors">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#eab308] hover:bg-[#ca8a04] text-white font-semibold py-3 rounded-md transition-colors text-[15px]">
                        Abrir Caja
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-gray-100 p-8 w-full max-w-md text-center border-t-4 border-t-green-500">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-[#1e293b] mb-2">Caja Abierta</h3>
            <p class="text-[#64748b] text-sm mb-4">Ya existe una caja operando en este momento.</p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left border">
                <p class="text-sm text-gray-500 mb-1">Monto Inicial:</p>
                <p class="text-lg font-black text-green-600 mb-3">${{ number_format($cajaAbierta->monto_inicial, 2) }}</p>
                
                <p class="text-sm text-gray-500 mb-1">Fecha de Apertura:</p>
                <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($cajaAbierta->fecha_apertura)->format('d/m/Y h:i A') }}</p>
            </div>

            <button type="button" @click="mostrarModalCerrar = true" class="w-full bg-[#991b1b] hover:bg-[#7f1d1d] text-white font-semibold py-3 rounded-md transition-colors text-[15px]">
                Cerrar Caja
            </button>
        </div>

        <div x-show="mostrarModalCerrar" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-transition.opacity>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 overflow-hidden text-center" @click.away="mostrarModalCerrar = false" x-transition.scale.origin.bottom>
                <div class="w-16 h-16 rounded-full bg-red-50 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2 tracking-tight">¿Cerrar la Caja?</h3>
                <p class="text-gray-500 text-sm mb-6 mt-2">¿Estás seguro de que deseas hacer el corte y cerrar la caja actual?</p>
                <div class="flex gap-3 mt-6">
                    <button @click="mostrarModalCerrar = false" type="button" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition-colors text-sm">Cancelar</button>
                    <form action="{{ route('flujo.caja.cerrar', $cajaAbierta->id_caja) }}" method="POST" class="flex-1">
                        @csrf @method('POST')
                        <button type="submit" class="w-full bg-[#991b1b] hover:bg-[#7f1d1d] text-white font-bold py-3 rounded-xl transition-colors text-sm shadow-sm">Sí, Cerrar</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection