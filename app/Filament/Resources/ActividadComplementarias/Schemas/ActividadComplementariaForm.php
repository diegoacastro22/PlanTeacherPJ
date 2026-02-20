<?php

namespace App\Filament\Resources\ActividadComplementarias\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ActividadComplementariaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id'),

                TextInput::make('horas_trabajos_grado')
                    ->label('Horas de orientación y evaluación de trabajos de grado')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_investigacion')
                    ->label('Horas dedicadas a investigación aprobada')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_proyeccion_social')
                    ->label('Horas dedicadas a proyección social registrada')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_cooperacion')
                    ->label('Horas dedicadas a cooperación interinstitucional')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_crecimiento')
                    ->label('Horas para crecimiento personal y profesional')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_administrativas')
                    ->label('Horas dedicadas a actividades administrativas')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_otras')
                    ->label('Horas dedicadas a otras actividades')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_compartidas')
                    ->label('Horas compartidas con las horas semanales')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
