<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InstructorShow extends Component
{
    public Instructor $instructor;

    public function mount(Instructor $instructor): void
    {
        if ($this->currentUserIsInstructor() && ! $this->canAccessInstructor((int) $instructor->id)) {
            abort(403, 'No tienes permiso para ver este instructor.');
        }

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
