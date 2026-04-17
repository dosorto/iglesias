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
    }

    public function update()
    {
        if ($this->isInstructorView && $this->currentInstructorId) {
            $this->instructor_id = $this->currentInstructorId;
        }

        $this->validate([
            'nombre' => ['required', 'max:200', 'regex:/[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]/'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:Activo,Finalizado,Cancelado'],
            'tipo_curso_id' => ['required', 'exists:tipos_curso,id'],
            'instructor_id' => [
                'required',
                'exists:instructores,id',
                $this->isInstructorView && $this->currentInstructorId
                    ? Rule::in([$this->currentInstructorId])
                    : null,
            ],
        ], [
            'nombre.regex' => 'El nombre del curso debe contener al menos una letra.',
        ]);

        $this->curso->update([
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,
            'tipo_curso_id' => $this->tipo_curso_id,
            'instructor_id' => $this->instructor_id,
        ]);

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

        if (! $this->currentInstructorId || (int) $curso->instructor_id !== (int) $this->currentInstructorId) {
            abort(403, 'No tienes permiso para editar este curso.');
        }
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