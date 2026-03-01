@extends('layouts.app')

@section('content')

@if(!$cajaAbierta)
    <div class="w-full flex flex-col items-center justify-center min-h-[70vh]">
        <div class="bg-red-50 border border-red-200 rounded-xl p-8 text-center max-w-lg">
            <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-red-800 mb-2">¡Caja Cerrada!</h3>
            <p class="text-red-600 mb-6">Para empezar a vender, necesitas abrir el turno de caja primero.</p>
            <a href="{{ route('flujo.caja.index') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg shadow-sm transition-colors inline-block">
                Ir a Abrir Caja
            </a>
        </div>
    </div>
@else

    <div class="w-full flex flex-col lg:flex-row gap-6 min-h-[85vh] bg-[#f8f9fa] p-4" 
         x-data="puntoDeVenta({{ json_encode($catalogo) }}, {{ json_encode($clientes) }}, {{ json_encode($direcciones) }}, {{ json_encode($paquetes) }})">
        
        <div class="w-full lg:w-[68%] flex flex-col">
            
            <div class="flex flex-wrap gap-3 mb-5">
                @foreach($paquetes as $paq)
                    <button @click="abrirModalPaquete({{ $paq->id_paquete }}, '{{ addslashes($paq->nombre) }}', '{{ addslashes($paq->descripcion) }}', {{ $paq->precio }})" class="bg-[#ffc107] hover:bg-yellow-500 text-white font-bold py-2 px-5 rounded-md text-sm shadow-sm transition-colors">{{ $paq->nombre }}</button>
                @endforeach
                <button @click="modalIngredientes = true" class="bg-[#f97316] hover:bg-orange-600 text-white font-bold py-2 px-5 rounded-md text-sm shadow-sm transition-colors">Por Ingrediente</button>
                <button @click="modalMitades = true" class="bg-[#ef4444] hover:bg-red-600 text-white font-bold py-2 px-5 rounded-md text-sm shadow-sm transition-colors">Mitad y Mitad</button>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex-1 flex flex-col overflow-hidden p-4">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-3">
                    <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto scrollbar-hide bg-gray-100 p-1.5 rounded-lg border border-gray-200">
                        <button @click="seleccionarCategoria(12)" :class="categoriaActiva === 12 ? 'bg-[#ff8c00] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-5 py-1.5 rounded-md text-sm font-bold transition-colors">Pizzas</button>
                        <button @click="seleccionarCategoria(2)" :class="categoriaActiva === 2 ? 'bg-[#ff8c00] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-5 py-1.5 rounded-md text-sm font-bold transition-colors">Mariscos</button>
                        <button @click="seleccionarCategoria(11)" :class="categoriaActiva === 11 ? 'bg-[#ff8c00] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-5 py-1.5 rounded-md text-sm font-bold transition-colors">Rectangular</button>
                        <button @click="seleccionarCategoria(10)" :class="categoriaActiva === 10 ? 'bg-[#ff8c00] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-5 py-1.5 rounded-md text-sm font-bold transition-colors">Barra</button>
                        
                        <button @click="mostrarExtras = !mostrarExtras" :class="mostrarExtras || [1,6,5,7,8,9].includes(categoriaActiva) ? 'bg-gray-300 text-gray-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-md text-sm font-bold transition-colors flex items-center gap-1">
                            Extras 
                            <svg class="w-4 h-4 transition-transform" :class="mostrarExtras ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>

                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></span>
                        <input type="text" x-model="busqueda" placeholder="Buscar producto." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-1 focus:ring-orange-500 focus:border-orange-500 outline-none">
                    </div>
                </div>

                <div x-show="mostrarExtras" x-collapse class="mb-4">
                    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide bg-gray-100 p-1.5 rounded-lg border border-gray-200 inline-flex">
                        <button @click="seleccionarCategoria(1)" :class="categoriaActiva === 1 ? 'bg-gray-300 text-gray-800 shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-md text-sm font-bold transition-colors">Bebidas</button>
                        <button @click="seleccionarCategoria(6)" :class="categoriaActiva === 6 ? 'bg-gray-300 text-gray-800 shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-md text-sm font-bold transition-colors">Hamburguesas</button>
                        <button @click="seleccionarCategoria(5)" :class="categoriaActiva === 5 ? 'bg-gray-300 text-gray-800 shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-md text-sm font-bold transition-colors">Alitas</button>
                        <button @click="seleccionarCategoria(7)" :class="categoriaActiva === 7 ? 'bg-gray-300 text-gray-800 shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-md text-sm font-bold transition-colors">Costillas</button>
                        <button @click="seleccionarCategoria(9)" :class="categoriaActiva === 9 ? 'bg-gray-300 text-gray-800 shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-md text-sm font-bold transition-colors">Spaguetty</button>
                        <button @click="seleccionarCategoria(8)" :class="categoriaActiva === 8 ? 'bg-gray-300 text-gray-800 shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-4 py-1.5 rounded-md text-sm font-bold transition-colors">Papas</button>
                    </div>
                </div>

                <div class="overflow-y-auto max-h-[60vh] flex-1 pt-2">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template x-for="prod in productosFiltrados()" :key="prod.id_unico">
                            <button @click="agregarAlCarrito(prod)" class="bg-white border border-gray-200 border-l-[6px] border-l-[#ffc107] rounded-xl p-4 text-left hover:shadow-md transition-all flex flex-col h-32 group">
                                <h4 class="font-bold text-[#0f172a] text-[15px] leading-tight mb-2" x-text="prod.nombre"></h4>
                                <div class="mt-auto flex justify-between items-end w-full">
                                    <span class="text-[#ff8c00] text-xs font-bold group-hover:underline">Ver opciones &rarr;</span>
                                    <span class="text-gray-400 text-xs font-bold" x-text="'$' + parseFloat(prod.precio).toFixed(2)"></span>
                                </div>
                            </button>
                        </template>
                    </div>
                    <div x-show="productosFiltrados().length === 0" class="text-center py-10 text-gray-400 text-sm font-medium">
                        No se encontraron productos.
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[32%] bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col overflow-hidden relative">
            <div class="p-5 border-b border-gray-200">
                <h2 class="text-xl font-black text-gray-900">Productos</h2>
                <p class="text-sm text-gray-500" x-show="carrito.length === 0">Sin productos seleccionados</p>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50">
                <template x-for="(item, index) in carrito" :key="index">
                    <div class="bg-gray-50 border border-gray-200 p-3 rounded-lg relative shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-[13px] font-bold text-gray-800 pr-6 leading-tight" x-text="item.nombre"></h4>
                            <button @click="eliminarDelCarrito(index)" class="text-red-500 hover:text-red-700 absolute top-3 right-3 text-xs font-black">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 448 512"><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg>
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-3">
                            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded p-1">
                                <button @click="item.cantidad > 1 ? item.cantidad-- : null" class="w-6 h-6 rounded text-gray-600 font-bold hover:bg-gray-200">-</button>
                                <span class="text-xs font-bold w-4 text-center" x-text="item.cantidad"></span>
                                <button @click="item.cantidad++" class="w-6 h-6 rounded text-gray-600 font-bold hover:bg-gray-200">+</button>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-500">| Precio c/u: <span x-text="'$'+parseFloat(item.precio).toFixed(2)"></span></p>
                                <span class="text-[14px] font-black text-gray-900" x-text="'Subtotal: $' + (item.precio * item.cantidad).toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="p-4 border-t border-gray-200 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-bold text-gray-600">Total:</span>
                    <span class="text-2xl font-black text-gray-900" x-text="'$' + calcularTotal().toFixed(2)"></span>
                </div>

                <div class="space-y-3 mb-4" x-show="tipoServicio === 1">
                    <input type="text" x-model="numeroMesa" placeholder="Número de mesa *" class="w-full border border-gray-300 rounded px-3 py-2 text-sm outline-none focus:border-orange-500">
                </div>
                <div class="space-y-3 mb-4" x-show="[2,3,4].includes(tipoServicio)">
                    <input type="text" x-model="nombreCliente" placeholder="Nombre del cliente *" class="w-full border border-gray-300 rounded px-3 py-2 text-sm outline-none focus:border-orange-500">
                </div>

                <button @click="modalComentarios = true" class="w-full bg-[#f1f5f9] hover:bg-[#e2e8f0] text-gray-700 font-medium py-2 rounded text-sm mb-3 transition-colors border border-gray-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7z"/></svg>
                    Agregar comentarios
                </button>

                <div class="flex rounded overflow-hidden shadow-sm" x-data="{ openTipo: false }">
                    <div class="relative w-1/3">
                        <button @click="openTipo = !openTipo" class="w-full h-full bg-[#ff7b00] hover:bg-[#e66a00] text-white font-bold text-[11px] flex items-center justify-center gap-1 transition-colors px-1 border-r border-[#e66a00]">
                            <span x-text="tipoServicio === 3 ? 'A Domicilio' : (tipoServicio === 1 ? 'Comer Aqui' : (tipoServicio === 2 ? 'Para Llevar' : 'P. Especial'))"></span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="openTipo" @click.away="openTipo = false" x-cloak class="absolute bottom-full left-0 mb-1 w-40 bg-[#fff7ed] border border-[#fed7aa] rounded shadow-lg z-50 py-1">
                            <button @click="tipoServicio = 3; openTipo = false" class="w-full text-left px-4 py-2.5 text-sm text-orange-800 hover:bg-orange-100 font-medium">A Domicilio</button>
                            <button @click="tipoServicio = 1; openTipo = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-100 font-medium">Comer Aqui</button>
                            <button @click="tipoServicio = 2; openTipo = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-100 font-medium">Para Llevar</button>
                            <button @click="tipoServicio = 4; openTipo = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-100 font-medium">P. Especial</button>
                        </div>
                    </div>
                    <button type="button" @click="iniciarCheckout()" :disabled="carrito.length === 0" :class="carrito.length === 0 ? 'bg-gray-300 text-gray-500' : 'bg-[#ff8c00] hover:bg-[#e67e00] text-white'" class="w-2/3 font-bold py-3.5 text-[15px] transition-colors">
                        Enviar Orden
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('ventas.modales_pos')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('puntoDeVenta', (catalogoInicial, clientesDB, direccionesDB, paquetesDB) => ({
                catalogo: catalogoInicial, 
                clientes: clientesDB, 
                direcciones: direccionesDB,
                paquetesDB: paquetesDB,
                
                categoriaActiva: 12, // 12 = Pizzas por defecto
                mostrarExtras: false,
                busqueda: '', 
                carrito: [],
                
                tipoServicio: 3, // 3 = Domicilio
                numeroMesa: '', nombreCliente: '', comentarios: '',
                
                modalComentarios: false, modalDireccion: false, modalPago: false,
                
                // Modales de Productos Especiales
                modalPaquete: false, paqueteSeleccionado: null,
                modalMitades: false, 
                modalIngredientes: false,

                id_clie: '', id_dir: '',
                pagoEf: true, montoEf: 0, pagoTa: false, montoTa: 0, pagoTr: false, montoTr: 0, refTr: '',

                seleccionarCategoria(id) {
                    this.categoriaActiva = id;
                    // Si selecciona algo que no es extra, ocultamos la barra de extras
                    if (![1,5,6,7,8,9].includes(id)) {
                        this.mostrarExtras = false;
                    }
                },

                productosFiltrados() {
                    return this.catalogo.filter(p => {
                        const coincideCat = p.id_cat === this.categoriaActiva;
                        const coincideBusqueda = p.nombre.toLowerCase().includes(this.busqueda.toLowerCase());
                        return coincideCat && coincideBusqueda;
                    });
                },

                agregarAlCarrito(producto) {
                    let index = this.carrito.findIndex(item => item.id_unico === producto.id_unico);
                    if (index > -1) { this.carrito[index].cantidad++; } else { this.carrito.push({ ...producto, cantidad: 1 }); }
                },

                abrirModalPaquete(id, nombre, descripcion, precio) {
                    this.paqueteSeleccionado = { id: id, nombre: nombre, descripcion: descripcion, precio: parseFloat(precio) };
                    this.modalPaquete = true;
                },

                eliminarDelCarrito(index) { this.carrito.splice(index, 1); },
                calcularTotal() { return this.carrito.reduce((total, item) => total + (item.precio * item.cantidad), 0); },

                iniciarCheckout() {
                    if(this.tipoServicio === 1 && this.numeroMesa === '') { alert("Por favor ingresa la Mesa."); return; }
                    if([2,3,4].includes(this.tipoServicio) && this.nombreCliente === '') { alert("Por favor ingresa el Nombre del Cliente."); return; }
                    
                    if(this.tipoServicio === 3 && (this.id_clie === '' || this.id_dir === '')) {
                        this.modalDireccion = true; return;
                    }
                    
                    this.montoEf = this.calcularTotal(); this.pagoEf = true;
                    this.montoTa = 0; this.pagoTa = false;
                    this.montoTr = 0; this.pagoTr = false;
                    this.modalPago = true;
                },
                confirmarDireccion() { this.modalDireccion = false; this.iniciarCheckout(); },
                validaPagos() {
                    let sum = (this.pagoEf ? parseFloat(this.montoEf)||0 : 0) + (this.pagoTa ? parseFloat(this.montoTa)||0 : 0) + (this.pagoTr ? parseFloat(this.montoTr)||0 : 0);
                    return Math.abs(sum - this.calcularTotal()) < 0.01;
                },
                procesarVentaFinal() {
                    let pagos = [];
                    if(this.pagoEf) pagos.push({ id_metpago: 2, monto: this.montoEf });
                    if(this.pagoTa) pagos.push({ id_metpago: 1, monto: this.montoTa });
                    if(this.pagoTr) pagos.push({ id_metpago: 3, monto: this.montoTr, referencia: this.refTr });

                    fetch("{{ route('ventas.pos.store') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ _token: '{{ csrf_token() }}', tipo_servicio: this.tipoServicio, mesa: this.numeroMesa, nombre_cliente: this.nombreCliente, comentarios: this.comentarios, total: this.calcularTotal(), carrito: this.carrito, pagos: pagos, id_clie: this.id_clie, id_dir: this.id_dir })
                    }).then(r => r.json()).then(res => {
                        if(res.success) {
                            this.modalPago = false; this.carrito = []; this.numeroMesa = ''; this.nombreCliente = '';
                            window.open('/venta/pos/ticket/' + res.id_venta, 'Ticket', 'width=400,height=600');
                        } else { alert("Error: " + res.message); }
                    });
                }
            }));
        });
    </script>
@endif
@endsection