<x-filament-panels::page>
    @if($planTrabajo)
        {{-- Vista del Plan de Trabajo --}}
        @php
            $media = $planTrabajo->getFirstMedia('plan_trabajo');
        @endphp

        @if($media)
            {{-- Visor de PDF --}}
            <div>
                <div class="border rounded-lg -mt-4 overflow-hidden bg-gray-50 dark:bg-gray-900">
                    <object
                        data="{{ $media->getUrl() }}#toolbar=1&navpanes=1&scrollbar=1"
                        type="application/pdf"
                        class="w-full h-[800px]">
                        <p class="p-4 text-center">
                            No se puede mostrar el PDF.
                            <a href="{{ $media->getUrl() }}" target="_blank" class="text-primary-600 hover:underline">
                                Haz clic aquí para abrirlo
                            </a>
                        </p>
                    </object>
                </div>
            </div>

            {{-- Información y botones --}}
            <x-filament::section class="mt-6">
                <x-slot name="heading">
                    {{ $planTrabajo->titulo }}
                </x-slot>

                @if($planTrabajo->descripcion)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{ $planTrabajo->descripcion }}
                    </p>
                @endif

                <div class="space-y-4">
                    <p class="text-xs text-gray-500 dark:text-gray-500">
                        {{ $media->file_name }} ({{ $media->human_readable_size }}) • Última actualización: {{ $planTrabajo->updated_at->format('d/m/Y H:i') }}
                    </p>

                    {{-- Botones de acción --}}
                    <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex gap-2">
                            <x-filament::button
                                tag="a"
                                href="{{ $media->getUrl() }}"
                                target="_blank"
                                icon="heroicon-o-eye"
                                size="sm"
                            >
                                Abrir PDF
                            </x-filament::button>

                            <x-filament::button
                                tag="a"
                                href="{{ $media->getUrl() }}"
                                download="{{ $media->file_name }}"
                                icon="heroicon-o-arrow-down-tray"
                                color="gray"
                                size="sm"
                            >
                                Descargar
                            </x-filament::button>
                        </div>

                        {{ ($this->deleteAction) }}
                    </div>
                </div>
            </x-filament::section>
        @else
            {{-- Si existe plan pero no tiene PDF --}}
            <x-filament::section>
                <x-slot name="heading">
                    {{ $planTrabajo->titulo }}
                </x-slot>

                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        No hay un documento PDF asociado a este plan.
                    </p>

                    {{ ($this->deleteAction) }}
                </div>
            </x-filament::section>
        @endif
    @else
        {{-- Formulario de creación --}}
        <x-filament::section>
            <x-slot name="heading">
                Crear Plan de Trabajo
            </x-slot>

            <div class="text-center py-8 mb-6">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Sin Plan de Trabajo
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Completa el formulario para crear tu plan de trabajo.
                </p>
            </div>

            <form wire:submit="save">
                {{ $this->form }}

                <div class="flex justify-end mt-6">
                    <x-filament::button
                        type="submit"
                        color="primary"
                    >
                        Crear Plan de Trabajo
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>
    @endif
</x-filament-panels::page>
