<?php

namespace App\Filament\Widgets;

use App\Models\ActividadComplementaria;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ActividadesComplementariasChart extends ChartWidget
{
    protected ?string $heading = 'Distribución de Horas Complementarias';

    protected static ?int $sort = 2;

    protected string $color = 'info';

    protected function getData(): array
    {
        $actividad = ActividadComplementaria::where('user_id', Auth::id())->first();

        if (!$actividad) {
            return [
                'datasets' => [
                    [
                        'label' => 'Horas',
                        'data' => [0, 0, 0, 0, 0, 0, 0, 0],
                        'backgroundColor' => [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(251, 146, 60)',
                            'rgb(139, 92, 246)',
                            'rgb(236, 72, 153)',
                            'rgb(34, 197, 94)',
                            'rgb(234, 179, 8)',
                            'rgb(239, 68, 68)',
                        ],
                    ],
                ],
                'labels' => [
                    'Trabajos de Grado',
                    'Investigación',
                    'Proyección Social',
                    'Cooperación',
                    'Crecimiento Personal',
                    'Administrativas',
                    'Otras',
                    'Compartidas'
                ],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Horas',
                    'data' => [
                        $actividad->horas_trabajos_grado ?? 0,
                        $actividad->horas_investigacion ?? 0,
                        $actividad->horas_proyeccion_social ?? 0,
                        $actividad->horas_cooperacion ?? 0,
                        $actividad->horas_crecimiento ?? 0,
                        $actividad->horas_administrativas ?? 0,
                        $actividad->horas_otras ?? 0,
                        $actividad->horas_compartidas ?? 0,
                    ],
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(251, 146, 60)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)',
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)',
                    ],
                ],
            ],
            'labels' => [
                'Trabajos de Grado',
                'Investigación',
                'Proyección Social',
                'Cooperación',
                'Crecimiento Personal',
                'Administrativas',
                'Otras',
                'Compartidas'
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
