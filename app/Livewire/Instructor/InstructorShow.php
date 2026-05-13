<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InstructorShow extends Component
{
    use WithFileUploads;

    public Instructor $instructor;
    public $firma;

    public function mount(Instructor $instructor): void
    {
        if ($this->currentUserIsInstructor() && ! $this->canAccessInstructor((int) $instructor->id)) {
            abort(403, 'No tienes permiso para ver este instructor.');
        }

        // Problema #21: Cargar cursos que enseña el instructor
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

    public function saveFirma(): void
    {
        if (! Auth::user()?->can('instructor.edit')) {
            abort(403, 'No tienes permiso para actualizar la firma.');
        }

        if ($this->currentUserIsInstructor() && ! $this->canAccessInstructor((int) $this->instructor->id)) {
            abort(403, 'No tienes permiso para actualizar la firma de este instructor.');
        }

        $this->validate([
            'firma' => ['required', 'image', 'max:2048'],
        ], [
            'firma.required' => 'Debes seleccionar una imagen para la firma.',
            'firma.image' => 'La firma debe ser una imagen válida.',
            'firma.max' => 'La firma no puede pesar más de 2 MB.',
        ]);

        if ($this->instructor->path_firma && Storage::disk('public')->exists($this->instructor->path_firma)) {
            Storage::disk('public')->delete($this->instructor->path_firma);
        }

        $this->instructor->update([
            'path_firma' => $this->firma->store('firmas', 'public'),
        ]);

        $this->instructor->refresh();
        $this->firma = null;

        session()->flash('success', 'Firma actualizada correctamente.');
    }

    private function canAccessInstructor(int $instructorId): bool
    {
        $currentInstructorId = $this->resolveCurrentInstructorId();

        return $currentInstructorId !== null && $currentInstructorId === $instructorId;
    }

    private function currentUserIsInstructor(): bool
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;

        return (bool) ($currentUser?->roles?->contains('name', 'instructor'));
    }

    private function resolveCurrentInstructorId(): ?int
    {
        $authUser = Auth::user();

        if (! $authUser) {
            return null;
        }

        return Instructor::resolveIdFromAuthEmail($authUser->email);
    }
}
