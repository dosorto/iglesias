<?php

namespace App\Livewire\InscripcionCurso;

use Livewire\Component;
use App\Models\InscripcionCurso;
use App\Models\Curso;
use App\Models\Feligres;
use Illuminate\Support\Facades\Auth;

class InscripcionCursoCreate extends Component
{

    public int $paso = 1;

    // ======================
    // PASO 1
    // ======================

    public $curso_id;
    public $feligres_id;

    public ?array $personaSeleccionada = null;

    // ======================
    // PASO 2
    // ======================

    public $fecha_inscripcion;
    public $aprobado = null;
    public $certificado_emitido = false;
    public $fecha_certificado;
    public $dniBusqueda;

    // ======================
    // ACTUALIZAR PERSONA
    // ======================

    public function updatedFeligresId($value)
    {

        $feligres = Feligres::with('persona')->find($value);

        if($feligres){

            $this->personaSeleccionada = [
                'nombre' => $feligres->persona->nombre_completo ?? '',
                'dni' => $feligres->persona->dni ?? ''
            ];

        }else{

            $this->personaSeleccionada = null;

        }

    }

    // ======================
    // WIZARD
    // ======================

    public function siguientePaso()
    {

        if($this->paso === 1){

            $this->validate([
                'curso_id' => 'required|exists:cursos,id',
                'feligres_id' => 'required|exists:feligres,id',
            ]);

        }

        $this->paso++;

    }

    public function anteriorPaso()
    {

        $this->paso--;

    }

    // ======================
    // GUARDAR
    // ======================

    public function guardar()
    {

        $this->validate([
            'fecha_inscripcion' => 'required|date'
        ]);

        InscripcionCurso::create([

            'curso_id' => $this->curso_id,
            'feligres_id' => $this->feligres_id,
            'fecha_inscripcion' => $this->fecha_inscripcion,
            'aprobado' => $this->aprobado,
            'certificado_emitido' => $this->certificado_emitido,
            'fecha_certificado' => $this->fecha_certificado,
            'created_by' => Auth::id()

        ]);

        session()->flash('success','Inscripción creada correctamente');

        return redirect()->route('inscripcion-curso.index');

    }
    
    public function buscarPersona()
    {
        $feligres = Feligres::with('persona')
            ->whereHas('persona', function($q){
                $q->where('dni', $this->dniBusqueda);
            })
            ->first();

        if(!$feligres){
            session()->flash('error','No se encontró persona con ese DNI');
            return;
        }

        $this->feligres_id = $feligres->id;

        $this->personaSeleccionada = [
            'nombre' => $feligres->persona->nombre_completo,
            'dni' => $feligres->persona->dni
        ];
    }
    // ======================
    // RENDER
    // ======================

    public function render()
    {

        return view('livewire.inscripcion-curso.inscripcion-curso-create',[

            'cursos' => Curso::orderBy('nombre')->get(),
            'feligreses' => Feligres::with('persona')->get()

        ]);

    }

}