<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Core\Database\Factories\UserFactory;

#[Fillable(['name', 'email', 'password', 'career_id', 'role_id', 'dni', 'telefono', 'is_active', 'text_scale', 'view_scale'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'core.users';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'text_scale' => 'integer',
            'view_scale' => 'integer',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function scopeWithRole(Builder $query, string $slug): Builder
    {
        return $query->whereHas('role', fn (Builder $q) => $q->where('slug', $slug));
    }

    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    public function roleLabel(): string
    {
        return $this->role?->name ?? 'Sin rol';
    }

    public function preferredCareer(): ?Career
    {
        return $this->career_id ? $this->career : null;
    }

    public function isPresidenteCotejo(): bool
    {
        return $this->hasRole('presidente_cotejo');
    }

    public function isDirectorEscuela(): bool
    {
        return $this->hasRole('director_escuela');
    }

    public function isSecretaria(): bool
    {
        return $this->hasRole('secretaria');
    }

    public function isDocente(): bool
    {
        return $this->hasRole('docente');
    }

    public function isEstudiante(): bool
    {
        return $this->hasRole('estudiante');
    }

    public function isCoordinadorAdmision(): bool
    {
        return $this->hasRole('coordinador_admision');
    }

    public function isPersonalMatricula(): bool
    {
        return $this->hasRole('personal_matricula');
    }

    public function isTutorAcademico(): bool
    {
        return $this->hasRole('tutor_academico');
    }

    public function isRelacionesInternacionales(): bool
    {
        return $this->hasRole('relaciones_internacionales');
    }

    public function isUnidadGradosTitulos(): bool
    {
        return $this->hasRole('unidad_grados_titulos');
    }

    public function isSeguimientoEgresado(): bool
    {
        return $this->hasRole('seguimiento_egresado');
    }
}
