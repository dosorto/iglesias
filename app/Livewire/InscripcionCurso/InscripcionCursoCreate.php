<?php

namespace App\Livewire\InscripcionCurso;

use Livewire\Component;
use App\Models\InscripcionCurso;
use App\Models\Curso;
use App\Models\Feligres;
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
    public string $nombreBusqueda = '';
    public array $resultadosBusqueda = [];
    public ?string $nombreInstructor = null;

    public function mount(): void
    {
        $this->fecha_inscripcion = now()->format('Y-m-d');

        $cursoId = request()->query('curso_id');

        if ($cursoId && Curso::where('id', $cursoId)->exists()) {
            $this->curso_id = $cursoId;
            $this->cargarInstructorDesdeCurso($cursoId);
        }
    }

    public function updatedFeligresId($value): void
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

    public function updated($property, $value): void
    {
        if ($property === 'curso_id') {
            $this->cargarInstructorDesdeCurso($value);
        }
    }

    protected function cargarInstructorDesdeCurso($cursoId): void
    {
        $this->nombreInstructor = '';

        if (! $cursoId) {
            return;
        }

        $curso = Curso::with('instructor.feligres.persona')->find($cursoId);

        if (
            $curso &&
            $curso->instructor &&
            $curso->instructor->feligres &&
            $curso->instructor->feligres->persona
        ) {
            $this->nombreInstructor = $curso->instructor->feligres->persona->nombre_completo;
        }
    }

    public function siguientePaso(): void
    {
        if ($this->paso === 1) {
            $this->validate([
                'curso_id' => 'required|exists:cursos,id',
                'feligres_id' => 'required|exists:feligres,id',
            ]);
        }

        if ($this->paso < 2) {
            $this->paso++;
        }
    }

    public function anteriorPaso(): void
    {
        if ($this->paso > 1) {
            $this->paso--;
        }
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
                    'aprobado' => 0,
                    'certificado_emitido' => 0,
                    'fecha_certificado' => null,
                    'updated_by' => Auth::id(),
                    'deleted_by' => null,
                ]);

                session()->flash('success', 'La inscripción eliminada fue restaurada correctamente.');
                return redirect()->route('curso.show', $this->curso_id);
            }

            session()->flash('error', 'Esta persona ya está inscrita en este curso.');
            return;
        }

        InscripcionCurso::create([
            'curso_id' => $this->curso_id,
            'feligres_id' => $this->feligres_id,
            'fecha_inscripcion' => $this->fecha_inscripcion,
            'aprobado' => 0,
            'certificado_emitido' => 0,
            'fecha_certificado' => null,
            'created_by' => Auth::id(),
        ]);

        session()->flash('success', 'Inscripción creada correctamente.');
        return redirect()->route('curso.show', $this->curso_id);
    }

    public function buscarPersona(): void
    {
        $termino = trim($this->nombreBusqueda);

        $this->resultadosBusqueda = [];
        $this->feligres_id = null;
        $this->personaSeleccionada = null;

        if ($termino === '') {
            session()->flash('error', 'Ingresa un nombre para buscar.');
            return;
        }

        $feligreses = Feligres::with('persona')
            ->whereDoesntHave('instructor')
            ->whereHas('persona', function ($q) use ($termino) {
                $q->whereRaw(
                    "CONCAT_WS(' ', primer_nombre, segundo_nombre, primer_apellido, segundo_apellido) LIKE ?",
                    ['%' . $termino . '%']
                );
            })
            ->limit(10)
            ->get();

        if ($feligreses->isEmpty()) {
            session()->flash('error', 'No se encontraron personas con ese nombre.');
            return;
        }

        if ($feligreses->count() === 1) {
            $this->seleccionarPersona($feligreses->first()->id);
            return;
        }

        $this->resultadosBusqueda = $feligreses->map(function ($feligres) {
            return [
                'id' => $feligres->id,
                'nombre' => $feligres->persona->nombre_completo ?? 'N/A',
                'dni' => $feligres->persona->dni ?? 'N/A',
            ];
        })->toArray();
    }

    public function seleccionarPersona(int $feligresId): void
    {
        $feligres = Feligres::with('persona')->find($feligresId);

        if (! $feligres) {
            session()->flash('error', 'No se pudo seleccionar la persona.');
            return;
        }

        $this->feligres_id = $feligres->id;

        $this->personaSeleccionada = [
            'nombre' => $feligres->persona->nombre_completo ?? '',
            'dni' => $feligres->persona->dni ?? '',
        ];

        $this->resultadosBusqueda = [];
        $this->nombreBusqueda = $feligres->persona->nombre_completo ?? '';
    }

    public function limpiarPersonaSeleccionada(): void
    {
        $this->feligres_id = null;
        $this->personaSeleccionada = null;
        $this->nombreBusqueda = '';
        $this->resultadosBusqueda = [];
    }

    public function render()
    {
        return view('livewire.inscripcion-curso.inscripcion-curso-create', [
            'cursos' => Curso::orderBy('nombre')->get(),
            'feligreses' => Feligres::with('persona')->get(),
        ]);
    }
}