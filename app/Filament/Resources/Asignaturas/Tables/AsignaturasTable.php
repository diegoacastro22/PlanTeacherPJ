<?php

namespace App\Filament\Resources\Asignaturas\Tables;

use App\Filament\Exports\AsignaturaExporter;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AsignaturasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('nombre')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('grupo')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('facultad')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('limite_estudiantes')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('horas_practicas')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('horas_teoricas')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
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
                    ExportBulkAction::make('Exportar seleccionados')
                        ->label('Exportar seleccionados')
                        ->exporter(AsignaturaExporter::class)
                        ->filename('asignaturas_export'),

                ]),
                ExportAction::make('exportar_todas')
                    ->label('Exportar todo')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->exporter(AsignaturaExporter::class)
                    ->fileName(fn (): string => 'asignaturas_completo_' . now()->format('Y-m-d_His'))
                    ->modifyQueryUsing(function ($query) {
                        // Exportar solo las asignaturas del docente actual
                        $actividadDocenteId = auth()->user()->actividadDocente->id;
                        return $query->where('actividad_docente_id', $actividadDocenteId);
                    }),
                Action::make('Eliminar todas las asignaturas de la actividad docente')
                    ->label('Eliminar todo')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () {
                        // Definir la actividad docente específica
                        $actividadDocenteId = auth()->user()->actividadDocente->id; // Cambiar según tu lógica

                        $deleted = \App\Models\Asignatura::where('actividad_docente_id', $actividadDocenteId)->delete();

                        Notification::make()
                            ->title("Se eliminaron $deleted asignaturas de la actividad del docente")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
