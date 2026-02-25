@extends('layouts.app')

@section('content')
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-20px]" x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-[-20px]"
     class="fixed top-6 right-6 z-50 bg-[#00b300] text-white px-5 py-3.5 rounded shadow-lg flex items-center gap-3">
    <div class="bg-white rounded-full p-0.5"><svg class="w-4 h-4 text-[#00b300]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg></div>
    <span class="font-medium text-[15px]">{{ session('success') }}</span>
</div>
@endif

<div class="w-full bg-[#f4f4f4] min-h-[80vh] p-4">

    <div class="flex border border-gray-200 rounded-lg overflow-hidden mb-6 bg-[#fef6f6] shadow-sm min-h-[200px]">
        <div class="w-[50px] bg-[#f8efef] border-r border-gray-200 flex flex-col items-center py-6 text-[#475569] font-black text-[13px] uppercase shrink-0">
            <span class="w-2.5 h-2.5 rounded-full bg-gray-400 mb-4 shadow-sm"></span>
            <span class="leading-tight">P</span>
            <span class="leading-tight">r</span>
            <span class="leading-tight">e</span>
            <span class="leading-tight">p</span>
            <span class="leading-tight">a</span>
            <span class="leading-tight">r</span>
            <span class="leading-tight">a</span>
            <span class="leading-tight">n</span>
            <span class="leading-tight">d</span>
            <span class="leading-tight">o</span>
        </div>
        
        <div class="flex-1 p-6">
            @if($pedidosEspera->isEmpty())
                <div class="h-full flex items-center justify-center">
                    <p class="text-gray-500 text-sm">No hay pedidos en espera</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($pedidosEspera as $pedido)
                        <div class="bg-white border border-gray-200 p-4 rounded-lg shadow-sm relative border-t-4 border-t-gray-400">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-black text-gray-800 text-lg">Pedido #{{ $pedido->id_venta }}</h4>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($pedido->fecha_hora)->format('h:i A') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 font-medium"><span class="text-gray-400">Cliente/Mesa:</span> {{ $pedido->nombreClie ?? 'Mesa '.$pedido->mesa }}</p>
                            
                            <form action="{{ route('ventas.pedidos.status', $pedido->id_venta) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-2 rounded transition-colors">
                                    Iniciar Preparación
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="flex border border-[#fef08a] rounded-lg overflow-hidden bg-[#fffdf0] shadow-sm min-h-[200px]">
        <div class="w-[50px] bg-[#fef9c3] border-r border-[#fef08a] flex flex-col items-center py-6 text-[#ca8a04] font-black text-[13px] uppercase shrink-0">
            <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 mb-4 shadow-sm"></span>
            <span class="leading-tight">E</span>
            <span class="leading-tight">n</span>
            <span class="leading-tight">t</span>
            <span class="leading-tight">r</span>
            <span class="leading-tight">e</span>
            <span class="leading-tight">g</span>
            <span class="leading-tight">a</span>
            <span class="leading-tight">d</span>
            <span class="leading-tight">o</span>
        </div>
        
        <div class="flex-1 p-6">
            @if($pedidosPreparando->isEmpty())
                <div class="h-full flex items-center justify-center">
                    <p class="text-gray-500 text-sm">No hay pedidos en preparación</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($pedidosPreparando as $pedido)
                        <div class="bg-white border border-[#fef08a] p-4 rounded-lg shadow-sm relative border-t-4 border-t-yellow-400">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-black text-gray-800 text-lg">Pedido #{{ $pedido->id_venta }}</h4>
                                <span class="text-xs text-yellow-600 bg-yellow-50 px-2 py-1 rounded font-bold">{{ \Carbon\Carbon::parse($pedido->fecha_hora)->format('h:i A') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 font-medium"><span class="text-gray-400">Cliente/Mesa:</span> {{ $pedido->nombreClie ?? 'Mesa '.$pedido->mesa }}</p>
                            
                            <form action="{{ route('ventas.pedidos.status', $pedido->id_venta) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 rounded transition-colors flex justify-center items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg> Marcar Listo / Entregar
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection