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
    }
}
