<?php

namespace App\Livewire\Curso;

use Livewire\Component;
use App\Models\Curso;
use App\Models\Encargado;
use App\Models\TipoCurso;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;

class CursoCreate extends Component
{
    public int $paso = 1;

    public $nombre = '';
    public $fecha_inicio = null;
    public $fecha_fin = null;
    public $estado = 'Activo';
    public $encargado_id = null;

    public $buscar_tipo_curso = '';
    public $tipo_curso_id = null;
    public $tipoCursoResultados = [];

    public $buscar_instructor = '';
    public $instructorResultados = [];
    public $instructor_id = null;

    public function mount(): void
    {
        $encargado = Encargado::first();

        if ($encargado) {
            $this->encargado_id = $encargado->id;
        }
    }

    public function siguientePaso(): void
    {
        if ($this->paso === 1) {
            $this->validate([
                'nombre' => ['required', 'max:200', 'regex:/[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]/'],
                'fecha_inicio' => ['nullable', 'date'],
                'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
                'estado' => ['required', 'in:Activo,Finalizado,Cancelado'],
            ], [
                'nombre.required' => 'El nombre del curso es obligatorio.',
                'nombre.max' => 'El nombre del curso no puede exceder 200 caracteres.',
                'nombre.regex' => 'El nombre del curso debe contener al menos una letra.',
                'fecha_inicio.date' => 'La fecha de inicio no es válida.',
                'fecha_fin.date' => 'La fecha de fin no es válida.',
                'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado seleccionado no es válido.',
            ]);
        }

        if ($this->paso === 2) {
            if (!$this->tipo_curso_id) {
                $this->addError('buscar_tipo_curso', 'Debe seleccionar un tipo de curso.');
                return;
            }
        }

        if ($this->paso < 3) {
            $this->paso++;
        }
    }

    public function anteriorPaso(): void
    {
        if ($this->paso > 1) {
            $this->paso--;
        }
    }

    public function updatedBuscarTipoCurso(): void
    {
        $this->resetErrorBag('buscar_tipo_curso');

        if (strlen(trim($this->buscar_tipo_curso)) < 2) {
            $this->tipoCursoResultados = [];
            return;
        }

        $this->tipoCursoResultados = TipoCurso::where('nombre_curso', 'like', '%' . trim($this->buscar_tipo_curso) . '%')
            ->limit(5)
            ->get();
    }

    public function seleccionarTipoCurso($id): void
    {
        $tipo = TipoCurso::find($id);

        if (!$tipo) {
            $this->addError('buscar_tipo_curso', 'El tipo de curso seleccionado no existe.');
            return;
        }

        $this->tipo_curso_id = $tipo->id;
        $this->buscar_tipo_curso = $tipo->nombre_curso;
        $this->tipoCursoResultados = [];
        $this->resetErrorBag('buscar_tipo_curso');
    }

    public function resetTipoCurso(): void
    {
        $this->tipo_curso_id = null;
        $this->buscar_tipo_curso = '';
        $this->tipoCursoResultados = [];
        $this->resetErrorBag('buscar_tipo_curso');
    }

    public function updatedBuscarInstructor(): void
    {
        $this->resetErrorBag('buscar_instructor');

        if (strlen(trim($this->buscar_instructor)) < 2) {
            $this->instructorResultados = [];
            return;
        }

        $busqueda = trim($this->buscar_instructor);

        $this->instructorResultados = Instructor::whereHas('feligres.persona', function ($q) use ($busqueda) {
            $q->where('primer_nombre', 'like', '%' . $busqueda . '%')
              ->orWhere('primer_apellido', 'like', '%' . $busqueda . '%');
        })
        ->with('feligres.persona')
        ->limit(5)
        ->get();
    }

    public function seleccionarInstructor($id): void
    {
        $inst = Instructor::with('feligres.persona')->find($id);

        if (!$inst || !$inst->feligres || !$inst->feligres->persona) {
            $this->addError('buscar_instructor', 'El instructor seleccionado no es válido.');
            return;
        }

        $this->instructor_id = $inst->id;
        $this->buscar_instructor = $inst->feligres->persona->nombre_completo;
        $this->instructorResultados = [];
        $this->resetErrorBag('buscar_instructor');
    }

    public function resetInstructor(): void
    {
        $this->instructor_id = null;
        $this->buscar_instructor = '';
        $this->instructorResultados = [];
        $this->resetErrorBag('buscar_instructor');
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => ['required', 'max:200', 'regex:/[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ]/'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:Activo,Finalizado,Cancelado'],
            'encargado_id' => ['required', 'exists:encargado,id'],
            'tipo_curso_id' => ['required', 'exists:tipos_curso,id'],
            'instructor_id' => ['required', 'exists:instructores,id'],
        ], [
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'nombre.max' => 'El nombre del curso no puede exceder 200 caracteres.',
            'nombre.regex' => 'El nombre del curso debe contener al menos una letra.',
            'fecha_inicio.date' => 'La fecha de inicio no es válida.',
            'fecha_fin.date' => 'La fecha de fin no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha fin debe ser igual o posterior a la fecha inicio.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'encargado_id.required' => 'Debe existir un encargado para registrar el curso.',
            'encargado_id.exists' => 'El encargado seleccionado no existe.',
            'tipo_curso_id.required' => 'Debe seleccionar un tipo de curso.',
            'tipo_curso_id.exists' => 'El tipo de curso seleccionado no existe.',
            'instructor_id.required' => 'Debe seleccionar un instructor.',
            'instructor_id.exists' => 'El instructor seleccionado no existe.',
        ]);

        Curso::create([
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estado,
            'encargado_id' => $this->encargado_id,
            'tipo_curso_id' => $this->tipo_curso_id,
            'instructor_id' => $this->instructor_id,
            'created_by' => Auth::id(),
        ]);

        session()->flash('success', 'Curso creado correctamente.');

        return redirect()->route('curso.index');
    }

    public function render()
    {
        return view('livewire.curso.curso-create', [
            'encargados' => Encargado::with('feligres.persona')->get(),
        ]);
    }
}