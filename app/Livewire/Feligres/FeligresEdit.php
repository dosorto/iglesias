<?php

namespace App\Livewire\Feligres;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Feligres;
use App\Models\Persona;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FeligresEdit extends Component
{
    public Feligres $feligres;

    // ── Búsqueda live ──────────────────────────────────────────────
    public string $search = '';
    public ?int   $persona_id = null;
    public ?array $personaSeleccionada = null;

    // ── Datos persona ───────────────────────────────────────────────
    public string  $dni = '';
    public string  $primer_nombre = '';
    public ?string $segundo_nombre = null;
    public string  $primer_apellido = '';
    public ?string $segundo_apellido = null;
    public string  $fecha_nacimiento = '';
    public string  $sexo = '';
    public ?string $telefono = null;
    public ?string $email = null;

    // ── Datos feligrés ──────────────────────────────────────────────
    public ?int   $id_iglesia    = null;
    public string $fecha_ingreso = '';
    public string $estado        = 'Activo';

    public function mount(Feligres $feligre): void
    {
        $this->feligres = $feligre;
        $feligre->loadMissing('persona');

        // Pre-cargar datos actuales
        $this->persona_id   = $feligre->id_persona;
        $this->id_iglesia   = $feligre->id_iglesia;
        $this->fecha_ingreso = $feligre->fecha_ingreso?->format('Y-m-d') ?? '';
        $this->estado       = $feligre->estado;

        if ($feligre->persona) {
            $this->personaSeleccionada = [
                'id'              => $feligre->persona->id,
                'dni'             => $feligre->persona->dni,
                'nombre_completo' => $feligre->persona->nombre_completo,
                'telefono'        => $feligre->persona->telefono,
                'email'           => $feligre->persona->email,
            ];

            $this->cargarDatosPersona($feligre->persona);
        }

        if (session('tenant')) {
            $this->id_iglesia = TenantIglesia::currentId();
        }
    }

    // ── Resultados en vivo ──────────────────────────────────────────
    #[Computed]
    public function resultados(): \Illuminate\Support\Collection
    {
        $q = trim($this->search);

        if (strlen($q) < 2) {
            return collect();
        }

        return Persona::where(function ($query) use ($q) {
                $query->where('dni', 'like', "%{$q}%")
                      ->orWhere('primer_nombre',    'like', "%{$q}%")
                      ->orWhere('segundo_nombre',   'like', "%{$q}%")
                      ->orWhere('primer_apellido',  'like', "%{$q}%")
                      ->orWhere('segundo_apellido', 'like', "%{$q}%");
            })
            ->orderBy('primer_apellido')
            ->orderBy('primer_nombre')
            ->limit(10)
            ->get();
    }

    public function updatedSearch(): void
    {
        unset($this->resultados);
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

        $this->cargarDatosPersona($persona);

        $this->search = '';
        unset($this->resultados);
    }

    // ── Deseleccionar persona ───────────────────────────────────────
    public function limpiarPersona(): void
    {
        $this->persona_id          = null;
        $this->personaSeleccionada = null;
        $this->search              = '';
        $this->dni                 = '';
        $this->primer_nombre       = '';
        $this->segundo_nombre      = null;
        $this->primer_apellido     = '';
        $this->segundo_apellido    = null;
        $this->fecha_nacimiento    = '';
        $this->sexo                = '';
        $this->telefono            = null;
        $this->email               = null;
        unset($this->resultados);
    }

    protected function cargarDatosPersona(Persona $persona): void
    {
        $this->dni              = (string) $persona->dni;
        $this->primer_nombre    = (string) $persona->primer_nombre;
        $this->segundo_nombre   = $persona->segundo_nombre;
        $this->primer_apellido  = (string) $persona->primer_apellido;
        $this->segundo_apellido = $persona->segundo_apellido;
        $this->fecha_nacimiento = $persona->fecha_nacimiento?->format('Y-m-d') ?? '';
        $this->sexo             = (string) $persona->sexo;
        $this->telefono         = $persona->telefono;
        $this->email            = $persona->email;
    }

    // ── Guardar cambios ──────────────────────────────────────────────
    public function guardar(): void
    {
        if (session('tenant')) {
            $this->id_iglesia = TenantIglesia::currentId();
        }

        $this->validate([
            'persona_id'    => [
                'required',
                'integer',
                'exists:personas,id',
                Rule::unique('feligres', 'id_persona')
                    ->ignore($this->feligres->id)
                    ->whereNull('deleted_at'),
            ],
            'dni' => [
                'required',
                'string',
                'max:20',
                Rule::unique('personas', 'dni')->ignore($this->persona_id)->whereNull('deleted_at'),
            ],
            'primer_nombre'    => ['required', 'string', 'max:150'],
            'segundo_nombre'   => ['nullable', 'string', 'max:150'],
            'primer_apellido'  => ['required', 'string', 'max:100'],
            'segundo_apellido' => ['nullable', 'string', 'max:100'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'sexo' => ['required', 'in:M,F'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('personas', 'email')->ignore($this->persona_id)->whereNull('deleted_at'),
            ],
            'id_iglesia'    => ['required', 'integer', 'exists:iglesias,id'],
            'fecha_ingreso' => ['nullable', 'date', 'before_or_equal:today'],
            'estado'        => ['required', 'in:Activo,Inactivo'],
        ], [
            'persona_id.required' => 'Debes seleccionar una persona.',
            'persona_id.exists'   => 'La persona seleccionada no existe.',
            'persona_id.unique'   => 'Esta persona ya está registrada como feligrés en otra iglesia.',
            'dni.required'        => 'El DNI es obligatorio.',
            'dni.unique'          => 'El DNI ya está registrado en otra persona.',
            'primer_nombre.required'   => 'El primer nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before'  => 'La fecha de nacimiento debe ser anterior a hoy.',
            'sexo.required'       => 'El sexo es obligatorio.',
            'sexo.in'             => 'El sexo debe ser Masculino o Femenino.',
            'email.email'         => 'El correo electrónico no es válido.',
            'email.unique'        => 'El correo electrónico ya está registrado en otra persona.',
            'id_iglesia.required' => 'Debes seleccionar una iglesia.',
            'id_iglesia.exists'   => 'La iglesia seleccionada no existe.',
            'fecha_ingreso.date'  => 'La fecha de ingreso no es válida.',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser futura.',
            'estado.required'     => 'El estado es obligatorio.',
            'estado.in'           => 'El estado debe ser Activo o Inactivo.',
        ]);

        // Verificar duplicado persona+iglesia excluyendo el registro actual
        $existe = Feligres::where('id_persona', $this->persona_id)
            ->where('id_iglesia', $this->id_iglesia)
            ->where('id', '!=', $this->feligres->id)
            ->whereNull('deleted_at')
            ->exists();

        if ($existe) {
            $this->addError('id_iglesia', 'Esta persona ya está registrada como feligrés en esa iglesia.');
            return;
        }

        DB::transaction(function (): void {
            $persona = Persona::findOrFail($this->persona_id);

            $persona->update([
                'dni'              => $this->dni,
                'primer_nombre'    => $this->primer_nombre,
                'segundo_nombre'   => $this->segundo_nombre ?: null,
                'primer_apellido'  => $this->primer_apellido,
                'segundo_apellido' => $this->segundo_apellido ?: null,
                'fecha_nacimiento' => $this->fecha_nacimiento,
                'sexo'             => $this->sexo,
                'telefono'         => $this->telefono ?: null,
                'email'            => $this->email ?: null,
            ]);

            $this->feligres->update([
                'id_persona'    => $this->persona_id,
                'id_iglesia'    => $this->id_iglesia,
                'fecha_ingreso' => $this->fecha_ingreso ?: null,
                'estado'        => $this->estado,
            ]);
        });

        $personaActualizada = Persona::find($this->persona_id);
        if ($personaActualizada) {
            $this->personaSeleccionada = [
                'id'              => $personaActualizada->id,
                'dni'             => $personaActualizada->dni,
                'nombre_completo' => $personaActualizada->nombre_completo,
                'telefono'        => $personaActualizada->telefono,
                'email'           => $personaActualizada->email,
            ];
        }

        session()->flash('success', 'Feligrés y persona actualizados correctamente.');
        $this->redirect(route('feligres.index'), navigate: false);
    }

    public function render()
    {
        if (session('tenant')) {
            $iglesias = collect([TenantIglesia::current()])->filter();
        } else {
            $iglesias = Iglesias::where('estado', 'Activo')->orderBy('nombre')->get();
        }

        return view('livewire.feligres.feligres-edit', [
            'iglesias' => $iglesias,
        ]);
    }
}
