<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzetos - Sistema</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="bg-[#f4f4f4] font-sans antialiased overflow-x-hidden">

    <div x-show="sidebarOpen" 
         x-cloak
         x-transition:opacity
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-black/40 z-40 backdrop-blur-sm">
    </div>

    <div class="min-h-screen flex flex-col">
        
        <aside 
            x-show="sidebarOpen"
            x-cloak
            x-transition:enter="sidebar-transition -translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="sidebar-transition translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="w-80 bg-amber-400 text-black flex flex-col fixed h-full z-50 shadow-2xl overflow-y-auto">
            
            <div class="p-10 text-center border-b border-black/10 relative shrink-0">
                <button @click="sidebarOpen = false" class="absolute top-4 right-4 p-2 hover:bg-black/10 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="bg-white p-3 rounded-full shadow-xl inline-block mb-4">
                    <img src="{{ asset('pizzetos.png') }}" alt="Logo" class="h-16 w-16 object-contain">
                </div>
                <h1 class="text-2xl font-black italic tracking-tighter uppercase leading-none">Pizzetos</h1>
            </div>

            <nav class="flex-1 px-8 py-10 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-6 py-5 rounded-[2rem] transition-all {{ request()->routeIs('dashboard') ? 'bg-black text-white shadow-xl' : 'hover:bg-black/10 font-black' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512"><path d="M256 48L32 240h64v192h96V304h128v128h96V240h64L256 48z"/></svg>
                    <span class="text-xs uppercase tracking-[0.3em] font-black italic">Inicio</span>
                </a>
                
                <a href="{{ route('empleados.index') }}" class="flex items-center gap-4 px-6 py-5 rounded-[2rem] transition-all {{ request()->routeIs('empleados.*') ? 'bg-black text-white shadow-xl' : 'hover:bg-black/10 font-black' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512"><path d="M256 288c70.7 0 128-57.3 128-128S326.7 32 256 32 128 89.3 128 160s57.3 128 128 128zm-101.8 32H136c-75.1 0-136 60.9-136 136v40c0 8.8 7.2 16 16 16h480c8.8 0 16-7.2 16-16v-40c0-75.1-60.9-136-136-136h-18.2c-35 28.1-79 45.4-125.8 45.4s-90.8-17.3-125.8-45.4z"/></svg>
                    <span class="text-xs uppercase tracking-[0.3em] font-black italic">Empleados</span>
                </a>

                <a href="{{ route('corte.index') }}" class="flex items-center gap-4 px-6 py-5 rounded-[2rem] transition-all {{ request()->routeIs('corte.*') ? 'bg-black text-white shadow-xl' : 'hover:bg-black/10 font-black' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512"><path d="M384 256H128V208h256v48zm32-80v112H96V176h320zM208 0H112C85.5 0 64 21.5 64 48v80H32c-17.7 0-32 14.3-32 32v128c0 17.7 14.3 32 32 32h16l24 160c1.5 9.9 10 17.3 20 17.3h32c11 0 20-9 20-20V320h224v157.3c0 11 9 20 20 20h32c10 0 18.5-7.4 20-17.3l24-160h16c17.7 0 32-14.3 32-32V160c0-17.7-14.3-32-32-32h-32V48c0-26.5-21.5-48-48-48zM192 64h128v64H192V64z"/></svg>
                    <span class="text-xs uppercase tracking-[0.3em] font-black italic">Corte Mensual</span>
                </a>

                <div x-data="{ productosOpen: {{ request()->is('productos/*') ? 'true' : 'false' }} }" class="w-full">
                    <button @click="productosOpen = !productosOpen" 
                            class="w-full flex items-center justify-between px-6 py-5 rounded-[2rem] transition-all hover:bg-black/10 font-black">
                        <div class="flex items-center gap-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 416 512"><path d="M207.9 15.2c.8 4.7 16.1 94.5 16.1 128.8 0 52.3-27.8 89.6-68.9 104.6L168 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l12.9-231.4c-40.9-15-68.9-52.3-68.9-104.6 0-34.1 15.6-124.3 16.1-128.8 1.4-8.1 8.5-14.2 16.8-14.2h109.1c8.3 0 15.4 6.1 16.8 14.2zm204.6 114.6c-5.8-3.4-12.9-3.3-18.7 .3-47.5 29.7-77.9 80.4-77.9 136v49.9h-31.9c-17.7 0-32 14.3-32 32s14.3 32 32 32h31.9V480c0 17.7 14.3 32 32 32s32-14.3 32-32V320h2.7c17.7 0 32-14.3 32-32s-14.3-32-32-32h-2.7v-49.9c0-37.4 18-72.3 48.7-93.7 6.1-4.3 8.3-12.5 4.8-18.6z"/></svg>
                            <span class="text-xs uppercase tracking-[0.3em] font-black italic">Productos</span>
                        </div>
                        <svg :class="{'rotate-180': productosOpen}" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="productosOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-4"
                         x-cloak 
                         class="mt-2 px-2 flex flex-col space-y-1 pb-4">
                        
                        <a href="{{ route('pizzas.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('pizzas.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M290.7 311L95 269.7 86.8 309l195.7 41.3c4.1-13.3 8-26.3 8.2-39.3zm211-132.3L275.5 28.5C266.3 10.1 245.8 1 224.2 1H214.5c-20 0-38.6 10.9-48.4 28.5l-153 273.6c-9.1 16.3-10.7 35.8-4.4 53.4C14.1 372 26 385 41.7 392l253.3 113.3c15 6.7 32 8 47.7 4 15.7-4 29-13.6 38.3-27L510.5 220.5c10.4-15 13-33.8 7-50.5-6-16.7-18.4-30-34.8-37.3zM151 228c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm84-76c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm88 76c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('pizzas.*') ? 'font-black' : 'font-bold' }} italic">Pizzas</span>
                        </a>
                        <a href="{{ route('alitas.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('alitas.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M441 58.9A105.7 105.7 0 0 0 291.6 59l-190.5 190.5-47.5-47.5C39.4 187.8 16 195 16 215.1c0 8.4 3.4 16.5 9.4 22.4l50.2 50.2c-15.6 18.3-25 41.5-25 66.8 0 57.4 46.6 104 104 104 25.4 0 48.6-9.5 66.8-25l50.2 50.2c5.9 6 14 9.4 22.4 9.4 20 0 27.2-23.4 13-37.6l-47.5-47.5L441 208.5c41.4-41.5 41.4-108.3 0-149.6z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('alitas.*') ? 'font-black' : 'font-bold' }} italic">Alitas</span>
                        </a>
                        <a href="{{ route('costillas.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('costillas.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M441 58.9A105.7 105.7 0 0 0 291.6 59l-190.5 190.5-47.5-47.5C39.4 187.8 16 195 16 215.1c0 8.4 3.4 16.5 9.4 22.4l50.2 50.2c-15.6 18.3-25 41.5-25 66.8 0 57.4 46.6 104 104 104 25.4 0 48.6-9.5 66.8-25l50.2 50.2c5.9 6 14 9.4 22.4 9.4 20 0 27.2-23.4 13-37.6l-47.5-47.5L441 208.5c41.4-41.5 41.4-108.3 0-149.6z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('costillas.*') ? 'font-black' : 'font-bold' }} italic">Costillas</span>
                        </a>
                        <a href="{{ route('hamburguesas.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('hamburguesas.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M464 256H48a48 48 0 0 0 0 96h416a48 48 0 0 0 0-96zm16 128H32a16 16 0 0 0-16 16v16a64 64 0 0 0 64 64h352a64 64 0 0 0 64-64v-16a16 16 0 0 0-16-16zM58.6 224h394.8c34.6 0 54.6-43.9 34.8-75.9C448.9 84.4 358.5 32 256 32S63.1 84.4 23.8 148.1c-19.8 32 .2 75.9 34.8 75.9z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('hamburguesas.*') ? 'font-black' : 'font-bold' }} italic">Hamburguesas</span>
                        </a>
                        <a href="{{ route('magno.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('magno.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 416 512"><path d="M207.9 15.2c.8 4.7 16.1 94.5 16.1 128.8 0 52.3-27.8 89.6-68.9 104.6L168 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l12.9-231.4c-40.9-15-68.9-52.3-68.9-104.6 0-34.1 15.6-124.3 16.1-128.8 1.4-8.1 8.5-14.2 16.8-14.2h109.1c8.3 0 15.4 6.1 16.8 14.2zm204.6 114.6c-5.8-3.4-12.9-3.3-18.7 .3-47.5 29.7-77.9 80.4-77.9 136v49.9h-31.9c-17.7 0-32 14.3-32 32s14.3 32 32 32h31.9V480c0 17.7 14.3 32 32 32s32-14.3 32-32V320h2.7c17.7 0 32-14.3 32-32s-14.3-32-32-32h-2.7v-49.9c0-37.4 18-72.3 48.7-93.7 6.1-4.3 8.3-12.5 4.8-18.6z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('magno.*') ? 'font-black' : 'font-bold' }} italic">Magno</span>
                        </a>
                        <a href="{{ route('papas.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('papas.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 416 512"><path d="M207.9 15.2c.8 4.7 16.1 94.5 16.1 128.8 0 52.3-27.8 89.6-68.9 104.6L168 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l12.9-231.4c-40.9-15-68.9-52.3-68.9-104.6 0-34.1 15.6-124.3 16.1-128.8 1.4-8.1 8.5-14.2 16.8-14.2h109.1c8.3 0 15.4 6.1 16.8 14.2zm204.6 114.6c-5.8-3.4-12.9-3.3-18.7 .3-47.5 29.7-77.9 80.4-77.9 136v49.9h-31.9c-17.7 0-32 14.3-32 32s14.3 32 32 32h31.9V480c0 17.7 14.3 32 32 32s32-14.3 32-32V320h2.7c17.7 0 32-14.3 32-32s-14.3-32-32-32h-2.7v-49.9c0-37.4 18-72.3 48.7-93.7 6.1-4.3 8.3-12.5 4.8-18.6z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('papas.*') ? 'font-black' : 'font-bold' }} italic">Papas</span>
                        </a>
                        <a href="{{ route('mariscos.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('mariscos.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M495 186.2C449.6 142.9 388 128 388 128c-98.3 0-191 38.6-261.2 105.4L64 160C28.7 160 0 188.7 0 224v128c0 35.3 28.7 64 64 64l62.8-73.4C197 409.4 289.7 448 388 448c0 0 61.6-14.9 107-58.2C508.8 376.5 512 355.2 512 336v-96c0-19.2-3.2-40.5-17-53.8zM240 256c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('mariscos.*') ? 'font-black' : 'font-bold' }} italic">Mariscos</span>
                        </a>
                        <a href="{{ route('rectangular.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('rectangular.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 416 512"><path d="M207.9 15.2c.8 4.7 16.1 94.5 16.1 128.8 0 52.3-27.8 89.6-68.9 104.6L168 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l12.9-231.4c-40.9-15-68.9-52.3-68.9-104.6 0-34.1 15.6-124.3 16.1-128.8 1.4-8.1 8.5-14.2 16.8-14.2h109.1c8.3 0 15.4 6.1 16.8 14.2zm204.6 114.6c-5.8-3.4-12.9-3.3-18.7 .3-47.5 29.7-77.9 80.4-77.9 136v49.9h-31.9c-17.7 0-32 14.3-32 32s14.3 32 32 32h31.9V480c0 17.7 14.3 32 32 32s32-14.3 32-32V320h2.7c17.7 0 32-14.3 32-32s-14.3-32-32-32h-2.7v-49.9c0-37.4 18-72.3 48.7-93.7 6.1-4.3 8.3-12.5 4.8-18.6z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('rectangular.*') ? 'font-black' : 'font-bold' }} italic">Rectangular</span>
                        </a>
                        <a href="{{ route('refrescos.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('refrescos.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 384 512"><path d="M32 64h320c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96S14.3 64 32 64zm17.3 112H334.7l-24.1 273.4C308.3 475.2 286.6 496 260 496H124c-26.6 0-48.3-20.8-50.6-46.6L49.3 176z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('refrescos.*') ? 'font-black' : 'font-bold' }} italic">Refrescos</span>
                        </a>
                        <a href="{{ route('spaguetty.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('spaguetty.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 416 512"><path d="M207.9 15.2c.8 4.7 16.1 94.5 16.1 128.8 0 52.3-27.8 89.6-68.9 104.6L168 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l12.9-231.4c-40.9-15-68.9-52.3-68.9-104.6 0-34.1 15.6-124.3 16.1-128.8 1.4-8.1 8.5-14.2 16.8-14.2h109.1c8.3 0 15.4 6.1 16.8 14.2zm204.6 114.6c-5.8-3.4-12.9-3.3-18.7 .3-47.5 29.7-77.9 80.4-77.9 136v49.9h-31.9c-17.7 0-32 14.3-32 32s14.3 32 32 32h31.9V480c0 17.7 14.3 32 32 32s32-14.3 32-32V320h2.7c17.7 0 32-14.3 32-32s-14.3-32-32-32h-2.7v-49.9c0-37.4 18-72.3 48.7-93.7 6.1-4.3 8.3-12.5 4.8-18.6z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('spaguetty.*') ? 'font-black' : 'font-bold' }} italic">Spaguetty</span>
                        </a>
                        <a href="{{ route('especialidades.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('especialidades.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 416 512"><path d="M207.9 15.2c.8 4.7 16.1 94.5 16.1 128.8 0 52.3-27.8 89.6-68.9 104.6L168 480c0 17.7-14.3 32-32 32s-32-14.3-32-32l12.9-231.4c-40.9-15-68.9-52.3-68.9-104.6 0-34.1 15.6-124.3 16.1-128.8 1.4-8.1 8.5-14.2 16.8-14.2h109.1c8.3 0 15.4 6.1 16.8 14.2zm204.6 114.6c-5.8-3.4-12.9-3.3-18.7 .3-47.5 29.7-77.9 80.4-77.9 136v49.9h-31.9c-17.7 0-32 14.3-32 32s14.3 32 32 32h31.9V480c0 17.7 14.3 32 32 32s32-14.3 32-32V320h2.7c17.7 0 32-14.3 32-32s-14.3-32-32-32h-2.7v-49.9c0-37.4 18-72.3 48.7-93.7 6.1-4.3 8.3-12.5 4.8-18.6z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('especialidades.*') ? 'font-black' : 'font-bold' }} italic">Especialidad</span>
                        </a>
                        <a href="{{ route('barra.index') }}" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all {{ request()->routeIs('barra.*') ? 'bg-black text-amber-400 shadow-xl' : 'hover:bg-black/10 text-black' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 384 512"><path d="M32 64h320c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96S14.3 64 32 64zm17.3 112H334.7l-24.1 273.4C308.3 475.2 286.6 496 260 496H124c-26.6 0-48.3-20.8-50.6-46.6L49.3 176z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] {{ request()->routeIs('barra.*') ? 'font-black' : 'font-bold' }} italic">Barra</span>
                        </a>
                    </div>
                </div>

                <div x-data="{ recursosOpen: false }" class="w-full">
                    <button @click="recursosOpen = !recursosOpen" 
                            class="w-full flex items-center justify-between px-6 py-5 rounded-[2rem] transition-all hover:bg-black/10 font-black">
                        <div class="flex items-center gap-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512"><path d="M12.41 148.02l232.94 105.67c6.8 3.09 14.49 3.09 21.29 0l232.94-105.67c16.55-7.51 16.55-32.52 0-40.03L266.65 2.31a25.607 25.607 0 0 0-21.29 0L12.41 107.98c-16.55 7.51-16.55 32.53 0 40.04zm487.18 88.28l-58.09-26.33-161.64 73.27c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.51 209.97l-58.1 26.33c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 276.3c16.55-7.5 16.55-32.5 0-40zm0 127.8l-58.09-26.33-161.64 73.27c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.51 337.77l-58.1 26.33c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 404.1c16.55-7.5 16.55-32.5 0-40z"/></svg>
                            <span class="text-xs uppercase tracking-[0.3em] font-black italic">Recursos</span>
                        </div>
                        <svg :class="{'rotate-180': recursosOpen}" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="recursosOpen" x-transition x-cloak class="mt-2 px-2 flex flex-col space-y-1 pb-4">
                        <a href="#" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all hover:bg-black/10 text-black">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M160 16H32C14.3 16 0 30.3 0 48v128c0 17.7 14.3 32 32 32h128c17.7 0 32-14.3 32-32V48c0-17.7-14.3-32-32-32zm320 0H352c-17.7 0-32 14.3-32 32v128c0 17.7 14.3 32 32 32h128c17.7 0 32-14.3 32-32V48c0-17.7-14.3-32-32-32zM160 304H32c-17.7 0-32 14.3-32 32v128c0 17.7 14.3 32 32 32h128c17.7 0 32-14.3 32-32V336c0-17.7-14.3-32-32-32zm320 0H352c-17.7 0-32 14.3-32 32v128c0 17.7 14.3 32 32 32h128c17.7 0 32-14.3 32-32V336c0-17.7-14.3-32-32-32z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold italic">Categorias</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all hover:bg-black/10 text-black">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 384 512"><path d="M384 144c0-44.2-35.8-80-80-80s-80 35.8-80 80c0 36.4 24.3 67.1 57.5 76.8-.6 16.1-4.2 28.5-11 36.9-15.4 19.2-49.3 22.4-85.2 25.7-28.2 2.6-57.4 5.4-81.3 16.9v-144c32.5-10.2 56-40.5 56-76.3 0-44.2-35.8-80-80-80S0 35.8 0 80c0 35.8 23.5 66.1 56 76.3v199.3C23.5 365.9 0 396.2 0 432c0 44.2 35.8 80 80 80s80-35.8 80-80c0-34-21.2-63.1-51.2-74.6 3.1-5.2 7.8-9.8 14.9-13.4 16.2-8.2 40.4-10.4 66.1-12.8 42.2-3.9 90-8.4 118.2-43.4 14-17.4 21.1-39.8 21.6-67.9 31.6-10.8 54.4-41.2 54.4-75.9zM80 64c8.8 0 16 7.2 16 16s-7.2 16-16 16-16-7.2-16-16 7.2-16 16-16zm0 384c-8.8 0-16-7.2-16-16s7.2-16 16-16 16 7.2 16 16-7.2 16-16 16zm224-320c8.8 0 16 7.2 16 16s-7.2 16-16 16-16-7.2-16-16 7.2-16 16-16z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold italic">Sucursales</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all hover:bg-black/10 text-black">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M208 48v64c0 26.5 21.5 48 48 48h16v32H112c-26.5 0-48 21.5-48 48v32H32c-17.7 0-32 14.3-32 32v64c0 17.7 14.3 32 32 32h64c17.7 0 32-14.3 32-32v-64c0-17.7-14.3-32-32-32H64v-32c0-8.8 7.2-16 16-16h160v48c0 26.5 21.5 48 48 48h64c26.5 0 48-21.5 48-48v-48h160c8.8 0 16 7.2 16 16v32h-32c-17.7 0-32 14.3-32 32v64c0 17.7 14.3 32 32 32h64c17.7 0 32-14.3 32-32v-64c0-17.7-14.3-32-32-32h-32v-32c0-26.5-21.5-48-48-48H288v-32h16c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48h-64c-26.5 0-48 21.5-48 48z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold italic">Cargos</span>
                        </a>
                    </div>
                </div>

                <a href="#" class="flex items-center gap-4 px-6 py-5 rounded-[2rem] transition-all hover:bg-black/10 font-black">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512"><path d="M192 208c0-17.7-14.3-32-32-32h-16c-35.3 0-64 28.7-64 64v48c0 35.3 28.7 64 64 64h16c17.7 0 32-14.3 32-32V208zm176 144c35.3 0 64-28.7 64-64v-48c0-35.3-28.7-64-64-64h-16c-17.7 0-32 14.3-32 32v112c0 17.7 14.3 32 32 32h16zM256 0C114.6 0 0 114.6 0 256v120c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24v-40c0-48.6 39.4-88 88-88h16c26.5 0 48-21.5 48-48V160c0-26.5-21.5-48-48-48H121.6C153.5 56.7 201 32 256 32c106 0 192 86 192 192v24c0 26.5-21.5 48-48 48h-16c-26.5 0-48 21.5-48 48v40c0 26.5 21.5 48 48 48h16c48.6 0 88-39.4 88-88v-40c0-141.4-114.6-256-256-256z"/></svg>
                    <span class="text-xs uppercase tracking-[0.3em] font-black italic">Clientes</span>
                </a>

                <div x-data="{ ventaOpen: false }" class="w-full">
                    <button @click="ventaOpen = !ventaOpen" 
                            class="w-full flex items-center justify-between px-6 py-5 rounded-[2rem] transition-all hover:bg-black/10 font-black">
                        <div class="flex items-center gap-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512"><path d="M384 256H128V208h256v48zm32-80v112H96V176h320zM208 0H112C85.5 0 64 21.5 64 48v80H32c-17.7 0-32 14.3-32 32v128c0 17.7 14.3 32 32 32h16l24 160c1.5 9.9 10 17.3 20 17.3h32c11 0 20-9 20-20V320h224v157.3c0 11 9 20 20 20h32c10 0 18.5-7.4 20-17.3l24-160h16c17.7 0 32-14.3 32-32V160c0-17.7-14.3-32-32-32h-32V48c0-26.5-21.5-48-48-48zM192 64h128v64H192V64z"/></svg>
                            <span class="text-xs uppercase tracking-[0.3em] font-black italic">Venta</span>
                        </div>
                        <svg :class="{'rotate-180': ventaOpen}" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="ventaOpen" x-transition x-cloak class="mt-2 px-2 flex flex-col space-y-1 pb-4">
                        <a href="#" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all hover:bg-black/10 text-black">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M0 96C0 60.7 28.7 32 64 32H448c35.3 0 64 28.7 64 64V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96zm64 64V416H224V160H64zm384 0H288V416H448V160z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold italic">Resumen</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all hover:bg-black/10 text-black">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M0 168v-16c0-13.3 10.7-24 24-24h360V80c0-21.4 25.9-32.1 41-17l71 72c9.4 9.4 9.4 24.6 0 33.9l-71 72c-15.1 15.1-41 4.4-41-17v-48H24c-13.3 0-24-10.7-24-24zm488 152H128v-48c0-21.4-25.9-32.1-41-17l-71 72c-9.4 9.4-9.4 24.6 0 33.9l71 72c15.1 15.1 41 4.4 41-17v-48h360c13.3 0 24-10.7 24-24v-16c0-13.3-10.7-24-24-24z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold italic">Flujo de caja</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all hover:bg-black/10 text-black">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M256 0c-35.3 0-64 28.7-64 64H59.5c-20 0-37.4 14.5-40.8 34.3L0 352h512l-18.7-253.7c-3.4-19.8-20.8-34.3-40.8-34.3H320c0-35.3-28.7-64-64-64zM160 128h192c0 35.3-28.7 64-64 64H224c-35.3 0-64-28.7-64-64zM24 384L38.4 492.3c2.6 15.2 15.6 26.7 31 26.7H442.6c15.4 0 28.4-11.5 31-26.7L488 384H24z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold italic">Venta</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all hover:bg-black/10 text-black">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M480 256h-88c-13.3 0-24 10.7-24 24v24c0 26.5-21.5 48-48 48H192c-26.5 0-48-21.5-48-48v-24c0-13.3-10.7-24-24-24H32c-17.7 0-32 14.3-32 32v128c0 35.3 28.7 64 64 64h384c35.3 0 64-28.7 64-64V288c0-17.7-14.3-32-32-32zM0 160v32c0 17.7 14.3 32 32 32h110c7.3 0 14-3.5 18.4-9.3L192 160h128l31.6 54.7c4.4 5.8 11.1 9.3 18.4 9.3H480c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64H64C28.7 96 0 124.7 0 160z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold italic">Pedidos</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all hover:bg-black/10 text-black">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 384 512"><path d="M320 48v48H192V48h128zm64 48c0-26.5-21.5-48-48-48H288C288 21.5 266.5 0 240 0s-48 21.5-48 48H144c-26.5 0-48 21.5-48 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V96h-48zm-64 304H192v-32h128v32zm0-96H192v-32h128v32z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold italic">Anticipos</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-6 py-3.5 rounded-[1.5rem] transition-all hover:bg-black/10 text-black">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 512 512"><path d="M0 128C0 92.7 28.7 64 64 64h384c35.3 0 64 28.7 64 64v256c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128zm64 64c-17.7 0-32 14.3-32 32v64c0 17.7 14.3 32 32 32s32-14.3 32-32v-64c0-17.7-14.3-32-32-32zm384 0c-17.7 0-32 14.3-32 32v64c0 17.7 14.3 32 32 32s32-14.3 32-32v-64c0-17.7-14.3-32-32-32zM256 192c-35.3 0-64 28.7-64 64s28.7 64 64 64 64-28.7 64-64-28.7-64-64-64z"/></svg>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold italic">Gastos</span>
                        </a>
                    </div>
                </div>

                <a href="#" class="flex items-center gap-4 px-6 py-5 rounded-[2rem] transition-all hover:bg-black/10 font-black">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512"><path d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4 .6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"/></svg>
                    <span class="text-xs uppercase tracking-[0.3em] font-black italic">Configuración</span>
                </a>

            </nav>

            <div class="p-8 border-t border-black/10 shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-4 px-6 py-5 rounded-[2rem] bg-black text-white hover:bg-white hover:text-black transition-all font-black text-xs uppercase tracking-[0.3em] italic shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <header class="w-full bg-white border-b border-gray-100 sticky top-0 z-30 px-8 py-8">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center gap-8">
                    <button @click="sidebarOpen = true" class="p-4 bg-amber-400 rounded-2xl shadow-lg hover:scale-110 transition-transform active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.5em] mb-1 italic">By Ollintem</h2>
                        <p class="text-4xl font-black text-gray-900 tracking-tighter italic uppercase leading-none"></p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.4em] leading-none mb-1">Usuario Activo</p>
                        <p class="text-lg font-black text-gray-900 uppercase italic tracking-tighter leading-none">{{ Auth::user()->nombre }}</p>
                    </div>
                    <div class="w-14 h-14 bg-amber-400 rounded-2xl shadow-inner flex items-center justify-center font-black text-xl italic border-4 border-white shadow-lg">
                        {{ substr(Auth::user()->nombre, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-12">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>