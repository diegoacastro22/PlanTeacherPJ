<?php

namespace App\Filament\Resources\Asistencias\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AsistenciaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Asistencia')
                    ->schema([
                        Select::make('asignatura_id')
                            ->label('Asignatura')
                            ->relationship('asignatura', 'nombre')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nombre} - G{$record->grupo} - #{$record->codigo}")
                            ->searchable(['nombre', 'codigo', 'grupo'])
                            ->preload()
                            ->required()
                            ->disabled(fn (string $operation) => $operation === 'edit'),

                        DatePicker::make('fecha')
                            ->label('Fecha de la asistencia')
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, \Closure $fail) {
                                        $asignaturaId = request()->input('data.asignatura_id');

                                        if ($asignaturaId && $value) {
                                            $exists = \App\Models\Asistencia::query()
                                                ->whereDate('fecha', $value)
                                                ->where('asignatura_id', $asignaturaId)
                                                ->exists();

                                            if ($exists) {
                                                $fail('Ya existe una asistencia registrada para esta fecha y asignatura.');
                                            }
                                        }
                                    };
                                },
                            ])
                            ->required()
                            ->default(now())
                            ->disabled(fn ($record) => $record?->finalizada),

                        Toggle::make('finalizada')
                            ->label('Asistencia finalizada')
                            ->helperText('Una vez finalizada, no se podrán hacer más cambios')
                            ->default(false)
                            ->visible(fn (string $operation) => $operation === 'edit')
                            ->disabled(fn ($record) => $record?->finalizada)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Resumen de Asistencia')
                    ->schema([
                        Placeholder::make('total_estudiantes')
                            ->label('Total de estudiantes')
                            ->content(function ($record) {
                                if (!$record) return '0';
                                return $record->detalles()->count();
                            })
                            ->extraAttributes([
                                'class' => 'text-3xl font-bold text-gray-700 dark:text-gray-300'
                            ]),

                        Placeholder::make('porcentaje')
                            ->label('Porcentaje de asistencia')
                            ->content(function ($record) {
                                if (!$record) return '0%';
                                $total = $record->detalles()->count();
                                if ($total === 0) return '0%';
                                $presentes = $record->detalles()->where('asistio', true)->count();
                                return round(($presentes / $total) * 100, 2) . '%';
                            })
                            ->extraAttributes([
                                'class' => 'text-3xl font-bold text-blue-600 dark:text-blue-400'
                            ]),

                        Placeholder::make('presentes')
                            ->label('Presentes')
                            ->content(function ($record) {
                                if (!$record) return '0';
                                return $record->detalles()->where('asistio', true)->count();
                            })
                            ->extraAttributes([
                                'class' => 'text-3xl font-bold text-green-600 dark:text-green-400'
                            ]),

                        Placeholder::make('ausentes')
                            ->label('Ausentes')
                            ->content(function ($record) {
                                if (!$record) return '0';
                                return $record->detalles()->where('asistio', false)->count();
                            })
                            ->extraAttributes([
                                'class' => 'text-3xl font-bold text-red-600 dark:text-red-400'
                            ]),
                    ])
                    ->columns(2)
                    ->visible(fn (string $operation) => $operation === 'edit'),
            ])
            ->extraAttributes(['style' => 'display: grid; grid-auto-rows: 1fr;']);
    }
}
