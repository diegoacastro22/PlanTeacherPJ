<?php

namespace App\Observers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Estudiante;

class InformeMediaObserver
{
    /**
     * Se dispara cuando se agrega un media (PDF) a la colección informe_pdf.
     */
    public function created(Media $media): void
    {
        if ($media->collection_name !== 'informe_pdf') {
            return;
        }

        dispatch(function () use ($media) {

            sleep(3); // darle tiempo a Spatie a escribir el archivo

            $source = $media->fresh()->getPath();
            $informe = $media->model;
            if (File::exists($source)) {
                $target = $this->buildInformeFilePath($informe);
                File::copy($source, $target);
            }

        })->onQueue('default');
    }


    /**
     * Actualización del PDF o cambios en tipo_actividad / estudiante_id.
     * Se dispara cuando el media se actualiza.
     */
    public function updated(Media $media): void
    {
        if ($media->collection_name !== 'informe_pdf') {
            return;
        }

        try {
            /** @var \App\Models\Informe $informe */
            $informe = $media->model;

            $this->ensureInformeFolderExists($informe);

            $base = $this->resolveBasePath();
            $docente = $informe->user ?? auth()->user();

            // Obtener valores antiguos del modelo
            $oldTipo = $informe->getOriginal('tipo_actividad');
            $oldEstudianteId = $informe->getOriginal('estudiante_id');

            $oldEstudiante = $oldEstudianteId ? Estudiante::find($oldEstudianteId) : null;
            $oldTipoLabel = $this->tipoLabelFromKey($oldTipo) ?? $oldTipo;
            $oldEstNombre = $oldEstudiante?->nombre_completo ?? 'sin_estudiante';

            $oldRaw = "{$oldTipoLabel} - {$oldEstNombre}.pdf";
            $oldFilename = $this->sanitizeFileName($oldRaw);

            $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
            $informesFolder = "{$docentePath}/informes";

            $oldFull = "{$informesFolder}/{$oldFilename}";
            $newFull = $this->buildInformeFilePath($informe);

            // Si cambió el nombre → mover archivo
            if (File::exists($oldFull) && $oldFull !== $newFull) {
                if (!File::exists($informesFolder)) {
                    File::makeDirectory($informesFolder, 0755, true);
                }
                File::move($oldFull, $newFull);
            }

            // Copiar PDF actualizado
            if ($media->getPath() && File::exists($media->getPath())) {
                File::copy($media->getPath(), $newFull);
            }
        } catch (\Throwable $e) {
            Log::error('Error en InformeMediaObserver@updated', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Cuando se elimina el PDF.
     */
    public function deleted(Media $media): void
    {
        if ($media->collection_name !== 'informe_pdf') {
            return;
        }

        try {
            /** @var \App\Models\Informe $informe */
            $informe = $media->model;

            $this->ensureInformeFolderExists($informe);

            $target = $this->buildInformeFilePath($informe);

            if (File::exists($target)) {
                File::delete($target);
            }
        } catch (\Throwable $e) {
            Log::error('Error en InformeMediaObserver@deleted', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /* -----------------------------------------------------------------
       Helpers privados (copiados literalmente de tu InformeObserver)
    ------------------------------------------------------------------*/

    private function buildInformeFilePath($informe): string
    {
        $base = $this->resolveBasePath();
        $docente = $informe->user ?? auth()->user();
        $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
        $informesFolder = "{$docentePath}/informes";

        $tipoLabel = method_exists($informe, 'getTipoActividadLabelAttribute')
            ? $informe->tipo_actividad_label
            : ($this->tipoLabelFromKey($informe->tipo_actividad) ?? $informe->tipo_actividad);

        $estNombre = $informe->estudiante?->nombre_completo ?? 'sin_estudiante';

        $raw = "{$tipoLabel} - {$estNombre}";
        $rawFilename = $this->sanitizeFileName($raw);
        $filename = "{$rawFilename} - {$informe->id}.pdf";
        if (!File::exists($informesFolder)) {
            File::makeDirectory($informesFolder, 0755, true);
        }
        return "{$informesFolder}/{$filename}";
    }

    private function ensureInformeFolderExists($informe): void
    {
        $base = $this->resolveBasePath();
        $docente = $informe->user ?? auth()->user();
        $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
        $informesFolder = "{$docentePath}/informes";

        if (!File::exists($docentePath)) {
            File::makeDirectory($docentePath, 0755, true);
        }

        if (!File::exists($informesFolder)) {
            File::makeDirectory($informesFolder, 0755, true);
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

    private function sanitizeFileName(string $name): string
    {
        $san = preg_replace('/[\\\\\/:*?"<>|]+/', '_', $name);
        $san = preg_replace('/\s+/', ' ', $san);
        $san = trim($san);
        return mb_substr($san, 0, 200);
    }

    private function tipoLabelFromKey(?string $key): ?string
    {
        if (!$key) return null;

        $map = [
            'orientacion_evaluacion_trabajos_grado' => 'Orientación y evaluación de trabajos de grado',
            'investigacion_aprobada' => 'Investigación aprobada',
            'proyeccion_social_registrada' => 'Proyección social registrada',
            'cooperacion_interinstitucional' => 'Cooperación interinstitucional',
            'crecimiento_personal_profesional' => 'Crecimiento personal y profesional',
            'actividades_administrativas' => 'Actividades administrativas',
            'otras_actividades' => 'Otras actividades',
            'compartidas_horas_semanales' => 'Compartidas con las horas semanales',
        ];

        return $map[$key] ?? $key;
    }
}
