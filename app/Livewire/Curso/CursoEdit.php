<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;
use App\Models\TipoCurso;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CursoEdit extends Component
{
    public Curso $curso;
    public bool $isInstructorView = false;
    public ?int $currentInstructorId = null;

    public $nombre;
    public $fecha_inicio;
    public $fecha_fin;
    public $estado;
    public $tipo_curso_id;
    public $instructor_id;
    public array $instructor_ids = [];

    public function mount(Curso $curso)
    {
        $this->authorizeInstructorCourseAccess($curso);

        $this->curso = $curso;

        $this->nombre = $curso->nombre;
        $this->fecha_inicio = $curso->fecha_inicio;
        $this->fecha_fin = $curso->fecha_fin;
        $this->estado = $curso->estado;
        $this->tipo_curso_id = $curso->tipo_curso_id;
        $this->instructor_id = $curso->instructor_id;
        $this->instructor_ids = $curso->instructors()->pluck('instructores.id')->map(fn ($id) => (int) $id)->all();

        if (empty($this->instructor_ids) && $curso->instructor_id) {
            $this->instructor_ids = [(int) $curso->instructor_id];
        }
    }

    public function update()
    {
        if ($this->isInstructorView && $this->currentInstructorId) {
            $this->instructor_id = $this->currentInstructorId;
            $this->instructor_ids = [(int) $this->currentInstructorId];
        }

        $this->validate([
            'nombre' => ['required', 'max:200', 'regex:/[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]/'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:Activo,Finalizado,Cancelado'],
            'tipo_curso_id' => ['required', 'exists:tipos_curso,id'],
            'instructor_ids' => ['required', 'array', 'min:1'],
            'instructor_ids.*' => [
                'integer',
                'exists:instructores,id',
                $this->isInstructorView && $this->currentInstructorId
                    ? Rule::in([$this->currentInstructorId])
                    : null,
            ],
        ], [
            'nombre.regex' => 'El nombre del curso debe contener al menos una letra.',
        ]);

        $primaryInstructorId = (int) ($this->instructor_ids[0] ?? 0);

        if ($primaryInstructorId <= 0) {
            $this->addError('instructor_ids', 'Debe seleccionar al menos un instructor.');
            return;
        }

        $this->curso->update([
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,
            'tipo_curso_id' => $this->tipo_curso_id,
            'instructor_id' => $primaryInstructorId,
        ]);

        $this->curso->instructors()->sync(array_values(array_unique(array_map('intval', $this->instructor_ids))));

        session()->flash('success', 'Curso actualizado');

        return redirect()->route('curso.index');
    }

    public function render()
    {
        $instructores = $this->isInstructorView && $this->currentInstructorId
            ? Instructor::with('feligres.persona')->where('id', $this->currentInstructorId)->get()
            : Instructor::with('feligres.persona')->get();

        return view('livewire.curso.curso-edit', [
            'tipos' => TipoCurso::orderBy('nombre_curso')->get(),
            'instructores' => $instructores
        ]);
    }

    private function authorizeInstructorCourseAccess(Curso $curso): void
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;

        if (! $currentUser) {
            abort(403);
        }

        $this->isInstructorView = (bool) ($currentUser->roles?->contains('name', 'instructor'));

        if (! $this->isInstructorView) {
            return;
        }

        $this->currentInstructorId = $this->resolveCurrentInstructorId();

        if (! $this->currentInstructorId) {
            abort(403, 'No tienes permiso para editar este curso.');
        }

        $isAssigned = ((int) $curso->instructor_id === (int) $this->currentInstructorId)
            || $curso->instructors()->where('instructores.id', $this->currentInstructorId)->exists();

        if (! $isAssigned) {
            abort(403, 'No tienes permiso para editar este curso.');
        }
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
            return $instructorByEmail->id;
        }

        if (preg_match('/^instructor\.([0-9]+)(?:\+[0-9]+)?@tenant\.local$/', $email, $matches)) {
            $dni = $matches[1] ?? null;

            if ($dni) {
                $instructorByDni = Instructor::whereHas('feligres.persona', function ($q) use ($dni) {
                    $q->where('dni', $dni);
                })->first();

                return $instructorByDni?->id;
            }
        }

        return null;
    }
}