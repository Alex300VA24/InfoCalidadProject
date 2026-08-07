<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Presidente de Cotejo', 'slug' => 'presidente_cotejo', 'description' => 'Gestiona el instrumento de cotejo y el informe técnico curricular'],
            ['name' => 'Director de Escuela', 'slug' => 'director_escuela', 'description' => 'Aprueba informes técnicos y supervisa el sistema de gestión de calidad'],
            ['name' => 'Secretaría Académica', 'slug' => 'secretaria', 'description' => 'Gestión administrativa, sílabos y solicitudes de recursos'],
            ['name' => 'Docente', 'slug' => 'docente', 'description' => 'Sube sílabos y participa en la ejecución del plan curricular'],
            ['name' => 'Estudiante', 'slug' => 'estudiante', 'description' => 'Accede a su récord académico, sílabos y certificados'],
            ['name' => 'Coordinador de Admisión', 'slug' => 'coordinador_admision', 'description' => 'Gestiona procesos de admisión y postulantes'],
            ['name' => 'Personal de Matrícula', 'slug' => 'personal_matricula', 'description' => 'Gestiona matrículas y órdenes de pago'],
            ['name' => 'Tutor Académico', 'slug' => 'tutor_academico', 'description' => 'Gestiona tutorías académicas'],
            ['name' => 'Relaciones Internacionales', 'slug' => 'relaciones_internacionales', 'description' => 'Gestiona movilidad y becas internacionales'],
            ['name' => 'Unidad de Grados y Títulos', 'slug' => 'unidad_grados_titulos', 'description' => 'Gestiona certificados, grados y títulos'],
            ['name' => 'Seguimiento de Egresado', 'slug' => 'seguimiento_egresado', 'description' => 'Gestiona el seguimiento de egresados'],
        ];

        foreach ($roles as $data) {
            Role::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
