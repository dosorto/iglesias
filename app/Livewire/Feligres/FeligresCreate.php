<?php

namespace App\Livewire\Feligres;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use Illuminate\Validation\Rule;

class FeligresCreate extends Component
{
    // ── Búsqueda por DNI / nombre ────────────────────────────────────
    public string $persona_dni    = '';
    public string $persona_estado = 'idle'; // idle | found | sin_persona | multiple
    public ?int   $persona_id     = null;
    public ?array $personaSeleccionada  = null;
    public array  $resultadosBusqueda   = [];

    // ── Crear persona inline ────────────────────────────────────────
    public bool   $showCrearPersona  = false;
    public string $p_dni             = '';
    public string $p_primer_nombre   = '';
    public string $p_segundo_nombre  = '';
    public string $p_primer_apellido  = '';
    public string $p_segundo_apellido = '';
    public string $p_telefono         = '';
    public string $p_email            = '';
    public string $p_sexo             = '';
    public string $p_fecha_nacimiento = '';

    // ── Datos feligrés ──────────────────────────────────────────────
    public ?int   $id_iglesia    = null;
    public string $fecha_ingreso = '';
    public string $estado        = 'Activo';

    public function mount(): void
    {
        $this->fecha_ingreso = now()->format('Y-m-d');

        // En tenant, preseleccionar la iglesia local automáticamente
        if (session('tenant')) {
            $this->id_iglesia = TenantIglesia::currentId();
        }
    }

    // ── Buscar persona por DNI / nombre / apellido ──────────────────
    public function buscarPersona(): void
    {
        $busqueda = trim($this->persona_dni);

        if (empty($busqueda)) {
            $this->addError('persona_dni', 'Ingresa un DNI, nombre o apellido para buscar.');
            return;
        }

        $this->resultadosBusqueda = [];

        // Si es numérico buscar por DNI exacto
        if (ctype_digit($busqueda)) {
            $persona = Persona::where('dni', $busqueda)->first();

            if (! $persona) {
                $this->persona_id          = null;
                $this->personaSeleccionada = null;
                $this->persona_estado      = 'sin_persona';
                return;
            }

            $this->seleccionarPersona($persona->id);
            return;
        }

        // Buscar por nombre o apellido
        $personas = Persona::where(function ($q) use ($busqueda) {
            $q->where('primer_nombre',    'like', "%{$busqueda}%")
              ->orWhere('segundo_nombre',   'like', "%{$busqueda}%")
              ->orWhere('primer_apellido',  'like', "%{$busqueda}%")
              ->orWhere('segundo_apellido', 'like', "%{$busqueda}%");
        })->orderBy('primer_apellido')->limit(15)->get();

        if ($personas->isEmpty()) {
            $this->persona_id          = null;
            $this->personaSeleccionada = null;
            $this->persona_estado      = 'sin_persona';
            return;
        }

        if ($personas->count() === 1) {
            $this->seleccionarPersona($personas->first()->id);
            return;
        }

        // Múltiples resultados: mostrar listado para seleccionar
        $this->resultadosBusqueda = $personas->map(fn ($p) => [
            'id'              => $p->id,
            'dni'             => $p->dni,
            'nombre_completo' => $p->nombre_completo,
            'telefono'        => $p->telefono,
            'email'           => $p->email,
        ])->toArray();
        $this->persona_estado = 'multiple';
    }

    // ── Seleccionar persona del listado ─────────────────────────────
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

        $this->persona_estado     = 'found';
        $this->showCrearPersona   = false;
        $this->resultadosBusqueda = [];
    }

    // ── Deseleccionar persona ───────────────────────────────────────
    public function limpiarPersona(): void
    {
        $this->persona_id          = null;
        $this->personaSeleccionada = null;
        $this->persona_dni         = '';
        $this->persona_estado      = 'idle';
        $this->showCrearPersona    = false;
        $this->resultadosBusqueda  = [];
    }

    // ── Abrir / cancelar form crear persona ─────────────────────────
    public function abrirCrearPersona(): void
    {
        $this->p_dni = ctype_digit(trim($this->persona_dni)) ? trim($this->persona_dni) : '';
        $this->reset(['p_primer_nombre', 'p_segundo_nombre', 'p_primer_apellido',
                      'p_segundo_apellido', 'p_telefono', 'p_email', 'p_sexo', 'p_fecha_nacimiento']);
        $this->resetErrorBag();
        $this->showCrearPersona = true;
    }

    public function cancelarCrearPersona(): void
    {
        $this->showCrearPersona = false;
    }

    // ── Crear persona inline ─────────────────────────────────────────
    public function crearPersona(): void
    {
        $this->validate([
            'p_dni'             => ['required', 'string', 'min:8', 'max:20', Rule::unique('personas', 'dni')],
            'p_primer_nombre'    => ['required', 'string', 'max:150', 'regex:/^[a-záéíóúüñA-ZÁÉÍÓÚÜÑ\s]+$/u'],
            'p_primer_apellido'  => ['required', 'string', 'max:100', 'regex:/^[a-záéíóúüñA-ZÁÉÍÓÚÜÑ\s]+$/u'],
            'p_segundo_nombre'   => ['nullable', 'string', 'max:150', 'regex:/^[a-záéíóúüñA-ZÁÉÍÓÚÜÑ\s]+$/u'],
            'p_segundo_apellido' => ['nullable', 'string', 'max:100', 'regex:/^[a-záéíóúüñA-ZÁÉÍÓÚÜÑ\s]+$/u'],
            'p_sexo'             => ['required', 'in:Masculino,Femenino'],
            'p_fecha_nacimiento' => ['required', 'date', 'before:today'],
            'p_telefono'         => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'p_email'            => ['nullable', 'email:rfc,dns', 'max:255'],
        ], [
            'p_telefono.required'        => 'El teléfono es obligatorio.',
            'p_telefono.regex'           => 'El teléfono solo puede contener números, +, - y espacios.',
            'p_dni.required'             => 'El número de identidad es obligatorio.',
            'p_dni.min'                  => 'El DNI debe tener al menos 8 caracteres.',
            'p_dni.unique'               => 'Ya existe una persona con ese DNI.',
            'p_primer_nombre.required'    => 'El primer nombre es obligatorio.',
            'p_primer_nombre.regex'        => 'El primer nombre solo puede contener letras.',
            'p_primer_apellido.required'   => 'El primer apellido es obligatorio.',
            'p_primer_apellido.regex'      => 'El primer apellido solo puede contener letras.',
            'p_segundo_nombre.regex'       => 'El segundo nombre solo puede contener letras.',
            'p_segundo_apellido.regex'     => 'El segundo apellido solo puede contener letras.',
            'p_sexo.required'             => 'El sexo es obligatorio.',
            'p_sexo.in'                   => 'El sexo debe ser Masculino o Femenino.',
            'p_fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'p_fecha_nacimiento.date'     => 'La fecha de nacimiento no es válida.',
            'p_fecha_nacimiento.before'   => 'La fecha de nacimiento debe ser anterior a hoy.',
            'p_email.email'               => 'El formato del correo electrónico no es válido.',
        ]);

        $persona = Persona::create([
            'dni'               => $this->p_dni,
            'primer_nombre'     => $this->p_primer_nombre,
            'segundo_nombre'    => $this->p_segundo_nombre ?: null,
            'primer_apellido'   => $this->p_primer_apellido,
            'segundo_apellido'  => $this->p_segundo_apellido ?: null,
            'sexo'              => $this->p_sexo === 'Masculino' ? 'M' : ($this->p_sexo === 'Femenino' ? 'F' : null),
            'fecha_nacimiento'  => $this->p_fecha_nacimiento,
            'telefono'          => $this->p_telefono ?: null,
            'email'             => $this->p_email ?: null,
        ]);

        $this->seleccionarPersona($persona->id);
        session()->flash('persona_nueva', "Persona \"{$persona->nombre_completo}\" creada y seleccionada.");
    }

    // ── Guardar feligrés ─────────────────────────────────────────────
    public function guardar(): void
    {
        if (session('tenant')) {
            $this->id_iglesia = TenantIglesia::currentId();
        }

        $this->validate([
            'persona_id'    => ['required', 'integer', 'exists:personas,id'],
            'id_iglesia'    => ['required', 'integer', 'exists:iglesias,id'],
            'fecha_ingreso' => ['nullable', 'date'],
            'estado'        => ['required', 'in:Activo,Inactivo'],
        ], [
            'persona_id.required' => 'Debes seleccionar una persona.',
            'id_iglesia.required' => 'Debes seleccionar una iglesia.',
        ]);

        $existe = Feligres::where('id_persona', $this->persona_id)
            ->where('id_iglesia', $this->id_iglesia)
            ->exists();

        if ($existe) {
            $this->addError('id_iglesia', 'Esta persona ya está registrada como feligrés en esa iglesia.');
            return;
        }

        Feligres::create([
            'id_persona'    => $this->persona_id,
            'id_iglesia'    => $this->id_iglesia,
            'fecha_ingreso' => $this->fecha_ingreso ?: null,
            'estado'        => $this->estado,
        ]);

        session()->flash('success', 'Feligrés registrado correctamente.');
        $this->redirect(route('feligres.index'), navigate: false);
    }

    public function render()
    {
        if (session('tenant')) {
            $iglesias = collect([TenantIglesia::current()])->filter();
        } else {
            $iglesias = Iglesias::where('estado', 'Activo')->orderBy('nombre')->get();
        }

        return view('livewire.feligres.feligres-create', [
            'iglesias' => $iglesias,
        ]);
    }
}
