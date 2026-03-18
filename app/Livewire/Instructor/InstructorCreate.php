<?php

namespace App\Livewire\Instructor;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Instructor;
use App\Models\Feligres;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class InstructorCreate extends Component
{

    use WithFileUploads;

    // ── Nueva propiedad para la firma ─────────────────────────────
    public $firma;

    // ── Búsqueda por DNI ───────────────────────────────────────────
    public string $persona_dni    = '';
    public string $persona_estado = 'idle';
    public ?int   $persona_id = null;
    public ?array $personaSeleccionada = null;

    // ── Crear persona inline ────────────────────────────────────────
    public bool   $showCrearPersona  = false;
    public string $p_dni             = '';
    public string $p_primer_nombre   = '';
    public string $p_segundo_nombre  = '';
    public string $p_primer_apellido  = '';
    public string $p_segundo_apellido = '';
    public string $p_telefono         = '';
    public string $p_email            = '';
    public string $p_fecha_nacimiento = '';
    public string $p_sexo             = '';

    // ── Datos del instructor / feligrés ────────────────────────────
    public ?int   $feligres_id = null;
    public string $fecha_ingreso = '';
    public string $estado        = 'Activo';


    public function mount(): void
    {
        $this->fecha_ingreso = now()->format('Y-m-d');
    }

    // ── Buscar persona por DNI ──────────────────────────────────────
    public function buscarPersona(): void
    {
        $dni = trim($this->persona_dni);

        if (! $dni) {
            $this->addError('persona_dni', 'Ingresa un DNI para buscar.');
            return;
        }

        $persona = Persona::where('dni', $dni)->first();

        if (! $persona) {
            $this->persona_estado = 'sin_persona';
            return;
        }

        $this->seleccionarPersona($persona->id);
    }

    // ── Seleccionar persona ─────────────────────────────────────────
    public function seleccionarPersona(int $id): void
    {
        $persona = Persona::findOrFail($id);

        $this->persona_id = $persona->id;
        $this->personaSeleccionada = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono,
            'email'           => $persona->email,
        ];

        $this->persona_estado   = 'found';
        $this->showCrearPersona = false;
    }

    // ── Deseleccionar persona ───────────────────────────────────────
    public function limpiarPersona(): void
    {
        $this->persona_id          = null;
        $this->personaSeleccionada = null;
        $this->persona_dni         = '';
        $this->persona_estado      = 'idle';
        $this->showCrearPersona    = false;
    }

    // ── Abrir form crear persona ────────────────────────────────────
    public function abrirCrearPersona(): void
    {
        $this->p_dni = ctype_digit(trim($this->persona_dni)) ? trim($this->persona_dni) : '';

        $this->reset([
            'p_primer_nombre',
            'p_segundo_nombre',
            'p_primer_apellido',
            'p_segundo_apellido',
            'p_telefono',
            'p_email',
            'p_fecha_nacimiento',
            'p_sexo'
        ]);

        $this->showCrearPersona = true;
        $this->resetErrorBag();
    }

    public function cancelarCrearPersona(): void
    {
        $this->showCrearPersona = false;
        $this->resetErrorBag();
    }

    // ── Crear persona inline ─────────────────────────────────────────
    public function crearPersona(): void
    {
        $this->validate([
            'p_dni'             => ['required', 'string', 'min:8', 'max:20', Rule::unique('personas', 'dni')],
            'p_primer_nombre'   => ['required', 'string', 'max:150'],
            'p_primer_apellido' => ['required', 'string', 'max:100'],
            'p_segundo_nombre'  => ['nullable', 'string', 'max:150'],
            'p_segundo_apellido'=> ['nullable', 'string', 'max:100'],
            'p_telefono'        => ['nullable', 'string', 'max:20'],
            'p_email'           => ['nullable', 'email', 'max:255'],
            'p_fecha_nacimiento'=> ['nullable', 'date'],
            'p_sexo'            => ['nullable', 'in:M,F'],
        ]);

        $persona = Persona::create([
            'dni'              => $this->p_dni,
            'primer_nombre'    => $this->p_primer_nombre,
            'segundo_nombre'   => $this->p_segundo_nombre ?: null,
            'primer_apellido'  => $this->p_primer_apellido,
            'segundo_apellido' => $this->p_segundo_apellido ?: null,
            'telefono'         => $this->p_telefono ?: null,
            'email'            => $this->p_email ?: null,
            'fecha_nacimiento' => $this->p_fecha_nacimiento ?: null,
            'sexo'             => $this->p_sexo === 'Masculino' ? 'M' : ($this->p_sexo === 'Femenino' ? 'F' : null),
        ]);

        $this->seleccionarPersona($persona->id);

        session()->flash('persona_nueva', "Persona \"{$persona->nombre_completo}\" creada y seleccionada.");
    }

    // ── Guardar instructor ───────────────────────────────────────────
    public function guardar(): void
    {
        $this->validate([
            'persona_id' => ['required', 'integer', 'exists:personas,id'],
            'firma'      => ['nullable', 'image', 'max:2048'],
            'fecha_ingreso' => ['nullable', 'date'],
            'estado'        => ['required', 'in:Activo,Inactivo'],
        ]);

        $iglesiaId = \App\Models\TenantIglesia::currentId();

        if (! $iglesiaId) {
            $this->addError('persona_id', 'No se encontró la iglesia activa de la sesión.');
            return;
        }

        // Crear o recuperar feligrés
        $feligres = Feligres::firstOrCreate(
            ['id_persona' => $this->persona_id],
            ['id_iglesia' => $iglesiaId]
        );

        // 🔴 VALIDACIÓN PARA EVITAR EL ERROR SQL
        $instructorExistente = Instructor::withTrashed()
            ->where('feligres_id', $feligres->id)
            ->first();

        if ($instructorExistente) { 
    
            if ($instructorExistente->trashed()) {
                $instructorExistente->restore();
            }

            session()->flash('success', 'Instructor restaurado correctamente.');

            $this->redirect(route('instructor.index'), navigate: false);
            return;   
        }

        // Guardar firma
        $pathFirma = $this->firma ? $this->firma->store('firmas', 'public') : null;

        // Crear instructor
        Instructor::create([
            'feligres_id'   => $feligres->id,
            'path_firma'    => $pathFirma,
            'fecha_ingreso' => $this->fecha_ingreso ?: null,
            'estado'        => $this->estado,
        ]);

        session()->flash('success', 'Instructor registrado correctamente.');

        $this->redirect(route('instructor.index'), navigate: false);
    }

    public function render()   
    {
        return view('livewire.instructor.instructor-create');
    }
}