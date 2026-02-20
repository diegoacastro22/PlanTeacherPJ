<?php

return [

    'driver' => env('UI_SWITCHER_DRIVER', 'session'),

    'database_column' => 'ui_preferences',

    'defaults' => [
        'font' => 'Quicksand',
        'color' => '#042f2e', // teal 950
        'layout' => 'sidebar',
        'font_size' => 16,
        'density' => 'default',
    ],

    'icon' => 'heroicon-o-cog-6-tooth',

    /*
    |--------------------------------------------------------------------------
    | Available Google Fonts (TODAS admitidas)
    |--------------------------------------------------------------------------
    */
    'fonts' => [
        // Ya tenías estas:
        'Quicksand',
        'Inter',
        'Poppins',
        'Public Sans',
        'DM Sans',
        'Nunito Sans',
        'Roboto',
        'Montserrat',
        'Work Sans',
        'Outfit',
        'Rubik',
        'Source Sans Pro',
        'Plus Jakarta Sans',

        // 🔥 NUEVAS, TODAS EN GOOGLE FONTS Y MUY DISTINTAS ENTRE SÍ

        // Geométricas modernas
        'Manrope',
        'Sora',
        'Epilogue',
        'Figtree',
        'Urbanist',

        // Sans serif profesionales
        'Mulish',
        'Karla',
        'Barlow',
        'Red Hat Display',
        'IBM Plex Sans',

        // Monoespaciadas para estilo técnico
        'Fira Code',
        'JetBrains Mono',

        // Serif elegantes (alternativas totalmente diferentes)
        'Merriweather',
        'Lora',
        'Playfair Display',

        // Display para títulos llamativos
        'Bebas Neue',
        'Anton',
        'Oswald',
    ],

    'custom_colors' => [

        // Obligatorios
        '#ffffff', // Blanco
        '#000000', // Negro
        '#808080', // Gris
        '#042f2e', // Teal único (teal-600, pero ÚNICO, no gama)

        // Colores sueltos (TODOS distintos)
        '#ff6b6b',
        '#ee6055',
        '#d90429',
        '#f07167',
        '#f4a261',
        '#e76f51',
        '#ff9f1c',
        '#ffbf00',
        '#ffd60a',
        '#e9d8a6',
        '#bc6c25',
        '#7f5539',
        '#6f1d1b',
        '#9d0208',
        '#7209b7',
        '#8338ec',
        '#5a189a',
        '#3a0ca3',
        '#4361ee',
        '#3f37c9',
        '#1d3557',
        '#457b9d',
        '#1e3a8a',
        '#0d3b66',
        '#4cc9f0',
        '#0096c7',
        '#48cae4',
        '#2a9d8f',
        '#16a34a',
        '#386641',
        '#2b9348',
        '#b5e48c',
        '#ffcad4',
        '#b5838d',
        '#6c757d',
        '#adb5bd',
        '#343a40',
        '#22223b',
        '#4a4e69',
        '#9a8c98',
        '#c9ada7',

    ],


    'layouts' => [
        'sidebar',
        'sidebar-collapsed',
        'sidebar-no-topbar',
        'topbar',
        'minimal',
        'compact-sidebar',
        'split',
    ],

    'font_size_range' => [
        'min' => 10,
        'max' => 24,
    ],
];
