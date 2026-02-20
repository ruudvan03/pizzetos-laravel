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
            class="w-80 bg-amber-400 text-black flex flex-col fixed h-full z-50 shadow-2xl">
            
            <div class="p-10 text-center border-b border-black/10 relative">
                <button @click="sidebarOpen = false" class="absolute top-4 right-4 p-2 hover:bg-black/10 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="bg-white p-3 rounded-full shadow-xl inline-block mb-4">
                    <img src="{{ asset('pizzetos.png') }}" alt="Logo" class="h-16 w-16 object-contain">
                </div>
                <h1 class="text-2xl font-black italic tracking-tighter uppercase leading-none">Pizzetos</h1>
            </div>

            <nav class="flex-1 px-8 py-10 space-y-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-6 py-5 rounded-[2rem] transition-all {{ request()->routeIs('dashboard') ? 'bg-black text-white shadow-xl' : 'hover:bg-black/10 font-black' }}">
                    <span class="text-xs uppercase tracking-[0.3em] font-black italic">Dashboard</span>
                </a>
                
                <a href="{{ route('empleados.index') }}" class="flex items-center gap-4 px-6 py-5 rounded-[2rem] transition-all {{ request()->routeIs('empleados.*') ? 'bg-black text-white shadow-xl' : 'hover:bg-black/10 font-black' }}">
                    <span class="text-xs uppercase tracking-[0.3em] font-black italic">Empleados</span>
                </a>

                <a href="{{ route('corte.index') }}" class="flex items-center gap-4 px-6 py-5 rounded-[2rem] transition-all {{ request()->routeIs('corte.*') ? 'bg-black text-white shadow-xl' : 'hover:bg-black/10 font-black' }}">
                    <span class="text-xs uppercase tracking-[0.3em] font-black italic">Corte Mensual</span>
                </a>
            </nav>

            <div class="p-8 border-t border-black/10">
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