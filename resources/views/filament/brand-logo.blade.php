@php
    $user = filament()->auth()->user();

    // Cargar el SVG según el modo
    $svgLight = file_get_contents(public_path('assets/logo-light.svg'));
    $svgDark  = file_get_contents(public_path('assets/logo-light.svg'));

    // Asegurar fill="currentColor" en ambos SVG
    $svgLight = str_replace('fill=', 'data-fill=', $svgLight); // evitar override
    $svgDark  = str_replace('fill=', 'data-fill=', $svgDark);

    // Insertar fill="currentColor" a nivel global
    $svgLight = str_replace('<svg', '<svg fill="currentColor"', $svgLight);
    $svgDark  = str_replace('<svg', '<svg fill="currentColor"', $svgDark);
@endphp

@if ($user)
    <div class="flex items-center gap-3 group">

        {{-- SVG modo claro --}}
        <span class="block dark:hidden w-12 h-auto text-primary-600 transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
            {!! $svgLight !!}
        </span>

        {{-- SVG modo oscuro --}}
        <span class="hidden dark:block w-12 h-auto text-primary-300 transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
            {!! $svgDark !!}
        </span>

        <span class="text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r
            from-primary-700 via-primary-500 to-primary-300
            dark:from-primary-600 dark:via-primary-400 dark:to-primary-200
            gradient-flow">
            {{ config('app.name') }}
        </span>
    </div>

@else
    <div class="mx-auto text-center group cursor-pointer">

        {{-- SVG modo claro --}}
        <span class="mx-auto block dark:hidden w-12 h-auto text-primary-600 transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
            {!! $svgLight !!}
        </span>

        {{-- SVG modo oscuro --}}
        <span class="mx-auto hidden dark:block w-12 h-auto text-primary-300 transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
            {!! $svgDark !!}
        </span>

        <span class="block text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r
            from-primary-700 via-primary-500 to-primary-300
            dark:from-primary-600 dark:via-primary-400 dark:to-primary-200
            gradient-flow">
            {{ config('app.name') }}
        </span>
    </div>
@endif

<style>
    .gradient-flow {
        background-size: 200% auto;
        animation: gradient-flow 3s cubic-bezier(0.6, 0, 0.25, 1) infinite;
    }
    @keyframes gradient-flow {
        0% { background-position: 200% center; }
        100% { background-position: 0% center; }
    }
</style>
