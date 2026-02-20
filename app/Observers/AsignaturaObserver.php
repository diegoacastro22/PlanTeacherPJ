<?php

namespace App\Observers;

use App\Models\Asignatura;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AsignaturaObserver
{
    public function created(Asignatura $asignatura): void
    {
        try {
            $actividad = $asignatura->actividadDocente;
            $docente = $actividad->docente ?? auth()->user();

            if (!$actividad || !$docente) return;

            $base = $this->resolveBasePath();
            $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
            $asignaturasPath = "{$docentePath}/asignaturas";

            // Carpeta de la asignatura
            $nombreAsignatura = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo}";
            $asignaturaFolder = "{$asignaturasPath}/{$nombreAsignatura}";

            // Crear la carpeta de la asignatura
            File::makeDirectory($asignaturaFolder, 0755, true);

            // Subcarpetas solicitadas
            File::makeDirectory("{$asignaturaFolder}/asistencias", 0755, true);
            File::makeDirectory("{$asignaturaFolder}/tutorias", 0755, true);
            File::makeDirectory("{$asignaturaFolder}/documentos", 0755, true);

            Log::info("Carpeta de asignatura creada", [
                'path' => $asignaturaFolder
            ]);

        } catch (\Exception $e) {
            Log::error("Error creando carpeta de asignatura", [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleted(Asignatura $asignatura): void
    {
        try {
            $actividad = $asignatura->actividadDocente;
            $docente = $actividad->docente ?? auth()->user();

            if (!$actividad || !$docente) return;

            $base = $this->resolveBasePath();
            $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
            $asignaturasPath = "{$docentePath}/asignaturas";

            $nombreAsignatura = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo}";
            $asignaturaFolder = "{$asignaturasPath}/{$nombreAsignatura}";

            if (File::exists($asignaturaFolder)) {
                File::deleteDirectory($asignaturaFolder);
            }

        } catch (\Exception $e) {
            Log::error("Error eliminando carpeta de asignatura", [
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

    public function updated(Asignatura $asignatura): void
    {
        try {
            // Campos que afectan el nombre de la carpeta
            $camposClave = ['nombre', 'codigo', 'grupo'];

            // Si ninguno cambió → no hacemos nada
            if (! $asignatura->wasChanged($camposClave)) {
                return;
            }

            $actividad = $asignatura->actividadDocente;
            $docente = $actividad->docente ?? auth()->user();

            if (! $actividad || ! $docente) return;

            $base = $this->resolveBasePath();
            $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
            $asignaturasPath = "{$docentePath}/asignaturas";

            /* ---------------------------------------------------------
               Construcción de carpetas: antigua y nueva
            ----------------------------------------------------------*/

            // Nombre antiguo usando getOriginal()
            $oldNombre = $asignatura->getOriginal('nombre');
            $oldCodigo = $asignatura->getOriginal('codigo');
            $oldGrupo  = $asignatura->getOriginal('grupo');

            $oldFolderName = "{$oldNombre} - {$oldCodigo} - Grupo {$oldGrupo}";
            $oldFolderPath = "{$asignaturasPath}/{$oldFolderName}";

            // Nombre nuevo con sus valores actuales
            $newFolderName = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo}";
            $newFolderPath = "{$asignaturasPath}/{$newFolderName}";

            /* ---------------------------------------------------------
               Renombrar carpeta
            ----------------------------------------------------------*/

            if (File::exists($oldFolderPath)) {
                File::move($oldFolderPath, $newFolderPath);

                Log::info("Carpeta de asignatura renombrada", [
                    'old' => $oldFolderPath,
                    'new' => $newFolderPath,
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Error renombrando carpeta de asignatura", [
                'error' => $e->getMessage()
            ]);
        }
    }
}
