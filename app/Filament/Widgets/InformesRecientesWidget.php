<?php

namespace App\Filament\Widgets;

use App\Models\Informe;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class InformesRecientesWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Informe::query()
                    ->where('user_id', Auth::id())
                    ->with(['estudiante'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('estudiante.nombre_completo')
                    ->label('Estudiante')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('estudiante.codigo')
                    ->label('Código')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tipo_actividad')
                    ->label('Tipo de Actividad')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'orientacion_evaluacion_trabajos_grado' => 'Orientación y evaluación de trabajos de grado',
                        'investigacion_aprobada' => 'Investigación aprobada',
                        'proyeccion_social_registrada' => 'Proyección social registrada',
                        'cooperacion_interinstitucional' => 'Cooperación interinstitucional',
                        'crecimiento_personal_profesional' => 'Crecimiento personal y profesional',
                        'actividades_administrativas' => 'Actividades administrativas',
                        'otras_actividades' => 'Otras actividades',
                        'compartidas_horas_semanales' => 'Compartidas con las horas semanales',
                        default => $state,
                    })
                    ->wrap()
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('tiene_pdf')
                    ->label('PDF')
                    ->boolean()
                    ->getStateUsing(fn (Informe $record): bool => $record->hasMedia('informe_pdf'))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('ver_pdf')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->size('sm')
                    ->url(fn (Informe $record): string =>
                    $record->hasMedia('informe_pdf')
                        ? $record->getFirstMediaUrl('informe_pdf')
                        : '#'
                    )
                    ->openUrlInNewTab()
                    ->visible(fn (Informe $record): bool => $record->hasMedia('informe_pdf')),
            ])
            ->heading('Informes Recientes')
            ->description('Últimos 5 informes registrados')
            ->paginated(false);
    }
}
