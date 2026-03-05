<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;
use App\Models\Iglesias;
use App\Models\Encargado;
use App\Models\TipoCurso;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;

class CursoCreate extends Component
{

    public int $paso = 1;

    // PASO 1
    public $nombre = '';
    public $fecha_inicio;
    public $fecha_fin;
    public $estado = 'Activo';

    public $iglesia_id;
    public $encargado_id;

    // PASO 2
    public $buscar_tipo_curso = '';
    public $tipo_curso_id = null;
    public $tipoCursoResultados = [];

    // PASO 3
    public $buscar_instructor = '';
    public $instructorResultados = [];
    public $instructor_id = null;

    public function mount()
    {
        $encargado = Encargado::first();

        if ($encargado) {
            $this->encargado_id = $encargado->id;
        }
    }

    // ======================
    // WIZARD
    // ======================

    public function siguientePaso()
    {

        if($this->paso === 1){

            $this->validate([
                'nombre'=>['required','max:200','regex:/[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]/'],
                'iglesia_id'=>'required'
            ],[
                'nombre.regex'=>'El nombre del curso debe contener al menos una letra.',
            ]);

        }

        if($this->paso === 2){

            if(!$this->tipo_curso_id){
                $this->addError('buscar_tipo_curso','Debe seleccionar un tipo de curso');
                return;
            }

        }

        $this->paso++;

    }

    public function anteriorPaso()
    {
        $this->paso--;
    }

    // ======================
    // AUTOCOMPLETE TIPO CURSO
    // ======================

    public function updatedBuscarTipoCurso()
    {

        if(strlen($this->buscar_tipo_curso) < 2){
            $this->tipoCursoResultados = [];
            return;
        }

        $this->tipoCursoResultados = TipoCurso::where('nombre_curso','like','%'.$this->buscar_tipo_curso.'%')
            ->limit(5)
            ->get();

    }

    public function seleccionarTipoCurso($id)
    {
        $tipo = TipoCurso::find($id);

        $this->tipo_curso_id = $tipo->id;
        $this->buscar_tipo_curso = $tipo->nombre_curso;
        $this->tipoCursoResultados = [];
    }

    public function resetTipoCurso()
    {
        $this->tipo_curso_id = null;
        $this->buscar_tipo_curso = '';
        $this->tipoCursoResultados = [];
    }

    // ======================
    // AUTOCOMPLETE INSTRUCTOR
    // ======================

    public function updatedBuscarInstructor()
    {

        if(strlen($this->buscar_instructor) < 2){
            $this->instructorResultados = [];
            return;
        }

        $this->instructorResultados = Instructor::whereHas('feligres.persona',function($q){

            $q->where('primer_nombre','like','%'.$this->buscar_instructor.'%')
              ->orWhere('primer_apellido','like','%'.$this->buscar_instructor.'%');

        })->limit(5)->get();

    }

    public function seleccionarInstructor($id)
    {
        $inst = Instructor::with('feligres.persona')->find($id);

        $this->instructor_id = $inst->id;
        $this->buscar_instructor = $inst->feligres->persona->nombre_completo;
        $this->instructorResultados = [];
    }

    public function resetInstructor()
    {
        $this->instructor_id = null;
        $this->buscar_instructor = '';
        $this->instructorResultados = [];
    }

    // ======================
    // GUARDAR
    // ======================

    public function guardar()
    {

        $this->validate([
            'instructor_id'=>'required',
            'tipo_curso_id'=>'required'
        ]);

        Curso::create([

            'nombre'=>$this->nombre,
            'fecha_inicio'=>$this->fecha_inicio,
            'fecha_fin'=>$this->fecha_fin,
            'estado'=>$this->estado,

            'iglesia_id'=>$this->iglesia_id,
            'encargado_id'=>$this->encargado_id,
            'tipo_curso_id'=>$this->tipo_curso_id,
            'instructor_id'=>$this->instructor_id,

            'created_by'=>Auth::id()

        ]);

        session()->flash('success','Curso creado correctamente');

        return redirect()->route('curso.index');

    }

    public function render()
    {

        $centralConn = config('tenancy.central_connection','mysql');

        return view('livewire.curso.curso-create',[

            'iglesias'=>Iglesias::on($centralConn)
                ->where('estado','Activo')
                ->orderBy('nombre')
                ->get(),

            'encargados'=>Encargado::with('feligres.persona')->get()

        ]);

    }

}