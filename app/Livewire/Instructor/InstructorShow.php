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

        if (! $authUser || ! $authUser->email) {
            return null;
        }

        $email = strtolower(trim($authUser->email));

        $instructorByEmail = Instructor::whereHas('feligres.persona', function ($q) use ($email) {
            $q->whereRaw('LOWER(email) = ?', [$email]);
        })->first();

        if ($instructorByEmail) {
            return (int) $instructorByEmail->id;
        }

        if (preg_match('/^instructor\.([0-9]+)(?:\+[0-9]+)?@tenant\.local$/', $email, $matches)) {
            $dni = $matches[1] ?? null;

            if ($dni) {
                $instructorByDni = Instructor::whereHas('feligres.persona', function ($q) use ($dni) {
                    $q->where('dni', $dni);
                })->first();

                return $instructorByDni ? (int) $instructorByDni->id : null;
            }
        }

        return null;
    }
}
