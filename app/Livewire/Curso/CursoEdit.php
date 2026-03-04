<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;
use App\Models\Iglesias;
use App\Models\TipoCurso;
use App\Models\Instructor;

class CursoEdit extends Component
{

    public Curso $curso;

    public $nombre;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado;

    public $iglesia_id;
    public $tipo_curso_id;
    public $instructor_id;

    public function mount(Curso $curso)
    {

        $this->curso = $curso;

        $this->nombre = $curso->nombre;
        $this->fecha_inicio = $curso->fecha_inicio;
        $this->fecha_fin = $curso->fecha_fin;
        $this->estado = $curso->estado;

        $this->iglesia_id = $curso->iglesia_id;
        $this->tipo_curso_id = $curso->tipo_curso_id;
        $this->instructor_id = $curso->instructor_id;

    }

    public function update()
    {

        $this->validate([
            'nombre' => 'required|max:200',
            'estado' => 'required',
            'iglesia_id' => 'required',
            'tipo_curso_id' => 'required',
            'instructor_id' => 'required',
        ]);

        $this->curso->update([

            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,

            'iglesia_id' => $this->iglesia_id,
            'tipo_curso_id' => $this->tipo_curso_id,
            'instructor_id' => $this->instructor_id,

        ]);

        session()->flash('success','Curso actualizado');

        return redirect()->route('curso.index');

    }

    public function render()
    {

        return view('livewire.curso.curso-edit',[

            'iglesias' => Iglesias::orderBy('nombre')->get(),
            'tipos' => TipoCurso::orderBy('nombre')->get(),
            'instructores' => Instructor::with('feligres.persona')->get()

        ]);

    }

}