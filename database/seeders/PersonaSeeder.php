<?php

namespace Database\Seeders;

use App\Models\Persona;
use Faker\Provider\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Persona::create([
            'dni'              => '0801199001234',
            'primer_nombre'    => 'Carlos',
            'segundo_nombre'   => 'Andrés',
            'primer_apellido'  => 'Martínez',
            'segundo_apellido' => 'López',
            'fecha_nacimiento' => '1990-05-15',
            'sexo'             => 'M',
            'telefono'         => '9801-1234',
            'email'            => 'carlos.martinez@gmail.com',
        ]);

        Persona::create([
            'dni'              => '0801199505678',
            'primer_nombre'    => 'María',
            'segundo_nombre'   => 'José',
            'primer_apellido'  => 'Hernández',
            'segundo_apellido' => 'Reyes',
            'fecha_nacimiento' => '1995-03-22',
            'sexo'             => 'F',
            'telefono'         => '9802-5678',
            'email'            => 'maria.hernandez@gmail.com',
        ]);

        Persona::create([
            'dni'              => '0801198809012',
            'primer_nombre'    => 'Juan',
            'segundo_nombre'   => null,
            'primer_apellido'  => 'García',
            'segundo_apellido' => 'Flores',
            'fecha_nacimiento' => '1988-11-08',
            'sexo'             => 'M',
            'telefono'         => '9803-9012',
            'email'            => 'juan.garcia@hotmail.com',
        ]);

        Persona::create([
            'dni'              => '0801200203456',
            'primer_nombre'    => 'Ana',
            'segundo_nombre'   => 'Lucía',
            'primer_apellido'  => 'Rodríguez',
            'segundo_apellido' => 'Mendoza',
            'fecha_nacimiento' => '2002-07-30',
            'sexo'             => 'F',
            'telefono'         => null,
            'email'            => 'ana.rodriguez@gmail.com',
        ]);

        Persona::create([
            'dni'              => '0801197507890',
            'primer_nombre'    => 'Roberto',
            'segundo_nombre'   => 'Emilio',
            'primer_apellido'  => 'Sánchez',
            'segundo_apellido' => null,
            'fecha_nacimiento' => '1975-01-18',
            'sexo'             => 'M',
            'telefono'         => '9805-7890',
            'email'            => null,
        ]);

        Persona::create([
            'dni'              => '0801199212345',
            'primer_nombre'    => 'Laura',
            'segundo_nombre'   => 'Patricia',
            'primer_apellido'  => 'Díaz',
            'segundo_apellido' => 'Castillo',
            'fecha_nacimiento' => '1992-09-12',
            'sexo'             => 'F',
            'telefono'         => '9806-2345',
            'email'            => 'laura.diaz@yahoo.com',
        ]);

        Persona::create([
            'dni'              => '0801198567890',
            'primer_nombre'    => 'Miguel',
            'segundo_nombre'   => null,
            'primer_apellido'  => 'Torres',
            'segundo_apellido' => 'Vásquez',
            'fecha_nacimiento' => '1985-12-03',
            'sexo'             => 'M',
            'telefono'         => '9807-6789',
            'email'            => 'miguel.torres@gmail.com',
        ]);

        Persona::create([
            'dni'              => '0801200034567',
            'primer_nombre'    => 'Sofía',
            'segundo_nombre'   => 'Isabel',
            'primer_apellido'  => 'Morales',
            'segundo_apellido' => 'Aguilar',
            'fecha_nacimiento' => '2000-06-25',
            'sexo'             => 'F',
            'telefono'         => '9808-3456',
            'email'            => 'sofia.morales@gmail.com',
        ]);

        Persona::create([
            'dni'              => '0801196889012',
            'primer_nombre'    => 'Francisco',
            'segundo_nombre'   => 'Javier',
            'primer_apellido'  => 'Ramírez',
            'segundo_apellido' => 'Ortega',
            'fecha_nacimiento' => '1968-04-14',
            'sexo'             => 'M',
            'telefono'         => null,
            'email'            => 'francisco.ramirez@hotmail.com',
        ]);

        Persona::create([
            'dni'              => '0801199845678',
            'primer_nombre'    => 'Valeria',
            'segundo_nombre'   => 'Alejandra',
            'primer_apellido'  => 'Cruz',
            'segundo_apellido' => 'Pineda',
            'fecha_nacimiento' => '1998-08-19',
            'sexo'             => 'F',
            'telefono'         => '9810-4567',
            'email'            => 'valeria.cruz@gmail.com',
        ]);
    }
}
