<?php

namespace App\Providers;

use App\Models\ActividadDocente;
use App\Models\Asignatura;
use App\Models\Asistencia;
use App\Models\Informe;
use App\Models\Tutoria;
use App\Observers\ActividadDocenteObserver;
use App\Observers\AsignaturaObserver;
use App\Observers\AsistenciaObserver;
use App\Observers\InformeMediaObserver;
use App\Observers\InformeObserver;
use App\Observers\TutoriaObserver;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        if (config('app.env') === 'production' || request()->header('x-forwarded-proto') === 'https') {
            \URL::forceScheme('https');
        }

        Asistencia::observe(AsistenciaObserver::class);
        Tutoria::observe(TutoriaObserver::class);

        //Sistema de carpetas
        ActividadDocente::observe(ActividadDocenteObserver::class);
        Asignatura::observe(AsignaturaObserver::class);
        Media::observe(InformeMediaObserver::class);
        Informe::observe(InformeObserver::class);


        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}
