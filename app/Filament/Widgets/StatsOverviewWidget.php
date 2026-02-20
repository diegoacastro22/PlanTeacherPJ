<?php

namespace App\Filament\Widgets;

use App\Models\ActividadDocente;
use App\Models\Asignatura;
use App\Models\Estudiante;
use App\Models\Informe;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();
        $actividadDocente = $user->actividadDocente;

        // Contar asignaturas
        $totalAsignaturas = $actividadDocente ? $actividadDocente->asignaturas()->count() : 0;

        // Contar estudiantes únicos
        $totalEstudiantes = Estudiante::whereHas('asignaturas', function ($query) use ($user) {
            $query->whereHas('actividadDocente', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        })->distinct()->count();

        // Contar informes
        $totalInformes = Informe::where('user_id', $user->id)->count();

        // Calcular horas totales de docencia
        $horasDocencia = $actividadDocente ? $actividadDocente->horas_docencia_directa : 0;

        return [
            Stat::make('Asignaturas', $totalAsignaturas)
                ->description('Asignaturas asignadas')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, $totalAsignaturas]),

            Stat::make('Estudiantes', $totalEstudiantes)
                ->description('Estudiantes matriculados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart([10, 15, 20, 25, 30, 35, $totalEstudiantes]),

            Stat::make('Horas de Docencia', $horasDocencia . ' hrs')
                ->description('Horas semanales de docencia directa')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Informes', $totalInformes)
                ->description('Informes registrados')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->chart([1, 2, 3, 4, 5, 6, $totalInformes]),
        ];
    }
}
