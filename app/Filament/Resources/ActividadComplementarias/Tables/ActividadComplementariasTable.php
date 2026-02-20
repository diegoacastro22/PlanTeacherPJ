<?php

namespace App\Filament\Resources\ActividadComplementarias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActividadComplementariasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_trabajos_grado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_investigacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_proyeccion_social')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_cooperacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_crecimiento')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_administrativas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_otras')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('horas_compartidas')
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
