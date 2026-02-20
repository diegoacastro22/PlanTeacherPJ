<?php

namespace App\Observers;

use App\Models\Informe;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Estudiante;

class InformeObserver
{
    public function updated(Informe $informe): void
    {
        // Campos que cambian el nombre del archivo
        $campos = ['tipo_actividad', 'estudiante_id', 'user_id'];

        // ¿Cambió algún campo relevante?
        if (! $informe->isDirty($campos)) {
            return;
        }

        try {
            $docente = auth()->user();

            /** ------------------------------
             * 1️⃣ Construir el nombre viejo
             * ------------------------------*/
            $oldTipo = $informe->getOriginal('tipo_actividad');
            $oldId = $informe->getOriginal('id');
            $oldEstId = $informe->getOriginal('estudiante_id');
            $oldUserId = $informe->getOriginal('user_id');

            $oldEst = $oldEstId ? Estudiante::find($oldEstId) : null;

            $oldTipoLabel = $this->tipoLabelFromKey($oldTipo);
            $oldEstName = $oldEst?->nombre_completo ?? 'sin_estudiante';

            $oldRaw = "{$oldTipoLabel} - {$oldEstName}";
            $oldRawFilename = $this->sanitizeFileName($oldRaw);
            $oldFilename = "{$oldRawFilename} - {$oldId}.pdf";

            // base path
            $base = $this->resolveBasePath();
            $oldDocentePath = "{$base}/Actividades del Docente - {$docente->email}";
            $oldFull = "{$oldDocentePath}/informes/{$oldFilename}";

            /** ------------------------------
             * 2️⃣ Construir el nombre nuevo
             * ------------------------------*/
            $newFull = $this->buildInformeFilePath($informe);

            /** ------------------------------
             * 3️⃣ Renombrar si existe
             * ------------------------------*/
            if (File::exists($oldFull)) {

                File::ensureDirectoryExists(dirname($newFull));

                if ($oldFull !== $newFull) {
                    File::move($oldFull, $newFull);
                }
            }

        } catch (\Throwable $e) {
            Log::error('Error en InformeObserver@updated', [
                'informe_id' => $informe->id,
                'error' => $e->getMessage(),
            ]);
        }
    }


    /* -----------------------------------------------------------------
       Helpers — COPIADOS desde tu InformeMediaObserver
    ------------------------------------------------------------------*/

    private function buildInformeFilePath($informe): string
    {
        $base = $this->resolveBasePath();
        $docente = auth()->user();
        $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
        $informesFolder = "{$docentePath}/informes";

        $tipoLabel = $informe->tipo_actividad_label;
        $estName = $informe->estudiante?->nombre_completo ?? 'sin_estudiante';

        $raw = "{$tipoLabel} - {$estName}";
        $rawFilename = $this->sanitizeFileName($raw);
        $filename = "{$rawFilename} - {$informe->id}.pdf";

        File::ensureDirectoryExists($informesFolder);

        return "{$informesFolder}/{$filename}";
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
        return trim(mb_substr($san, 0, 200));
    }

    private function tipoLabelFromKey(?string $key): ?string
    {
        if (! $key) return null;

        $map = [
            'orientacion_evaluacion_trabajos_grado' => 'Orientación y evaluación de trabajos de grado',
            'investigacion_aprobada'                => 'Investigación aprobada',
            'proyeccion_social_registrada'          => 'Proyección social registrada',
            'cooperacion_interinstitucional'        => 'Cooperación interinstitucional',
            'crecimiento_personal_profesional'      => 'Crecimiento personal y profesional',
            'actividades_administrativas'           => 'Actividades administrativas',
            'otras_actividades'                     => 'Otras actividades',
            'compartidas_horas_semanales'           => 'Compartidas con las horas semanales',
        ];

        return $map[$key] ?? $key;
    }
}
