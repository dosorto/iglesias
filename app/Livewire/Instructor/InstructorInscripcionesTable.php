<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InscripcionCurso;

class InstructorInscripcionesTable extends Component
{
    use WithPagination;

    public $feligresId;
    public $instructor;

    public function mount($instructor)
    {
        $this->instructor = $instructor;
        $this->feligresId = $instructor->feligres->id;
    }

    public function render()
{
    $inscripciones = InscripcionCurso::with([
        'curso.instructor.feligres.persona',
        'feligres.persona'
    ])
    ->whereHas('curso', function ($query) {
        $query->where('instructor_id', $this->instructor->id);
    })
    ->latest()
    ->paginate(10);

    return view('livewire.instructor.instructor-inscripciones-table', [
        'inscripciones' => $inscripciones,
        'instructor' => $this->instructor
    ]);
}
}