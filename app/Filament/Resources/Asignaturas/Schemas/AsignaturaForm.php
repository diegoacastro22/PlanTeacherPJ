<?php

namespace App\Filament\Resources\Asignaturas\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AsignaturaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('actividad_docente_id'),

                TextInput::make('codigo')
                    ->label('Código de la asignatura')
                    ->required(),

                TextInput::make('nombre')
                    ->label('Nombre de la asignatura')
                    ->required(),

                TextInput::make('grupo')
                    ->label('Número de grupo (01–20)')
                    ->required(),

                TextInput::make('facultad')
                    ->label('Facultad a la que pertenece la asignatura')
                    ->required(),

                TextInput::make('limite_estudiantes')
                    ->label('Límite máximo de estudiantes')
                    ->required()
                    ->numeric(),

                TextInput::make('horas_practicas')
                    ->label('Horas prácticas por semana')
                    ->required()
                    ->numeric(),

                TextInput::make('horas_teoricas')
                    ->label('Horas teóricas por semana')
                    ->required()
                    ->numeric(),
            ]);
    }
}
