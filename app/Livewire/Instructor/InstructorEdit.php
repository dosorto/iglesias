<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InstructorEdit extends Component
{
    use WithFileUploads;

    public Instructor $instructor;
    public string $fecha_ingreso = '';
    public string $estado = 'Activo';
    public $firma;

    public function mount(Instructor $instructor): void
    {
        if ($this->currentUserIsInstructor() && ! $this->canAccessInstructor((int) $instructor->id)) {
            abort(403, 'No tienes permiso para editar este instructor.');
        }

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
