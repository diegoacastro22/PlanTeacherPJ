<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-primary-100 dark:bg-primary-900 p-3">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            ¡Bienvenido, {{ $this->getViewData()['userName'] }}! 👋
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ now()->format('l, d \d\e F \d\e Y') }}
                        </p>
                    </div>
                </div>

                @if($this->getViewData()['hasPlanTrabajo'])
                    <div class="mt-4 p-3 rounded-lg bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-success-800 dark:text-success-300">
                                    Plan de Trabajo Activo
                                </p>
                                <p class="text-xs text-success-600 dark:text-success-400 mt-1">
                                    {{ $this->getViewData()['planTrabajoTitulo'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 p-3 rounded-lg bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-warning-800 dark:text-warning-300">
                                    No tienes un Plan de Trabajo registrado
                                </p>
                                <p class="text-xs text-warning-600 dark:text-warning-400 mt-1">
                                    Te recomendamos crear uno para organizar mejor tus actividades.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
