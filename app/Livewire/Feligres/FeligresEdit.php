<?php

namespace App\Livewire\Feligres;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Feligres;
use App\Models\Persona;
use App\Models\Iglesias;
use Illuminate\Validation\Rule;

class FeligresEdit extends Component
{
    public Feligres $feligres;

    // ── Búsqueda live ──────────────────────────────────────────────
    public string $search = '';
    public ?int   $persona_id = null;
    public ?array $personaSeleccionada = null;

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

        $this->search = '';
        unset($this->resultados);
    }

    // ── Deseleccionar persona ───────────────────────────────────────
    public function limpiarPersona(): void
    {
        $this->persona_id          = null;
        $this->personaSeleccionada = null;
        $this->search              = '';
        unset($this->resultados);
    }

    // ── Guardar cambios ──────────────────────────────────────────────
    public function guardar(): void
    {
        $this->validate([
            'persona_id'    => [
                'required',
                'integer',
                'exists:personas,id',
                Rule::unique('feligres', 'id_persona')
                    ->ignore($this->feligres->id)
                    ->whereNull('deleted_at'),
            ],
            'id_iglesia'    => ['required', 'integer', 'exists:iglesias,id'],
            'fecha_ingreso' => ['nullable', 'date', 'before_or_equal:today'],
            'estado'        => ['required', 'in:Activo,Inactivo'],
        ], [
            'persona_id.required' => 'Debes seleccionar una persona.',
            'persona_id.exists'   => 'La persona seleccionada no existe.',
            'persona_id.unique'   => 'Esta persona ya está registrada como feligrés en otra iglesia.',
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

        $this->feligres->update([
            'id_persona'    => $this->persona_id,
            'id_iglesia'    => $this->id_iglesia,
            'fecha_ingreso' => $this->fecha_ingreso ?: null,
            'estado'        => $this->estado,
        ]);

        session()->flash('success', 'Feligrés actualizado correctamente.');
        $this->redirect(route('feligres.index'), navigate: false);
    }

    public function render()
    {
        return view('livewire.feligres.feligres-edit', [
            'iglesias' => Iglesias::where('estado', 'Activo')->orderBy('nombre')->get(),
        ]);
    }
}
