<?php

namespace App\Models;

use Andreia\FilamentUiSwitcher\Models\Traits\HasUiPreferences;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;
    use HasUiPreferences;

    protected $table = 'users';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts modernos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ui_preferences' => 'array',
        ];
    }

    /* -----------------------------------------------------------
     |  RELACIONES DOCENTE ↔ SISTEMA ACADÉMICO
     |-----------------------------------------------------------
     */

    /**
     * Actividades docentes del profesor.
     */
    public function actividadDocente(): User|\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ActividadDocente::class, 'user_id');
    }

    /**
     * Actividad complementaria (1:1).
     */
    public function actividadComplementaria(): User|\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ActividadComplementaria::class, 'user_id');
    }

    /**
     * Asignaturas dictadas por el docente.
     */
    public function asignaturas(): \Illuminate\Database\Eloquent\Relations\HasManyThrough|User
    {
        return $this->hasManyThrough(
            Asignatura::class,
            ActividadDocente::class,
            'user_id',
            'actividad_docente_id',
            'id',
            'id'
        );
    }

    /**
     * Estudiantes asignados al docente.
     */
    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'user_id');
    }

    /**
     * Asistencias registradas por el docente.
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'user_id');
    }

    /**
     * Tutorías realizadas por el docente.
     */
    public function tutorias(): HasMany
    {
        return $this->hasMany(Tutoria::class, 'user_id');
    }

    public function planTrabajo(): HasOne
    {
        return $this->hasOne(PlanTrabajo::class);
    }

    public function informes()
    {
        return $this->hasMany(Informe::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
