<?php

namespace App\Filament\Resources\Estudiantes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EstudianteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('nombre_completo')
                    ->required()
                    ->maxLength(255),

                TextInput::make('correo')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('asignaturas')
                    ->relationship('asignaturas', 'nombre')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->optionsLimit(50)
                    ->label('Asignaturas'),
            ]);
    }
}
