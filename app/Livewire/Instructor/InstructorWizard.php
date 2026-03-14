<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Instructor;
use Illuminate\Validation\Rule;

class InstructorWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public string $dni = '';
    public ?int $persona_id = null;
    public string $telefono = '';
    public string $email = '';

    public string $primer_nombre = '';
    public string $segundo_nombre = '';
    public string $primer_apellido = '';
    public string $segundo_apellido = '';

    public bool $personaExiste = false;

    public $firma;

    /* =======================
        PASO 1 – BUSCAR DNI
    ========================*/
    public function buscarPersona()
    {
        $this->validate([
            'dni' => ['required','digits:13']
        ], [
            'dni.digits' => 'El DNI debe contener exactamente 13 números.'
        ]);

        $persona = Persona::where('dni',$this->dni)->first();

        if($persona){
            $this->personaExiste = true;
            $this->persona_id = $persona->id;
            $this->primer_nombre = $persona->primer_nombre;
            $this->segundo_nombre = $persona->segundo_nombre ?? '';
            $this->primer_apellido = $persona->primer_apellido;
            $this->segundo_apellido = $persona->segundo_apellido ?? '';
            $this->telefono = $persona->telefono ?? '';
            $this->email = $persona->email ?? '';
        } else {
            $this->personaExiste = false;
            $this->persona_id = null;
        }
    }

    public function nextStep()
    {
        if(!$this->personaExiste){
            $this->validate([
                'dni' => ['required', 'digits:13'],
                'primer_nombre' => ['required', 'regex:/^[\pL\s]+$/u'],
                'primer_apellido' => ['required', 'regex:/^[\pL\s]+$/u'],
                'telefono' => ['nullable', 'digits:8'],
            ], [
                'dni.required' => 'El DNI es obligatorio.',
                'dni.digits' => 'El DNI debe contener exactamente 13 números.',

                'primer_nombre.required' => 'El primer nombre es obligatorio.',
                'primer_nombre.regex' => 'El nombre solo puede contener letras.',

                'primer_apellido.required' => 'El primer apellido es obligatorio.',
                'primer_apellido.regex' => 'El apellido solo puede contener letras.',

                'telefono.digits' => 'El teléfono debe contener exactamente 8 números.',
            ]);

            $persona = Persona::create([
                'dni' => $this->dni,
                'primer_nombre' => $this->primer_nombre,
                'segundo_nombre' => $this->segundo_nombre ?: null,
                'primer_apellido' => $this->primer_apellido,
                'segundo_apellido' => $this->segundo_apellido ?: null,
                'telefono' => $this->telefono ?: null,
                'email' => $this->email ?: null,
            ]);

            $this->persona_id = $persona->id;
        }

        $this->step = 2;
    }

    public function previousStep()
    {
        $this->step = 1;
    }

    public function limpiarCampos()
    {
        $this->reset([
            'dni',
            'persona_id',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'telefono',
            'email',
            'personaExiste'
        ]);
    }

    /* =======================
        PASO 2 – GUARDAR
    ========================*/
    public function guardarInstructor()
    {
        $this->validate([
            'firma' => ['required','image','max:2048']
        ]);

        $iglesiaId = \App\Models\TenantIglesia::currentId();

        if (! $iglesiaId) {
            $this->addError('dni', 'No se encontró la iglesia activa de la sesión.');
            return;
        }

        $feligres = Feligres::firstOrCreate(
            [
                'id_persona' => $this->persona_id,
                'id_iglesia' => $iglesiaId
            ],
            [
                'estado' => 'Activo'
            ]
        );

        if(Instructor::where('feligres_id',$feligres->id)->exists()){
            $this->addError('dni','Ya es instructor.');
            return;
        }

        $path = $this->firma->store('firmas','public');

        Instructor::create([
            'feligres_id' => $feligres->id,
            'path_firma' => $path,
        ]);

        return redirect()->route('instructor.index')
            ->with('success','Instructor creado correctamente');
    }

    public function render()
    {
        return view('livewire.instructor.instructor-wizard');
    }
}