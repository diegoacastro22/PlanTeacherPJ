<?php

namespace App\Observers;

use App\Models\ActividadDocente;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ActividadDocenteObserver
{
    public function created(ActividadDocente $actividadDocente): void
    {
        try {
            $docente = auth()->user();

            if (!$docente) {
                Log::warning('No se pudo obtener el docente para crear las carpetas');
                return;
            }

            $basePath = $this->resolveBasePath();
            $docenteFolder = "Actividades del Docente - {$docente->email}";
            $docentePath = "{$basePath}/{$docenteFolder}";

            // Crear carpeta principal
            if (!File::exists($docentePath)) {
                File::makeDirectory($docentePath, 0755, true);
            }

            // Subcarpetas generales
            $subcarpetas = [
                'asignaturas',
                'informes',
                'evaluaciones',
                'documentos_generales',
            ];

            foreach ($subcarpetas as $sub) {
                $path = "{$docentePath}/{$sub}";
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }
            }

            // Archivo de información general
            $readmePath = "{$docentePath}/Informacion del docente.txt";
            $readmeContent = "Carpeta de Actividades Docentes\n";
            $readmeContent .= "Docente: {$docente->name}\n";
            $readmeContent .= "Email: {$docente->email}\n";
            $readmeContent .= "Fecha de creación: " . now()->format('d/m/Y H:i:s') . "\n";

            File::put($readmePath, $readmeContent);

        } catch (\Exception $e) {
            Log::error("Error creando carpetas de actividad docente", [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleted(ActividadDocente $actividadDocente): void
    {
        try {
            $docente = auth()->user();
            if (!$docente) return;

            $basePath = $this->resolveBasePath();
            $docenteFolder = "Actividades del Docente - {$docente->email}";
            $docentePath = "{$basePath}/{$docenteFolder}";

            if (File::exists($docentePath)) {
                File::deleteDirectory($docentePath);
            }

        } catch (\Exception $e) {
            Log::error("Error eliminando carpetas de actividad docente", [
                'error' => $e->getMessage()
            ]);
        }
    }

    private function resolveBasePath(): string
    {
        $path = env('DOCUMENTS_BASE_PATH', '../documentos/actividades_docente');
        return $this->isAbsolutePath($path) ? $path : base_path($path);
    }

    private function isAbsolutePath(string $path): bool
    {
        if (preg_match('/^[a-zA-Z]:[\\\\\/]/', $path)) return true;
        return str_starts_with($path, '/');
    }
}
