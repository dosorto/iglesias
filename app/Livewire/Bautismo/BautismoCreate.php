<?php

namespace App\Livewire\Bautismo;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\Encargado;
use App\Models\Bautismo;
use Illuminate\Validation\Rule;

class BautismoCreate extends Component
{
    // ── Wizard ──────────────────────────────────────────────────────
    public int $paso = 1;

    // ── Paso 1 ──────────────────────────────────────────────────────
    public ?int   $iglesia_id     = null;
    public string $fecha_bautismo = '';
    public ?int   $encargado_id   = null;

    // ── Paso 2 — estado por rol ─────────────────────────────────────
    // Cada rol tiene: _dni  |  _persona (array|null)  |  _feligres_id (int|null)  |  _estado (string)
    // _estado valores: idle | found | sin_persona | sin_feligres

    public string $bautizado_dni         = '';
    public ?array $bautizado_persona     = null;
    public ?int   $bautizado_feligres_id = null;
    public string $bautizado_estado      = 'idle';

    public string $padre_dni         = '';
    public ?array $padre_persona     = null;
    public ?int   $padre_feligres_id = null;
    public string $padre_estado      = 'idle';

    public string $madre_dni         = '';
    public ?array $madre_persona     = null;
    public ?int   $madre_feligres_id = null;
    public string $madre_estado      = 'idle';

    public string $padrino_dni         = '';
    public ?array $padrino_persona     = null;
    public ?int   $padrino_feligres_id = null;
    public string $padrino_estado      = 'idle';

    public string $madrina_dni         = '';
    public ?array $madrina_persona     = null;
    public ?int   $madrina_feligres_id = null;
    public string $madrina_estado      = 'idle';

    // ── Mini-form compartido (Crear Persona / Registrar Feligrés) ───
    public ?string $mini_rol  = null;   // rol activo: bautizado | padre | ...
    public ?string $mini_tipo = null;   // 'persona' | 'feligres'

    // Crear persona
    public string $mini_p_dni             = '';
    public string $mini_p_primer_nombre   = '';
    public string $mini_p_segundo_nombre  = '';
    public string $mini_p_primer_apellido  = '';
    public string $mini_p_segundo_apellido = '';
    public string $mini_p_telefono        = '';
    public string $mini_p_email           = '';

    // Registrar feligrés
    public string $mini_f_fecha_ingreso = '';
    public string $mini_f_estado        = 'Activo';

    // ── Paso 3 ──────────────────────────────────────────────────────
    public string $libro_bautismo = '';
    public string $folio          = '';
    public string $partida_numero = '';
    public string $observaciones  = '';

    // ────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->fecha_bautismo       = now()->format('Y-m-d');
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
    }

    // ── Navegación ──────────────────────────────────────────────────

    public function siguientePaso(): void
    {
        if ($this->paso === 1) {
            $this->validate([
                'iglesia_id'     => ['required', 'integer', 'exists:iglesias,id'],
                'fecha_bautismo' => ['required', 'date'],
                'encargado_id'   => ['nullable', 'integer', 'exists:encargado,id'],
            ], [
                'iglesia_id.required'     => 'Selecciona la iglesia.',
                'fecha_bautismo.required' => 'La fecha de bautismo es obligatoria.',
            ]);
        }

        if ($this->paso === 2) {
            if (! $this->bautizado_feligres_id) {
                $this->addError('bautizado_dni', 'El bautizado es obligatorio y debe estar registrado como feligrés.');
                return;
            }
        }

        $this->paso++;
        $this->resetErrorBag();
    }

    public function anteriorPaso(): void
    {
        if ($this->paso > 1) {
            $this->paso--;
            $this->resetErrorBag();
        }
    }

    // ── Buscar persona por DNI ───────────────────────────────────────

    public function buscarPersona(string $rol): void
    {
        $dni = trim($this->{"{$rol}_dni"});

        if (empty($dni)) {
            $this->addError("{$rol}_dni", 'Ingresa un DNI para buscar.');
            return;
        }

        $persona = Persona::where('dni', $dni)->first();

        if (! $persona) {
            $this->{"{$rol}_persona"}     = null;
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_persona';
            return;
        }

        $feligres = Feligres::where('id_persona', $persona->id)->first();

        $this->{"{$rol}_persona"} = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono ?? null,
            'email'           => $persona->email    ?? null,
        ];

        if ($feligres) {
            $this->{"{$rol}_feligres_id"} = $feligres->id;
            $this->{"{$rol}_estado"}      = 'found';
        } else {
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_feligres';
        }

        // Cerrar mini-form si había uno abierto para este rol
        if ($this->mini_rol === $rol) {
            $this->cancelarMini();
        }
    }

    // ── Limpiar un rol ───────────────────────────────────────────────

    public function limpiarRol(string $rol): void
    {
        $this->{"{$rol}_dni"}         = '';
        $this->{"{$rol}_persona"}     = null;
        $this->{"{$rol}_feligres_id"} = null;
        $this->{"{$rol}_estado"}      = 'idle';

        if ($this->mini_rol === $rol) {
            $this->cancelarMini();
        }
    }

    // ── Mini-form: abrir Crear Persona ───────────────────────────────

    public function abrirCrearPersona(string $rol): void
    {
        $dni = trim($this->{"{$rol}_dni"});

        $this->mini_rol              = $rol;
        $this->mini_tipo             = 'persona';
        $this->mini_p_dni            = ctype_digit($dni) ? $dni : '';

        $this->reset([
            'mini_p_primer_nombre', 'mini_p_segundo_nombre',
            'mini_p_primer_apellido', 'mini_p_segundo_apellido',
            'mini_p_telefono', 'mini_p_email',
        ]);

        $this->resetErrorBag();
    }

    // ── Mini-form: abrir Registrar como Feligrés ────────────────────

    public function abrirRegistrarFeligres(string $rol): void
    {
        $this->mini_rol             = $rol;
        $this->mini_tipo            = 'feligres';
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->mini_f_estado        = 'Activo';

        $this->resetErrorBag();
    }

    // ── Mini-form: cancelar ──────────────────────────────────────────

    public function cancelarMini(): void
    {
        $this->mini_rol  = null;
        $this->mini_tipo = null;

        $this->reset([
            'mini_p_dni', 'mini_p_primer_nombre', 'mini_p_segundo_nombre',
            'mini_p_primer_apellido', 'mini_p_segundo_apellido',
            'mini_p_telefono', 'mini_p_email',
        ]);

        $this->mini_f_estado        = 'Activo';
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->resetErrorBag();
    }

    // ── Mini-form: guardar nueva persona ────────────────────────────

    public function guardarMiniPersona(): void
    {
        $this->validate([
            'mini_p_dni'              => ['required', 'string', 'min:8', 'max:20', Rule::unique('personas', 'dni')],
            'mini_p_primer_nombre'    => ['required', 'string', 'max:150'],
            'mini_p_primer_apellido'  => ['required', 'string', 'max:100'],
            'mini_p_segundo_nombre'   => ['nullable', 'string', 'max:150'],
            'mini_p_segundo_apellido' => ['nullable', 'string', 'max:100'],
            'mini_p_telefono'         => ['nullable', 'string', 'max:20'],
            'mini_p_email'            => ['nullable', 'email', 'max:255'],
        ], [
            'mini_p_dni.required'            => 'El número de identidad es obligatorio.',
            'mini_p_dni.min'                 => 'El DNI debe tener al menos 8 caracteres.',
            'mini_p_dni.unique'              => 'Ya existe una persona con ese DNI.',
            'mini_p_primer_nombre.required'  => 'El primer nombre es obligatorio.',
            'mini_p_primer_apellido.required'=> 'El primer apellido es obligatorio.',
        ]);

        $persona = Persona::create([
            'dni'              => $this->mini_p_dni,
            'primer_nombre'    => $this->mini_p_primer_nombre,
            'segundo_nombre'   => $this->mini_p_segundo_nombre  ?: null,
            'primer_apellido'  => $this->mini_p_primer_apellido,
            'segundo_apellido' => $this->mini_p_segundo_apellido ?: null,
            'telefono'         => $this->mini_p_telefono ?: null,
            'email'            => $this->mini_p_email    ?: null,
        ]);

        $rol = $this->mini_rol;
        $this->{"{$rol}_dni"} = $persona->dni;

        $this->cancelarMini();

        // Auto-buscar → queda en estado "sin_feligres" listo para registrar
        $this->buscarPersona($rol);
    }

    // ── Mini-form: registrar como feligrés ──────────────────────────

    public function guardarMiniFeligres(): void
    {
        $this->validate([
            'iglesia_id'           => ['required', 'integer', 'exists:iglesias,id'],
            'mini_f_fecha_ingreso' => ['nullable', 'date'],
            'mini_f_estado'        => ['required', 'in:Activo,Inactivo'],
        ], [
            'iglesia_id.required' => 'La iglesia (Paso 1) debe estar seleccionada para registrar un feligrés.',
        ]);

        $rol     = $this->mini_rol;
        $persona = $this->{"{$rol}_persona"};

        $feligres = Feligres::create([
            'id_persona'    => $persona['id'],
            'id_iglesia'    => $this->iglesia_id,
            'fecha_ingreso' => $this->mini_f_fecha_ingreso ?: null,
            'estado'        => $this->mini_f_estado,
        ]);

        $this->{"{$rol}_feligres_id"} = $feligres->id;
        $this->{"{$rol}_estado"}      = 'found';

        $this->cancelarMini();
    }

    // ── Guardar bautismo ─────────────────────────────────────────────

    public function guardar(): void
    {
        $this->validate([
            'iglesia_id'           => ['required', 'integer', 'exists:iglesias,id'],
            'fecha_bautismo'       => ['required', 'date'],
            'encargado_id'         => ['nullable', 'integer', 'exists:encargado,id'],
            'bautizado_feligres_id'=> ['required', 'integer', 'exists:feligres,id'],
            'padre_feligres_id'    => ['nullable', 'integer', 'exists:feligres,id'],
            'madre_feligres_id'    => ['nullable', 'integer', 'exists:feligres,id'],
            'padrino_feligres_id'  => ['nullable', 'integer', 'exists:feligres,id'],
            'madrina_feligres_id'  => ['nullable', 'integer', 'exists:feligres,id'],
            'libro_bautismo'       => ['nullable', 'string', 'max:50'],
            'folio'                => ['nullable', 'string', 'max:50'],
            'partida_numero'       => ['nullable', 'string', 'max:50'],
            'observaciones'        => ['nullable', 'string'],
        ], [
            'bautizado_feligres_id.required' => 'El bautizado es obligatorio.',
        ]);

        Bautismo::create([
            'iglesia_id'     => $this->iglesia_id,
            'fecha_bautismo' => $this->fecha_bautismo,
            'encargado_id'   => $this->encargado_id,
            'bautizado_id'   => $this->bautizado_feligres_id,
            'padre_id'       => $this->padre_feligres_id,
            'madre_id'       => $this->madre_feligres_id,
            'padrino_id'     => $this->padrino_feligres_id,
            'madrina_id'     => $this->madrina_feligres_id,
            'libro_bautismo' => $this->libro_bautismo ?: null,
            'folio'          => $this->folio          ?: null,
            'partida_numero' => $this->partida_numero ?: null,
            'observaciones'  => $this->observaciones  ?: null,
        ]);

        session()->flash('success', 'Bautismo registrado correctamente.');
        $this->redirect(route('bautismo.index'), navigate: false);
    }

    // ────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.bautismo.bautismo-create', [
            'iglesias'   => Iglesias::where('estado', 'Activo')->orderBy('nombre')->get(),
            'encargados' => Encargado::with('feligres.persona')->get(),
        ]);
    }
}
