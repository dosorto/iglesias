<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Instructor;

class InstructorEdit extends Component
{
    use WithFileUploads;

    public Instructor $instructor;
    public string $fecha_ingreso = '';
    public string $estado = 'Activo';
    public $firma;

    public function mount(Instructor $instructor): void
    {
        $this->instructor = $instructor;
        $this->fecha_ingreso = $instructor->fecha_ingreso?->format('Y-m-d') ?? '';
        $this->estado = $instructor->estado ?? 'Activo';
    }

    public function update(): void
    {
        $this->validate([
            'fecha_ingreso' => ['nullable', 'date'],
            'estado'        => ['required', 'in:Activo,Inactivo'],
            'firma'         => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'fecha_ingreso' => $this->fecha_ingreso ?: null,
            'estado'        => $this->estado,
        ];

        if ($this->firma) {
            $data['path_firma'] = $this->firma->store('firmas', 'public');
        }

        $this->instructor->update($data);

        session()->flash('success', 'Instructor actualizado correctamente.');
        $this->redirect(route('instructor.index'), navigate: false);
    }

    public function render()
    {
        return view('livewire.instructor.instructor-edit');
    }
}
