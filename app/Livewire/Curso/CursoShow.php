<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;

class CursoShow extends Component
{
    public Curso $curso;

    public function mount(Curso $curso)
    {
        $this->curso = $curso->load([
            'tipoCurso',
            'instructor.feligres.persona',
            'encargado.feligres.persona',
            'auditLogs',
        ]);
    }

    public function render()
    {
        return view('livewire.curso.curso-show');
    }
}