<?php

namespace App\Livewire\InscripcionCurso;

use Livewire\Component;
use App\Models\InscripcionCurso;

class InscripcionCursoShow extends Component
{

    public InscripcionCurso $inscripcion;

    public function mount(InscripcionCurso $inscripcion)
    {
        $this->inscripcion = $inscripcion->load([
            'curso.instructor.feligres.persona',
            'curso.instructors.feligres.persona',
            'feligres.persona'
        ]);
    }

    public function render()
    {
        return view('livewire.inscripcion-curso.inscripcion-curso-show');
    }

}