<?php

namespace App\Filament\Resources\Informes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InformeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('estudiante_id')
                    ->relationship('estudiante', 'id')
                    ->required(),
                Select::make('tipo_actividad')
                    ->options([
            'orientacion_evaluacion_trabajos_grado' => 'Orientacion evaluacion trabajos grado',
            'investigacion_aprobada' => 'Investigacion aprobada',
            'proyeccion_social_registrada' => 'Proyeccion social registrada',
            'cooperacion_interinstitucional' => 'Cooperacion interinstitucional',
            'crecimiento_personal_profesional' => 'Crecimiento personal profesional',
            'actividades_administrativas' => 'Actividades administrativas',
            'otras_actividades' => 'Otras actividades',
            'compartidas_horas_semanales' => 'Compartidas horas semanales',
        ])
                    ->required(),
                Textarea::make('observaciones')
                    ->columnSpanFull(),
            ]);
    }
}
