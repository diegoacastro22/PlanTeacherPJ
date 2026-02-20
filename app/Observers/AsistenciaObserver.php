<?php

namespace App\Observers;

use App\Models\Asistencia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class AsistenciaObserver
{
    /**
     * Handle the Asistencia "created" event.
     */
    public function created(Asistencia $asistencia): void
    {
        // Obtener todos los estudiantes de la asignatura
        $estudiantes = $asistencia->asignatura->estudiantes;

        // Crear un detalle de asistencia para cada estudiante
        foreach ($estudiantes as $estudiante) {
            $asistencia->detalles()->create([
                'estudiante_id' => $estudiante->id,
                'asistio' => false,
            ]);
        }
    }

    /**
     * Handle the Asistencia "updated" event.
     */
    public function updated(Asistencia $asistencia): void
    {
        try {
            if (! $asistencia->wasChanged('finalizada') || ! $asistencia->finalizada) {
                return;
            }

            $asignatura = $asistencia->asignatura;
            if (! $asignatura) {
                Log::warning('Asistencia actualizada pero no tiene asignatura relacionada', [
                    'asistencia_id' => $asistencia->id
                ]);
                return;
            }

            $actividad = $asignatura->actividadDocente;
            $docente = $actividad->docente ?? auth()->user();

            if (! $docente) {
                Log::warning('No se pudo obtener docente para guardar excel de asistencia', [
                    'asistencia_id' => $asistencia->id
                ]);
                return;
            }

            $base = $this->resolveBasePath();

            $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
            $asignaturasPath = "{$docentePath}/asignaturas";

            $nombreAsignatura = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo}";
            $asignaturaFolder = "{$asignaturasPath}/{$nombreAsignatura}";

            if (! File::exists($asignaturaFolder)) {
                File::makeDirectory($asignaturaFolder, 0755, true);
            }

            $fechaStr = $asistencia->fecha ? $asistencia->fecha->format('Y-m-d') : now()->format('Y-m-d');
            $rawFilename = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo} - {$fechaStr}.xlsx";
            $filename = $this->sanitizeFileName($rawFilename);

            $fullPath = "{$asignaturaFolder}/asistencias/{$filename}";

            $detalles = $asistencia->detalles()->with('estudiante')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // --- ENCABEZADOS ---
            $headers = [
                'Código estudiante',
                'Nombre completo',
                'Correo',
                'Asistió',
                'Asistencia ID',
                'Detalle ID'
            ];

            $col = 1;
            foreach ($headers as $h) {
                $sheet->setCellValue(
                    $this->excelColumn($col) . '1',
                    $h
                );
                $col++;
            }

            // --- FILAS ---
            $row = 2;
            foreach ($detalles as $detalle) {
                $est = $detalle->estudiante;

                // impedir que excel convierta números → texto explícito
                $sheet->setCellValueExplicit(
                    $this->excelColumn(1) . $row,
                    (string) ($est->codigo ?? ''),
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                $sheet->setCellValueExplicit(
                    $this->excelColumn(2) . $row,
                    (string) ($est->nombre_completo ?? ''),
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                $sheet->setCellValueExplicit(
                    $this->excelColumn(3) . $row,
                    (string) ($est->correo ?? ''),
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                // SI / NO en lugar de 1 / 0
                $sheet->setCellValueExplicit(
                    $this->excelColumn(4) . $row,
                    $detalle->asistio ? 'SI' : 'NO',
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                // IDs también como texto para evitar notación científica
                $sheet->setCellValueExplicit(
                    $this->excelColumn(5) . $row,
                    (string) $asistencia->id,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                $sheet->setCellValueExplicit(
                    $this->excelColumn(6) . $row,
                    (string) $detalle->id,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                $row++;
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($fullPath);

            Log::info('Excel de asistencia generado', [
                'asistencia_id' => $asistencia->id,
                'path' => $fullPath
            ]);

        } catch (\Throwable $e) {
            Log::error('Error generando excel de asistencia', [
                'asistencia_id' => $asistencia->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    private function resolveBasePath(): string
    {
        $path = env('DOCUMENTS_BASE_PATH', '../documentos/actividades_docente');

        if ($this->isAbsolutePath($path)) {
            return $path;
        }

        return base_path($path);
    }

    private function isAbsolutePath(string $path): bool
    {
        if (preg_match('/^[a-zA-Z]:[\\\\\/]/', $path)) return true;
        return str_starts_with($path, '/');
    }

    private function sanitizeFileName(string $name): string
    {
        // Reemplaza caracteres no válidos por guion bajo y limita longitud razonable
        $san = preg_replace('/[\\\\\/:*?"<>|]+/', '_', $name);
        $san = preg_replace('/\s+/', ' ', $san);
        $san = trim($san);
        return mb_substr($san, 0, 200);
    }

    private function excelColumn(int $index): string
    {
        $index--; // corregir porque Excel empieza en 1 y PHP en 0
        $column = '';

        while ($index >= 0) {
            $column = chr($index % 26 + 65) . $column;
            $index = floor($index / 26) - 1;
        }

        return $column;
    }

    /**
     * Handle the Asistencia "deleted" event.
     */
    public function deleted(Asistencia $asistencia): void
    {
        try {
            $asignatura = $asistencia->asignatura;

            if (! $asignatura) {
                return;
            }

            $actividad = $asignatura->actividadDocente;
            $docente = $actividad->docente ?? null;

            if (! $docente) {
                return;
            }

            // Ruta base igual que en updated()
            $base = $this->resolveBasePath();

            // Carpeta base de la asignatura
            $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
            $asignaturasPath = "{$docentePath}/asignaturas";

            $nombreAsignatura = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo}";
            $asignaturaFolder = "{$asignaturasPath}/{$nombreAsignatura}";

            // Nombre limpio exacto como en updated()
            $fechaStr = $asistencia->fecha ? $asistencia->fecha->format('Y-m-d') : now()->format('Y-m-d');
            $rawFilename = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo} - {$fechaStr}.xlsx";
            $filename = $this->sanitizeFileName($rawFilename);

            // Ruta final del archivo
            $fullPath = "{$asignaturaFolder}/asistencias/{$filename}";

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }

        } catch (\Throwable $e) {
            \Log::error('Error eliminando excel de asistencia', [
                'asistencia_id' => $asistencia->id,
                'error' => $e->getMessage(),
            ]);
        }
    }


    /**
     * Handle the Asistencia "restored" event.
     */
    public function restored(Asistencia $asistencia): void
    {
        //
    }

    /**
     * Handle the Asistencia "force deleted" event.
     */
    public function forceDeleted(Asistencia $asistencia): void
    {
        //
    }
}
