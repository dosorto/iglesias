<?php

namespace App\Livewire\InscripcionCurso;

use Livewire\Component;
use App\Models\InscripcionCurso;
use App\Models\Curso;
use App\Models\Feligres;
use App\Models\Instructor; 
use Illuminate\Support\Facades\Auth;

class InscripcionCursoCreate extends Component
{
    public int $paso = 1;

    // PASO 1
    public $curso_id = null;
    public $feligres_id = null;
    public ?array $personaSeleccionada = null;

    // PASO 2
    public $fecha_inscripcion = null;
    public $aprobado = 0;
    public $certificado_emitido = 0;
    public $fecha_certificado = null;
    public $dniBusqueda = '';
    public ?string $nombreInstructor = null;

    public function mount()
    {
        $this->fecha_inscripcion = now()->format('Y-m-d');
    }

    public function updatedFeligresId($value)
    {
        $feligres = Feligres::with('persona')->find($value);

        if ($feligres) {
            $this->personaSeleccionada = [
                'nombre' => $feligres->persona->nombre_completo ?? '',
                'dni' => $feligres->persona->dni ?? '',
            ];
        } else {
            $this->personaSeleccionada = null;
        }
    }

    public function updated($property, $value)
    {
        if ($property === 'curso_id') {
            $this->nombreInstructor = '';

            if (!$value) {
                return;
            }

            $curso = Curso::with('instructor.feligres.persona')->find($value);

            if (
                $curso &&
                $curso->instructor &&
                $curso->instructor->feligres &&
                $curso->instructor->feligres->persona
            ) {
                $this->nombreInstructor = $curso->instructor->feligres->persona->nombre_completo;
            }
        }
    }

    public function siguientePaso()
    {
        if ($this->paso === 1) {
            $this->validate([
                'curso_id' => 'required|exists:cursos,id',
                'feligres_id' => 'required|exists:feligres,id',
            ]);
        }

        $this->paso++;
    }

    public function anteriorPaso()
    {
        $this->paso--;
    }

    public function guardar()
{
    $this->validate([
        'curso_id' => 'required|exists:cursos,id',
        'feligres_id' => 'required|exists:feligres,id',
        'fecha_inscripcion' => 'required|date',
    ]);

    $existe = InscripcionCurso::withTrashed()
        ->where('curso_id', $this->curso_id)
        ->where('feligres_id', $this->feligres_id)
        ->first();

    if ($existe) {
        if ($existe->deleted_at !== null) {
            $existe->restore();

            $existe->update([
                'fecha_inscripcion' => $this->fecha_inscripcion,
                'aprobado' => $this->aprobado,
                'certificado_emitido' => $this->certificado_emitido,
                'fecha_certificado' => $this->fecha_certificado,
                'updated_by' => Auth::id(),
                'deleted_by' => null,
            ]);

            session()->flash('success', 'La inscripción eliminada fue restaurada correctamente.');
            return redirect()->route('inscripcion-curso.index');
        }

        session()->flash('error', 'Esta persona ya está inscrita en este curso.');
        return;
    }

    InscripcionCurso::create([
        'curso_id' => $this->curso_id,
        'feligres_id' => $this->feligres_id,
        'fecha_inscripcion' => $this->fecha_inscripcion,
        'aprobado' => $this->aprobado,
        'certificado_emitido' => $this->certificado_emitido,
        'fecha_certificado' => $this->fecha_certificado,
        'created_by' => Auth::id(),
    ]);

    session()->flash('success', 'Inscripción creada correctamente');
    return redirect()->route('inscripcion-curso.index');
}

    public function buscarPersona()
{
    if (!$this->dniBusqueda) {
        session()->flash('error', 'Ingresa un DNI para buscar');
        return;
    }

    $feligres = Feligres::with('persona')
        ->whereHas('persona', function ($q) {
            $q->where('dni', $this->dniBusqueda);
        })
        ->first();

    if (!$feligres) {
        session()->flash('error', 'No se encontró persona con ese DNI');
        return;
    }

    // 🔹 VALIDAR SI ES INSTRUCTOR
    $esInstructor = Instructor::where('feligres_id', $feligres->id)->exists();

    if ($esInstructor) {
        session()->flash('error', 'Esta persona es un instructor y no puede inscribirse como estudiante.');
        return;
    }

    $this->feligres_id = $feligres->id;

    $this->personaSeleccionada = [
        'nombre' => $feligres->persona->nombre_completo,
        'dni' => $feligres->persona->dni,
    ];
}

    public function render()
    {
        return view('livewire.inscripcion-curso.inscripcion-curso-create', [
            'cursos' => Curso::orderBy('nombre')->get(),
            'feligreses' => Feligres::with('persona')->get(),
        ]);
    }
}