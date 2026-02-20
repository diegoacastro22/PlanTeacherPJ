<?php

namespace App\Filament\Widgets;

use App\Models\Asignatura;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class AsignaturasTableWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Asignatura::query()
                    ->whereHas('actividadDocente', function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->with(['estudiantes'])
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Asignatura')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('grupo')
                    ->label('Grupo')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('facultad')
                    ->label('Facultad')
                    ->searchable()
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('estudiantes_count')
                    ->label('Estudiantes')
                    ->counts('estudiantes')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('horas_teoricas')
                    ->label('H. Teóricas')
                    ->suffix(' hrs')
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('horas_practicas')
                    ->label('H. Prácticas')
                    ->suffix(' hrs')
                    ->alignCenter()
                    ->toggleable(),
            ])
            ->heading('Mis Asignaturas')
            ->description('Listado de asignaturas que impartes actualmente')
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }
}
