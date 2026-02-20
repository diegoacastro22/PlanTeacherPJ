<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActividadesComplementariasChart;
use App\Filament\Widgets\AsignaturasTableWidget;
use App\Filament\Widgets\HorasDocenciaChart;
use App\Filament\Widgets\InformesRecientesWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\WelcomeWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            WelcomeWidget::class,
            StatsOverviewWidget::class,
            HorasDocenciaChart::class,
            ActividadesComplementariasChart::class,
            AsignaturasTableWidget::class,
            InformesRecientesWidget::class,
        ];
    }

    public function getColumns(): int| array
    {
        return 2;
    }
}
