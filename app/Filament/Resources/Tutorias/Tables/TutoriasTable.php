<?php

namespace App\Filament\Resources\Tutorias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TutoriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asignatura.nombre')
                    ->label('Asignatura')
                    ->formatStateUsing(fn ($record) =>
                    "{$record->asignatura->nombre} - G{$record->asignatura->grupo} - #{$record->asignatura->codigo}"
                    )
                    ->searchable(['nombre', 'codigo', 'grupo'])
                    ->sortable(),

                TextColumn::make('fecha')
                    ->date()
                    ->sortable(),

                TextColumn::make('horas')
                    ->label('Horas')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('observaciones')
                    ->label('Observaciones')
                    ->wrap() // permite que el texto se muestre en varias líneas si es largo
                    ->limit(20) // opcional: corta el texto a 50 caracteres con "..."
                    ->tooltip(fn ($record) => $record->observaciones), // muestra el texto completo al pasar el mouse

                TextColumn::make('detalles_count')
                    ->label('Presentes/Total')
                    ->formatStateUsing(fn ($record) =>
                        $record->detalles()->where('asistio', true)->count()
                        . '/' .
                        $record->detalles()->count()
                    )
                    ->alignCenter()
                    ->badge()
                    ->color(function ($record) {
                        $presentes = $record->detalles()->where('asistio', true)->count();
                        $total = $record->detalles()->count();
                        $porcentaje = $total > 0 ? ($presentes / $total) * 100 : 0;

                        if ($porcentaje >= 80) return 'success';
                        if ($porcentaje >= 50) return 'warning';
                        return 'danger';
                    }),

                IconColumn::make('finalizada')
                    ->boolean(),

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
            ])

            ->defaultSort('fecha', 'desc');
    }
}
