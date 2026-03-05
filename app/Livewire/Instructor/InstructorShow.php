<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use App\Models\Instructor;

class InstructorShow extends Component
{
    public Instructor $instructor;

    public function mount(Instructor $instructor): void
    {
        $this->instructor = $instructor->load([
            'feligres.persona',
            'feligres.iglesia',
            'auditLogs',
        ]);
    }

    public function render()
    {
        return view('livewire.instructor.instructor-show');
    }
}
