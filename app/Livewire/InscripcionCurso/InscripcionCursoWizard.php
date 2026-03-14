<?php

namespace App\Livewire\InscripcionCurso;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Curso;
use App\Models\InscripcionCurso;

class InscripcionCursoWizard extends Component
{

    public int $step = 1;

    public string $dni = '';
    public ?int $persona_id = null;
    public ?int $feligres_id = null;

    public string $primer_nombre = '';
    public string $segundo_nombre = '';
    public string $primer_apellido = '';
    public string $segundo_apellido = '';

    public bool $personaExiste = false;

    public $curso_id;
    public $fecha_inscripcion;
    public $aprobado = null;
    public $certificado_emitido = false;
    public $fecha_certificado;

    /* =======================
        PASO 1 – BUSCAR DNI
    ========================*/

    public function buscarPersona()
    {

        $this->validate([
            'dni' => ['required','digits:13']
        ],[
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

            $feligres = Feligres::where('id_persona',$persona->id)->first();

            if($feligres){
                $this->feligres_id = $feligres->id;
            }

        } else {

            $this->personaExiste = false;
            $this->persona_id = null;
            $this->feligres_id = null;

        }

    }

    public function nextStep()
    {

        if(!$this->personaExiste){

            $this->validate([
                'dni' => ['required','digits:13'],
                'primer_nombre' => ['required','regex:/^[\pL\s]+$/u'],
                'primer_apellido' => ['required','regex:/^[\pL\s]+$/u'],
            ],[
                'dni.required' => 'El DNI es obligatorio.',
                'dni.digits' => 'El DNI debe contener exactamente 13 números.',

                'primer_nombre.required' => 'El primer nombre es obligatorio.',
                'primer_nombre.regex' => 'El nombre solo puede contener letras.',

                'primer_apellido.required' => 'El primer apellido es obligatorio.',
                'primer_apellido.regex' => 'El apellido solo puede contener letras.',
            ]);

            $persona = Persona::create([

                'dni' => $this->dni,
                'primer_nombre' => $this->primer_nombre,
                'segundo_nombre' => $this->segundo_nombre ?: null,
                'primer_apellido' => $this->primer_apellido,
                'segundo_apellido' => $this->segundo_apellido ?: null,

            ]);

            $this->persona_id = $persona->id;

        }

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

        $this->feligres_id = $feligres->id;

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
            'feligres_id',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'personaExiste'
        ]);

    }

    /* =======================
        PASO 2 – GUARDAR
    ========================*/

    public function guardarInscripcion()
    {

        $this->validate([
            'curso_id' => ['required','exists:cursos,id'],
            'fecha_inscripcion' => ['required','date'],
        ]);

        if(InscripcionCurso::where('curso_id',$this->curso_id)
            ->where('feligres_id',$this->feligres_id)
            ->exists()){

            $this->addError('dni','Este feligrés ya está inscrito en este curso.');
            return;

        }

        InscripcionCurso::create([

            'curso_id' => $this->curso_id,
            'feligres_id' => $this->feligres_id,
            'fecha_inscripcion' => $this->fecha_inscripcion,
            'aprobado' => $this->aprobado,
            'certificado_emitido' => $this->certificado_emitido,
            'fecha_certificado' => $this->fecha_certificado,

        ]);

        return redirect()->route('inscripcion-curso.index')
            ->with('success','Inscripción creada correctamente');

    }

    public function render()
    {

        return view('livewire.inscripcion-curso.inscripcion-curso-wizard',[

            'cursos' => Curso::where('estado','Activo')->orderBy('nombre')->get()

        ]);

    }

}