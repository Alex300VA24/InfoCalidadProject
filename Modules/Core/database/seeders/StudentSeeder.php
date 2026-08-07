<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\Career;
use Modules\Core\Models\Role;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $careerId = Career::where('code', 'ING-SIS')->value('id');
        $roleId = Role::where('slug', 'estudiante')->value('id');

        $students = [
            ['name' => 'Estudiante 1', 'email' => 'estudiante1@test.com', 'dni' => '12312312', 'telefono' => '999222333', 'codigo' => '2024-00001', 'ciclo' => 'VI'],
            ['name' => 'Estudiante 2', 'email' => 'estudiante2@test.com', 'dni' => '45645645', 'telefono' => '999333444', 'codigo' => '2024-00002', 'ciclo' => 'VI'],
            ['name' => 'Estudiante 3', 'email' => 'estudiante3@test.com', 'dni' => '78978978', 'telefono' => '999444555', 'codigo' => '2023-00010', 'ciclo' => 'VIII'],
        ];

        foreach ($students as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $roleId,
                    'career_id' => $careerId,
                    'dni' => $data['dni'],
                    'telefono' => $data['telefono'],
                    'email_verified_at' => now(),
                ]
            );

            Student::updateOrCreate(
                ['codigo' => $data['codigo']],
                [
                    'user_id' => $user->id,
                    'ciclo' => $data['ciclo'],
                    'fecha_nacimiento' => now()->subYears(rand(19, 22)),
                    'estado' => 'activo',
                ]
            );
        }
    }
}
