<?php

namespace Database\Seeders;

use App\Models\Iglesias;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IglesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Iglesias::create([
            'nombre' => 'Jesucristo el unico Camino',
            'direccion' => 'Calle Principal, Colonia Central', // ESTO FALTABA
            'telefono' => '2222-3333',
            'email' => 'contacto@iglesia.com',
            'parroco_nombre' => 'Juan Pérez',
            'estado' => 'activo',
        ]);

        Iglesias::create([
            'nombre' => 'La Esperanza',
            'direccion' => 'Av. de la Paz, Barrio El Centro',
            'telefono' => '2222-4444',
            'email' => 'esperanza@iglesia.com',
            'parroco_nombre' => 'María Rodríguez',
            'estado' => 'activo',
        ]);

        Iglesias::create([
            'nombre' => 'Monte Santo',
            'direccion' => 'Cerro Alto, Lote 45',
            'telefono' => '2222-5555',
            'email' => 'montesanto@iglesia.com',
            'parroco_nombre' => 'Pedro Martínez',
            'estado' => 'activo',
        ]);

        Iglesias::create([
            'nombre' => 'San Pablo Apóstol',
            'direccion' => 'Barrio El Centro, Avenida Principal',
            'telefono' => '2222-1111',
            'email' => 'sanpablo@iglesia.com',
            'parroco_nombre' => 'Juan Carlos López',
            'estado' => 'activo',
        ]);

        Iglesias::create([
            'nombre' => 'Nuestra Señora de Guadalupe',
            'direccion' => 'Colonia Guadalupe, Calle 3',
            'telefono' => '2222-2222',
            'email' => 'guadalupe@iglesia.com',
            'parroco_nombre' => 'Luis Fernando Ruiz',
            'estado' => 'activo',
        ]);

        Iglesias::create([
            'nombre' => 'San José Obrero',
            'direccion' => 'Colonia La Esperanza, Avenida 5',
            'telefono' => '2222-3333',
            'email' => 'sanjose@iglesia.com',
            'parroco_nombre' => 'Miguel Ángel Torres',
            'estado' => 'activo',
        ]);

        Iglesias::create([
            'nombre' => 'Sagrado Corazón de Jesús',
            'direccion' => 'Barrio El Carmen, Calle Real',
            'telefono' => '2222-4444',
            'email' => 'sagradocorazon@iglesia.com',
            'parroco_nombre' => 'Carlos Mejía',
            'estado' => 'activo',
        ]);

        Iglesias::create([
            'nombre' => 'Santa María de los Ángeles',
            'direccion' => 'Colonia Los Pinos, Calle 8',
            'telefono' => '2222-6666',
            'email' => 'santamaria@iglesia.com',
            'parroco_nombre' => 'Andrés Molina',
            'estado' => 'activo',
        ]);

        Iglesias::create([
            'nombre' => 'Cristo Rey',
            'direccion' => 'Residencial Las Flores, Bloque B',
            'telefono' => '2222-7777',
            'email' => 'cristorey@iglesia.com',
            'parroco_nombre' => 'Roberto Sánchez',
            'estado' => 'inactivo', // para probar badge rojo
        ]);

        Iglesias::create([
            'nombre' => 'San Francisco de Asís',
            'direccion' => 'Barrio Abajo, Avenida 2',
            'telefono' => '2222-8888',
            'email' => 'sanfrancisco@iglesia.com',
            'parroco_nombre' => 'Daniel Herrera',
            'estado' => 'activo',
        ]);
    }
}
