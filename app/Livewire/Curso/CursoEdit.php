<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;
use App\Models\TipoCurso;
use App\Models\Instructor;

class CursoEdit extends Component
{
    public Curso $curso;

    public $nombre;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado;
    public $tipo_curso_id;
    public $instructor_id;

    public function mount(Curso $curso)
    {
        $this->curso = $curso;

        $this->nombre = $curso->nombre;
        $this->fecha_inicio = $curso->fecha_inicio;
        $this->fecha_fin = $curso->fecha_fin;
        $this->estado = $curso->estado;
        $this->tipo_curso_id = $curso->tipo_curso_id;
        $this->instructor_id = $curso->instructor_id;
    }

    public function update()
    {
        $this->validate([
            'nombre' => ['required', 'max:200', 'regex:/[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]/'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:Activo,Finalizado,Cancelado'],
            'tipo_curso_id' => ['required', 'exists:tipos_curso,id'],
            'instructor_id' => ['required', 'exists:instructores,id'],
        ], [
            'nombre.regex' => 'El nombre del curso debe contener al menos una letra.',
        ]);

        $this->curso->update([
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,
            'tipo_curso_id' => $this->tipo_curso_id,
            'instructor_id' => $this->instructor_id,
        ]);

        session()->flash('success', 'Curso actualizado');

        return redirect()->route('curso.index');
    }

    public function render()
    {
        return view('livewire.curso.curso-edit', [
            'tipos' => TipoCurso::orderBy('nombre_curso')->get(),
            'instructores' => Instructor::with('feligres.persona')->get()
        ]);
    }
}