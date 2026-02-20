<?php

namespace App\Filament\Widgets;

use App\Models\ActividadDocente;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class HorasDocenciaChart extends ChartWidget
{
    protected ?string $heading = 'Distribución de Horas Docentes';

    protected static ?int $sort = 2;

    protected string $color = 'success';

    protected function getData(): array
    {
        $actividad = ActividadDocente::where('user_id', Auth::id())->first();

        if (!$actividad) {
            return [
                'datasets' => [
                    [
                        'label' => 'Horas',
                        'data' => [0, 0, 0],
                        'backgroundColor' => [
                            'rgb(34, 197, 94)',
                            'rgb(59, 130, 246)',
                            'rgb(251, 146, 60)',
                        ],
                    ],
                ],
                'labels' => ['Docencia Directa', 'Tutorías', 'Preparación'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Horas Semanales',
                    'data' => [
                        $actividad->horas_docencia_directa ?? 0,
                        $actividad->horas_tutorias ?? 0,
                        $actividad->horas_preparacion ?? 0,
                    ],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(251, 146, 60)',
                    ],
                ],
            ],
            'labels' => ['Docencia Directa', 'Tutorías', 'Preparación'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
