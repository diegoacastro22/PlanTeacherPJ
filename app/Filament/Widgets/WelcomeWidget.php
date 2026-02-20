<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected static ?int $sort = 0;

    protected string $view = 'filament.widgets.welcome-widget';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = Auth::user();
        $planTrabajo = $user->planTrabajo;

        return [
            'userName' => $user->name,
            'hasPlanTrabajo' => $planTrabajo !== null,
            'planTrabajoTitulo' => $planTrabajo?->titulo,
        ];
    }
}
