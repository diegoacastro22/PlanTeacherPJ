<?php

namespace App\Filament\Resources\ActividadDocentes\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ActividadDocenteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id'),

                TextInput::make('total_grupos')
                    ->label('Número total de grupos a cargo')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('total_estudiantes')
                    ->label('Número total de estudiantes en los grupos a cargo')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_docencia_directa')
                    ->label('Horas de docencia directa (teórica + práctica)')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_tutorias')
                    ->label('Horas de atención a estudiantes (tutorías / asesorías)')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('horas_preparacion')
                    ->label('Horas dedicadas a preparación y evaluación de asignaturas')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('max_asignaturas')
                    ->label('Número máximo de asignaturas a cargo')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('total_asignaturas')
                    ->label('Total de asignaturas registradas')
                    ->required()
                    ->columnSpanFull()
                    ->disabled()
                    ->visibleOn('edit')
                    ->numeric(),
            ]);
    }
}
