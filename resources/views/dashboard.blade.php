<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzetos - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-red-600 text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-bold">PIZZETOS - PANEL</h1>
        <div class="flex items-center gap-4">
            <span class="text-sm">Bienvenido, <strong>{{ Auth::user()->nombre }}</strong></span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-800 hover:bg-red-900 px-3 py-1 rounded text-xs">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <div class="p-10">
        <h2 class="text-2xl font-bold text-gray-800">Panel Principal</h2>
        <p class="text-gray-600">Has iniciado sesión correctamente en la sucursal con ID: {{ Auth::user()->id_suc }}</p>
    </div>
</body>
</html>