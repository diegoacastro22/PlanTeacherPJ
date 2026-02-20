<?php

namespace App\Observers;

use App\Models\Tutoria;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TutoriaObserver
{
    /**
     * Handle the Tutoria "created" event.
     */
    public function created(Tutoria $tutoria): void
    {
        // Obtener todos los estudiantes de la asignatura
        $estudiantes = $tutoria->asignatura->estudiantes;
        // Crear un detalle de tutoría para cada estudiante
        foreach ($estudiantes as $estudiante) {
            $tutoria->detalles()->create([
                'estudiante_id' => $estudiante->id,
                'asistio' => false,
            ]);
        }
    }

    /**
     * Handle the Tutoria "updated" event.
     */
    public function updated(Tutoria $tutoria): void
    {
        try {
            if (! $tutoria->wasChanged('finalizada') || ! $tutoria->finalizada) {
                return;
            }

            $asignatura = $tutoria->asignatura;
            if (! $asignatura) {
                Log::warning('Tutoria actualizada pero no tiene asignatura relacionada', [
                    'tutoria_id' => $tutoria->id
                ]);
                return;
            }

            $actividad = $asignatura->actividadDocente;
            $docente = $actividad->docente ?? auth()->user();

            if (! $docente) {
                Log::warning('No se pudo obtener docente para guardar excel de tutoría', [
                    'tutoria_id' => $tutoria->id
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

            $fechaStr = $tutoria->fecha ? $tutoria->fecha->format('Y-m-d') : now()->format('Y-m-d');
            $rawFilename = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo} - Tutoria - {$fechaStr}.xlsx";
            $filename = $this->sanitizeFileName($rawFilename);

            $fullPath = "{$asignaturaFolder}/tutorias/{$filename}";

            $detalles = $tutoria->detalles()->with('estudiante')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // --- ENCABEZADOS ---
            $headers = [
                'Código estudiante',
                'Nombre completo',
                'Correo',
                'Asistió',
                'Tutoria ID',
                'Detalle Tutoria ID'
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

                $sheet->setCellValueExplicit(
                    $this->excelColumn(4) . $row,
                    $detalle->asistio ? 'SI' : 'NO',
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                $sheet->setCellValueExplicit(
                    $this->excelColumn(5) . $row,
                    (string) $tutoria->id,
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

            Log::info('Excel de tutoría generado', [
                'tutoria_id' => $tutoria->id,
                'path' => $fullPath
            ]);

        } catch (\Throwable $e) {
            Log::error('Error generando excel de tutoría', [
                'tutoria_id' => $tutoria->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle the Tutoria "deleted" event.
     */
    public function deleted(Tutoria $tutoria): void
    {
        try {
            $asignatura = $tutoria->asignatura;

            if (! $asignatura) {
                return;
            }

            $actividad = $asignatura->actividadDocente;
            $docente = $actividad->docente ?? null;

            if (! $docente) {
                return;
            }

            $base = $this->resolveBasePath();

            $docentePath = "{$base}/Actividades del Docente - {$docente->email}";
            $asignaturasPath = "{$docentePath}/asignaturas";

            $nombreAsignatura = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo}";
            $asignaturaFolder = "{$asignaturasPath}/{$nombreAsignatura}";

            $fechaStr = $tutoria->fecha ? $tutoria->fecha->format('Y-m-d') : now()->format('Y-m-d');
            $rawFilename = "{$asignatura->nombre} - {$asignatura->codigo} - Grupo {$asignatura->grupo} - Tutoria - {$fechaStr}.xlsx";
            $filename = $this->sanitizeFileName($rawFilename);

            $fullPath = "{$asignaturaFolder}/tutorias/{$filename}";

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }

        } catch (\Throwable $e) {
            \Log::error('Error eliminando excel de tutoría', [
                'tutoria_id' => $tutoria->id,
                'error' => $e->getMessage(),
            ]);
        }
    }


    /* ======================================================
       ============ MÉTODOS PRIVADOS CLONADOS ===============
       ======================================================*/

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
        $san = preg_replace('/[\\\\\/:*?"<>|]+/', '_', $name);
        $san = preg_replace('/\s+/', ' ', $san);
        $san = trim($san);
        return mb_substr($san, 0, 200);
    }

    private function excelColumn(int $index): string
    {
        $index--;
        $column = '';

        while ($index >= 0) {
            $column = chr($index % 26 + 65) . $column;
            $index = floor($index / 26) - 1;
        }

        return $column;
    }

    /**
     * Handle the Tutoria "restored" event.
     */
    public function restored(Tutoria $tutoria): void
    {
        //
    }

    /**
     * Handle the Tutoria "force deleted" event.
     */
    public function forceDeleted(Tutoria $tutoria): void
    {
        //
    }
}
