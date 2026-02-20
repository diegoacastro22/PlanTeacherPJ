<?php

namespace App\Filament\Resources\Tutorias\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DetallesRelationManager extends RelationManager
{
    protected static string $relationship = 'detalles';

    protected static ?string $title = 'Lista de Estudiantes';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('estudiante.nombre_completo')
            ->columns([
                Tables\Columns\TextColumn::make('estudiante.nombre_completo')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('estudiante.codigo')
                    ->label('Código')
                    ->searchable(),

                Tables\Columns\IconColumn::make('asistio')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('asistio')
                    ->label('Estado')
                    ->options([
                        '1' => 'Presente',
                        '0' => 'Ausente',
                    ]),
            ])
            ->headerActions([
                // No necesitamos crear detalles manualmente
            ])
            ->actions([
                Action::make('toggle')
                    ->label(fn ($record) => $record->asistio ? 'Marcar Falta' : 'Marcar Presente')
                    ->icon(fn ($record) => $record->asistio ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->asistio ? 'danger' : 'success')
                    ->requiresConfirmation(false)
                    ->action(function ($record) {
                        $record->update(['asistio' => !$record->asistio]);

                        // Refrescar el registro padre para actualizar los placeholders
                        $this->getOwnerRecord()->refresh();

                        // Despachar evento para refrescar la página
                        $this->dispatch('$refresh');
                    })
                    ->disabled(fn () => $this->getOwnerRecord()->finalizada),
            ])
            ->bulkActions([
                BulkAction::make('marcar_presente')
                    ->label('Marcar presente')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each->update(['asistio' => true]);

                        // Refrescar el registro padre
                        $this->getOwnerRecord()->refresh();

                        // Despachar evento
                        $this->dispatch('$refresh');
                    })
                    ->deselectRecordsAfterCompletion()
                    ->disabled(fn () => $this->getOwnerRecord()->finalizada),

                BulkAction::make('marcar_ausente')
                    ->label('Marcar ausente')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($records) {
                        $records->each->update(['asistio' => false]);

                        // Refrescar el registro padre
                        $this->getOwnerRecord()->refresh();

                        // Despachar evento
                        $this->dispatch('$refresh');
                    })
                    ->deselectRecordsAfterCompletion()
                    ->disabled(fn () => $this->getOwnerRecord()->finalizada),
            ])
            ->defaultSort('estudiante.nombre_completo');
    }
}
