<!DOCTYPE html>
<html lang="es" class="h-full dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso No Permitido - 403</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0; }
            50% { opacity: 0.3; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes drawX {
            from { stroke-dashoffset: 100; }
            to { stroke-dashoffset: 0; }
        }

        .float {
            animation: float 3s ease-in-out infinite;
        }

        .pulse-ring {
            animation: pulse-ring 2s ease-out infinite;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }

        .draw-x {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: drawX 1s ease-out forwards;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .gradient-animate {
            background-size: 200% 200%;
            animation: gradient 3s ease infinite;
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-gray-100 via-gray-50 to-teal-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950">
<div class="min-h-full flex items-center justify-center px-4 relative overflow-hidden">
    <!-- Círculos de fondo animados -->
    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-teal-500/5 dark:bg-teal-500/10 rounded-full blur-3xl pulse-ring"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-3xl pulse-ring delay-1"></div>

    <div class="max-w-lg w-full text-center relative z-10">
        <!-- Contenedor del icono X con anillos -->
        <div class="flex justify-center mb-8 relative">
            <!-- Anillos decorativos -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-32 h-32 border-2 border-teal-500/20 dark:border-teal-500/30 rounded-full pulse-ring"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-40 h-40 border border-teal-400/10 dark:border-teal-400/20 rounded-full pulse-ring delay-2"></div>
            </div>

            <!-- X animada con flotación -->
            <div class="relative float">
                <div class="w-28 h-28 bg-gradient-to-br from-teal-500 to-teal-700 dark:from-teal-600 dark:to-teal-800 rounded-2xl shadow-2xl shadow-teal-500/30 flex items-center justify-center transform rotate-12">
                    <svg class="w-16 h-16 text-white transform -rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path class="draw-x" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Código 403 con gradiente -->
        <h1 class="text-8xl font-black mb-4 slide-up opacity-0 delay-1">
                <span class="bg-gradient-to-r from-teal-600 via-teal-500 to-teal-700 dark:from-teal-400 dark:via-teal-500 dark:to-teal-600 bg-clip-text text-transparent gradient-animate">
                    403
                </span>
        </h1>

        <!-- Título -->
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-3 slide-up opacity-0 delay-2">
            Acceso Denegado
        </h2>

        <!-- Mensaje -->
        <p class="text-base text-gray-600 dark:text-gray-400 mb-2 slide-up opacity-0 delay-3 max-w-md mx-auto">
            No tienes el permiso para acceder a este recurso.
        </p>

        <!-- Mensaje secundario -->
        <div class="flex items-center justify-center gap-2 text-sm text-gray-500 dark:text-gray-500 mb-8 slide-up opacity-0 delay-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span>Contacta al administrador si crees que es un error</span>
        </div>

        <!-- Botones con hover mejorado -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center slide-up opacity-0 delay-4">
            <button onclick="window.history.back()" class="group relative px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-teal-500 dark:hover:border-teal-500 transition-all duration-300 overflow-hidden">
                <span class="absolute inset-0 bg-gradient-to-r from-teal-500/0 via-teal-500/10 to-teal-500/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                <span class="relative flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver Atrás
                    </span>
            </button>

            <a href="/" class="group relative px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-teal-600 to-teal-700 dark:from-teal-500 dark:to-teal-600 rounded-xl hover:shadow-xl hover:shadow-teal-500/30 hover:scale-105 transition-all duration-300">
                <span class="absolute inset-0 bg-gradient-to-r from-teal-700 to-teal-800 dark:from-teal-600 dark:to-teal-700 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <span class="relative flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Ir al Inicio
                    </span>
            </a>
        </div>

        <!-- Código de error -->
        <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800 slide-up opacity-0 delay-4">
            <p class="text-xs font-mono text-gray-400 dark:text-gray-600">
                ERROR_CODE: <span class="text-teal-600 dark:text-teal-500">403_FORBIDDEN</span>
            </p>
        </div>
    </div>
</div>
</body>
</html>
