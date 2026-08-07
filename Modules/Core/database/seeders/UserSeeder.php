<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\Career;
use Modules\Core\Models\Role;
use Modules\Core\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $careerId = Career::where('code', 'ING-SIS')->value('id');
        $roleId = fn (string $slug) => Role::where('slug', $slug)->value('id');

        $users = [
            ['name' => 'Presidente Cotejo', 'email' => 'presidente@test.com', 'slug' => 'presidente_cotejo', 'dni' => '12345678', 'telefono' => '999111000'],
            ['name' => 'Director Escuela', 'email' => 'director@test.com', 'slug' => 'director_escuela', 'dni' => '87654321', 'telefono' => '999222000'],
            ['name' => 'Secretaria Académica', 'email' => 'secretaria@test.com', 'slug' => 'secretaria', 'dni' => '11122333', 'telefono' => '999333000'],
            ['name' => 'Docente 1', 'email' => 'docente1@test.com', 'slug' => 'docente', 'dni' => '44455666', 'telefono' => '999444000'],
            ['name' => 'Docente 2', 'email' => 'docente2@test.com', 'slug' => 'docente', 'dni' => '77788999', 'telefono' => '999555000'],
            ['name' => 'Coordinador Admisión', 'email' => 'admision@test.com', 'slug' => 'coordinador_admision', 'dni' => '22233444', 'telefono' => '999666000'],
            ['name' => 'Personal Matrícula', 'email' => 'matricula@test.com', 'slug' => 'personal_matricula', 'dni' => '55566777', 'telefono' => '999777000'],
            ['name' => 'Tutor Académico', 'email' => 'tutor@test.com', 'slug' => 'tutor_academico', 'dni' => '88899000', 'telefono' => '999888000'],
            ['name' => 'Relaciones Internacionales', 'email' => 'internacional@test.com', 'slug' => 'relaciones_internacionales', 'dni' => '11222333', 'telefono' => '999999000'],
            ['name' => 'Unidad Grados y Títulos', 'email' => 'grados@test.com', 'slug' => 'unidad_grados_titulos', 'dni' => '44555666', 'telefono' => '999000111'],
            ['name' => 'Seguimiento Egresado', 'email' => 'egresados@test.com', 'slug' => 'seguimiento_egresado', 'dni' => '77888999', 'telefono' => '999111222'],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $roleId($data['slug']),
                    'career_id' => $careerId,
                    'dni' => $data['dni'],
                    'telefono' => $data['telefono'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
