<?php

namespace App\Filament\Resources\ActividadDocentes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActividadDocentesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_asignaturas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_grupos')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_estudiantes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_docencia_directa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_tutorias')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_preparacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_asignaturas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
