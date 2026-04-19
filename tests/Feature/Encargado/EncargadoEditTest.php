<?php

namespace Tests\Feature\Encargado;

use App\Livewire\Encargado\EncargadoEdit;
use App\Models\Encargado;
use App\Models\Feligres;
use App\Models\Persona;
use App\Models\TenantIglesia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EncargadoEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_encargado_profile_fields_can_be_updated(): void
    {
        $persona = Persona::create([
            'dni' => '0801199900011',
            'primer_nombre' => 'Ana',
            'primer_apellido' => 'Pérez',
            'telefono' => '1111-1111',
            'email' => 'ana@old.test',
            'sexo' => 'F',
            'fecha_nacimiento' => '1990-01-01',
        ]);

        $iglesia = TenantIglesia::create([
            'nombre' => 'Parroquia Central',
            'direccion' => 'Centro',
            'parroco_nombre' => 'Pbro. Test',
            'estado' => 'Activo',
        ]);

        $feligres = Feligres::create([
            'id_persona' => $persona->id,
            'id_iglesia' => $iglesia->id,
            'estado' => 'Activo',
        ]);

        $encargado = Encargado::create([
            'id_feligres' => $feligres->id,
            'estado' => 'Activo',
        ]);

        Livewire::test(EncargadoEdit::class, ['encargado' => $encargado])
            ->set('telefono', '9999-0000')
            ->set('email', 'ana@new.test')
            ->set('fecha_nacimiento', '1991-02-02')
            ->set('sexo', 'F')
            ->call('update')
            ->assertHasNoErrors();

        $persona->refresh();

        $this->assertSame('9999-0000', $persona->telefono);
        $this->assertSame('ana@new.test', $persona->email);
        $this->assertSame('1991-02-02', $persona->fecha_nacimiento->format('Y-m-d'));
        $this->assertSame('F', $persona->sexo);
    }
}
