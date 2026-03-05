<?php

namespace App\Livewire\Feligres;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Iglesias;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class FeligresCreate extends Component
{
    // ── Búsqueda live ──────────────────────────────────────────────
    public string $search = '';
    public ?int   $persona_id = null;
    public ?array $personaSeleccionada = null;

    // ── Crear persona inline ────────────────────────────────────────
    public bool   $showCrearPersona  = false;
    public string $p_dni             = '';
    public string $p_primer_nombre   = '';
    public string $p_segundo_nombre  = '';
    public string $p_primer_apellido  = '';
    public string $p_segundo_apellido = '';
    public string $p_telefono = '';
    public string $p_email    = '';

    // ── Datos feligrés ──────────────────────────────────────────────
    public ?int   $id_iglesia    = null;
    public string $fecha_ingreso = '';
    public string $estado        = 'Activo';

    public function mount(): void
{
    $this->fecha_ingreso = now()->format('Y-m-d');

    // En tenant, preseleccionar la iglesia local automáticamente
    if (session('tenant')) {
        $iglesiaLocal   = DB::table('iglesias')->first();
        $this->id_iglesia = $iglesiaLocal?->id;
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

    // Auto-abrir form crear cuando la búsqueda no encuentra resultados
    public function updatedSearch(): void
    {
        unset($this->resultados);

        $q = trim($this->search);

        if (strlen($q) < 2) {
            $this->showCrearPersona = false;
            return;
        }

        // Si no hay resultados, mostrar el form automáticamente y pre-llenar DNI
        if ($this->resultados->isEmpty()) {
            if (! $this->showCrearPersona) {
                $this->showCrearPersona = true;
                $this->p_dni = ctype_digit($q) ? $q : '';
                $this->reset(['p_primer_nombre', 'p_segundo_nombre', 'p_primer_apellido',
                              'p_segundo_apellido', 'p_telefono', 'p_email']);
            }
        } else {
            // Hay resultados: ocultar el form crear
            $this->showCrearPersona = false;
        }
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

        $this->search           = '';
        $this->showCrearPersona = false;

        unset($this->resultados);
    }

    // ── Deseleccionar persona ───────────────────────────────────────
    public function limpiarPersona(): void
    {
        $this->persona_id          = null;
        $this->personaSeleccionada = null;
        $this->search              = '';
        $this->showCrearPersona    = false;
        unset($this->resultados);
    }

    // ── Mostrar / ocultar form crear persona ────────────────────────
    public function toggleCrearPersona(): void
    {
        if ($this->showCrearPersona) {
            // Cerrar: limpiar todo para volver al buscador vacío
            $this->showCrearPersona = false;
            $this->search = '';
            unset($this->resultados);
        } else {
            // Abrir manualmente (desde el pie de la lista de resultados)
            $q = trim($this->search);
            $this->p_dni = ctype_digit($q) ? $q : '';
            $this->reset(['p_primer_nombre', 'p_segundo_nombre', 'p_primer_apellido',
                          'p_segundo_apellido', 'p_telefono', 'p_email']);
            $this->search = '';
            unset($this->resultados);
            $this->showCrearPersona = true;
        }
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
        ], [
            'p_dni.required'             => 'El número de identidad es obligatorio.',
            'p_dni.min'                  => 'El DNI debe tener al menos 8 caracteres.',
            'p_dni.unique'               => 'Ya existe una persona con ese DNI.',
            'p_primer_nombre.required'   => 'El primer nombre es obligatorio.',
            'p_primer_apellido.required' => 'El primer apellido es obligatorio.',
        ]);

        $persona = Persona::create([
            'dni'              => $this->p_dni,
            'primer_nombre'    => $this->p_primer_nombre,
            'segundo_nombre'   => $this->p_segundo_nombre ?: null,
            'primer_apellido'  => $this->p_primer_apellido,
            'segundo_apellido' => $this->p_segundo_apellido ?: null,
            'telefono'         => $this->p_telefono ?: null,
            'email'            => $this->p_email ?: null,
        ]);

        $this->seleccionarPersona($persona->id);
        session()->flash('persona_nueva', "Persona \"{$persona->nombre_completo}\" creada y seleccionada.");
    }

    // ── Guardar feligrés ─────────────────────────────────────────────
    public function guardar(): void
    {
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
        $iglesias = collect([DB::table('iglesias')->first()])->filter();
    } else {
        $iglesias = Iglesias::where('estado', 'Activo')->orderBy('nombre')->get();
    }

    return view('livewire.feligres.feligres-create', [
        'iglesias' => $iglesias,
    ]);
}
}
