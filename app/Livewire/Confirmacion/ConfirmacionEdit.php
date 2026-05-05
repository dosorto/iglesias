<?php

namespace App\Livewire\Confirmacion;

use App\Models\Confirmacion;
use App\Models\Iglesias;
use App\Models\Feligres;
use App\Models\Persona;
use App\Models\TenantIglesia;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConfirmacionEdit extends Component
{
    private const LUGAR_CONFIRMACION_FIJO = 'Monjaras, Marcovia, Choluteca, Honduras, C.A.';

    public Confirmacion $confirmacion;

    // Campos principales
    public ?int   $iglesia_id         = null;
    public string $fecha_confirmacion = '';
    public string $lugar_confirmacion = '';
    public string $libro_confirmacion = '';
    public string $folio              = '';
    public string $partida_numero     = '';
    public string $observaciones      = '';
    public string $nota_marginal      = '';
    public string $lugar_expedicion   = '';
    public string $exp_dia            = '';
    public string $exp_mes            = '';
    public string $exp_ano            = '';

    // Ministro — igual que padre/madre
    public string $ministro_dni         = '';
    public ?array $ministro_persona     = null;
    public ?int   $ministro_feligres_id = null;
    public string $ministro_estado      = 'idle';

    // Padre
    public string $padre_dni         = '';
    public ?array $padre_persona     = null;
    public ?int   $padre_feligres_id = null;
    public string $padre_estado      = 'idle';

    // Madre
    public string $madre_dni         = '';
    public ?array $madre_persona     = null;
    public ?int   $madre_feligres_id = null;
    public string $madre_estado      = 'idle';

    // Padrino
    public string $padrino_dni         = '';
    public ?array $padrino_persona     = null;
    public ?int   $padrino_feligres_id = null;
    public string $padrino_estado      = 'idle';

    // Madrina
    public string $madrina_dni         = '';
    public ?array $madrina_persona     = null;
    public ?int   $madrina_feligres_id = null;
    public string $madrina_estado      = 'idle';

    // Búsqueda múltiple compartida
    public array   $busqueda_resultados = [];
    public ?string $busqueda_rol        = null;

    // Mini-form
    public ?string $mini_rol               = null;
    public ?string $mini_tipo              = null;
    public string  $mini_p_dni             = '';
    public string  $mini_p_primer_nombre   = '';
    public string  $mini_p_segundo_nombre  = '';
    public string  $mini_p_primer_apellido  = '';
    public string  $mini_p_segundo_apellido = '';
    public string  $mini_p_fecha_nacimiento = '';
    public string  $mini_p_sexo             = '';
    public string  $mini_p_telefono         = '';
    public string  $mini_p_email            = '';
    public string  $mini_f_fecha_ingreso    = '';
    public string  $mini_f_estado           = 'Activo';

    public function mount(Confirmacion $confirmacion): void
    {
        $this->confirmacion       = $confirmacion;
        $this->iglesia_id         = session('tenant')
            ? TenantIglesia::currentId()
            : $confirmacion->iglesia_id;
        $this->fecha_confirmacion = $confirmacion->fecha_confirmacion?->format('Y-m-d') ?? '';
        $this->lugar_confirmacion = self::LUGAR_CONFIRMACION_FIJO;
        $this->libro_confirmacion = $confirmacion->libro_confirmacion ?? '';
        $this->folio              = $confirmacion->folio ?? '';
        $this->partida_numero     = $confirmacion->partida_numero ?? '';
        $this->observaciones      = $confirmacion->observaciones ?? '';
        $this->nota_marginal      = $confirmacion->nota_marginal  ?? '';
        $this->lugar_expedicion   = $confirmacion->lugar_expedicion ?? '';
        $this->aplicarLugarExpedicionPorDefecto();

        $fe = $confirmacion->fecha_expedicion;
        $this->exp_dia = $fe ? (string) $fe->day   : '';
        $this->exp_mes = $fe ? (string) $fe->month : '';
        $this->exp_ano = $fe ? (string) ($fe->year - 2000) : '';

        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');

        // Cargar todos los roles existentes incluyendo ministro
        $this->cargarRolExistente('ministro', $confirmacion->ministro_id);
        $this->cargarRolExistente('padre',    $confirmacion->padre_id);
        $this->cargarRolExistente('madre',    $confirmacion->madre_id);
        $this->cargarRolExistente('padrino',  $confirmacion->padrino_id);
        $this->cargarRolExistente('madrina',  $confirmacion->madrina_id);
    }

    private function aplicarLugarExpedicionPorDefecto(): void
    {
        if (trim($this->lugar_expedicion) !== '') {
            return;
        }

        $direccion = trim((string) ($this->confirmacion->iglesia?->direccion ?? ''));
        if ($direccion === '' && session('tenant')) {
            $direccion = trim((string) (TenantIglesia::current()?->direccion ?? ''));
        }

        if ($direccion !== '') {
            $this->lugar_expedicion = $direccion;
        }
    }

    private function cargarRolExistente(string $rol, ?int $feligresId): void
    {
        if (! $feligresId) return;

        $feligres = Feligres::with('persona')->find($feligresId);
        if (! $feligres || ! $feligres->persona) return;

        $persona = $feligres->persona;
        $this->{"{$rol}_persona"} = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono ?? null,
            'email'           => $persona->email    ?? null,
        ];
        $this->{"{$rol}_feligres_id"} = $feligresId;
        $this->{"{$rol}_dni"}         = $persona->dni;
        $this->{"{$rol}_estado"}      = 'found';
    }

    // ── Búsqueda ──────────────────────────────────────────────────────────

    public function buscarPersona(string $rol): void
    {
        $input = trim($this->{"{$rol}_dni"});

        if (empty($input)) {
            $this->addError("{$rol}_dni", 'Ingresa un DNI o nombre para buscar.');
            return;
        }

        if (ctype_digit($input)) {
            $personas = Persona::where('dni', $input)->get();
        } else {
            if (mb_strlen($input) < 3) {
                $this->addError("{$rol}_dni", 'Ingresa al menos 3 caracteres para buscar por nombre.');
                return;
            }
            $term     = '%' . $input . '%';
            $personas = Persona::where(function ($q) use ($term) {
                $q->where('primer_nombre',    'like', $term)
                  ->orWhere('segundo_nombre',   'like', $term)
                  ->orWhere('primer_apellido',  'like', $term)
                  ->orWhere('segundo_apellido', 'like', $term);
            })->orderBy('primer_apellido')->orderBy('primer_nombre')->limit(15)->get();
        }

        if ($personas->isEmpty()) {
            $this->{"{$rol}_persona"}     = null;
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_persona';
            $this->busqueda_resultados    = [];
            $this->busqueda_rol           = null;
            return;
        }

        if ($personas->count() === 1) {
            $this->asignarPersonaARol($rol, $personas->first());
            return;
        }

        $this->busqueda_resultados = $personas->map(fn ($p) => [
            'id'              => $p->id,
            'dni'             => $p->dni,
            'nombre_completo' => $p->nombre_completo,
            'telefono'        => $p->telefono ?? null,
        ])->toArray();

        $this->busqueda_rol      = $rol;
        $this->{"{$rol}_estado"} = 'multiples';
    }

    public function seleccionarResultado(int $personaId): void
    {
        $rol = $this->busqueda_rol;
        if (! $rol) return;

        $persona = Persona::findOrFail($personaId);
        $this->asignarPersonaARol($rol, $persona);
    }

    private function asignarPersonaARol(string $rol, Persona $persona): void
    {
        $roles  = ['ministro', 'padre', 'madre', 'padrino', 'madrina'];
        $labels = [
            'ministro' => 'Ministro',
            'padre'    => 'Padre',
            'madre'    => 'Madre',
            'padrino'  => 'Padrino',
            'madrina'  => 'Madrina',
        ];

        foreach ($roles as $r) {
            if ($r === $rol) continue;
            $existente = $this->{"{$r}_persona"};
            if ($existente && $existente['id'] === $persona->id) {
                $this->addError("{$rol}_dni", "Esta persona ya está asignada como {$labels[$r]}.");
                return;
            }
        }

        $feligres = Feligres::where('id_persona', $persona->id)->first();

        $this->{"{$rol}_persona"} = [
            'id'              => $persona->id,
            'dni'             => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono'        => $persona->telefono ?? null,
            'email'           => $persona->email    ?? null,
        ];
        $this->{"{$rol}_dni"} = $persona->dni;

        if ($feligres) {
            $this->{"{$rol}_feligres_id"} = $feligres->id;
            $this->{"{$rol}_estado"}      = 'found';
        } else {
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_feligres';
        }

        $this->busqueda_resultados = [];
        $this->busqueda_rol        = null;

        if ($this->mini_rol === $rol) {
            $this->cancelarMini();
        }
    }

    public function limpiarRol(string $rol): void
    {
        $this->{"{$rol}_dni"}         = '';
        $this->{"{$rol}_persona"}     = null;
        $this->{"{$rol}_feligres_id"} = null;
        $this->{"{$rol}_estado"}      = 'idle';

        if ($this->busqueda_rol === $rol) {
            $this->busqueda_resultados = [];
            $this->busqueda_rol        = null;
        }

        if ($this->mini_rol === $rol) {
            $this->cancelarMini();
        }
    }

    // ── Mini-form ─────────────────────────────────────────────────────────

    public function abrirCrearPersona(string $rol): void
    {
        $dni = trim($this->{"{$rol}_dni"});
        $this->mini_rol   = $rol;
        $this->mini_tipo  = 'persona';
        $this->mini_p_dni = ctype_digit($dni) ? $dni : '';
        $this->reset([
            'mini_p_primer_nombre', 'mini_p_segundo_nombre',
            'mini_p_primer_apellido', 'mini_p_segundo_apellido',
            'mini_p_telefono', 'mini_p_email',
        ]);
        $this->resetErrorBag();
    }

    public function abrirRegistrarFeligres(string $rol): void
    {
        $this->mini_rol             = $rol;
        $this->mini_tipo            = 'feligres';
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->mini_f_estado        = 'Activo';
        $this->resetErrorBag();
    }

    public function cancelarMini(): void
    {
        $this->mini_rol  = null;
        $this->mini_tipo = null;
        $this->reset([
            'mini_p_dni', 'mini_p_primer_nombre', 'mini_p_segundo_nombre',
            'mini_p_primer_apellido', 'mini_p_segundo_apellido',
            'mini_p_fecha_nacimiento', 'mini_p_sexo',
            'mini_p_telefono', 'mini_p_email',
        ]);
        $this->mini_f_estado        = 'Activo';
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->resetErrorBag();
    }

    public function guardarMiniPersona(): void
    {
        $this->validate([
            'mini_p_dni'              => ['nullable', 'string', 'min:8', 'max:20', Rule::unique('personas', 'dni')],
            'mini_p_primer_nombre'    => ['required', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_primer_apellido'  => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_nombre'   => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_apellido' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_fecha_nacimiento' => ['required', 'date', 'before:today'],
            'mini_p_sexo'             => ['required', 'in:M,F'],
            'mini_p_telefono'         => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-]+$/'],
            'mini_p_email'            => ['nullable', 'email', 'max:255'],
            'mini_f_fecha_ingreso'    => ['nullable', 'date'],
            'mini_f_estado'           => ['required', 'in:Activo,Inactivo'],
        ]);

        $rol = $this->mini_rol;

        DB::transaction(function () use ($rol) {
            $iglesiaId = session('tenant')
                ? TenantIglesia::currentId()
                : $this->iglesia_id;

            $persona = Persona::create([
                'dni'              => $this->mini_p_dni ?: null,
                'primer_nombre'    => $this->mini_p_primer_nombre,
                'segundo_nombre'   => $this->mini_p_segundo_nombre  ?: null,
                'primer_apellido'  => $this->mini_p_primer_apellido,
                'segundo_apellido' => $this->mini_p_segundo_apellido ?: null,
                'fecha_nacimiento' => $this->mini_p_fecha_nacimiento ?: null,
                'sexo'             => $this->mini_p_sexo,
                'telefono'         => $this->mini_p_telefono ?: null,
                'email'            => $this->mini_p_email    ?: null,
            ]);

            $feligres = Feligres::create([
                'id_persona'    => $persona->id,
                'id_iglesia'    => $iglesiaId,
                'fecha_ingreso' => $this->mini_f_fecha_ingreso ?: now()->format('Y-m-d'),
                'estado'        => $this->mini_f_estado,
            ]);

            $this->{"{$rol}_persona"} = [
                'id'              => $persona->id,
                'dni'             => $persona->dni,
                'nombre_completo' => $persona->nombre_completo,
                'telefono'        => $persona->telefono ?? null,
                'email'           => $persona->email    ?? null,
            ];
            $this->{"{$rol}_feligres_id"} = $feligres->id;
            $this->{"{$rol}_estado"}      = 'found';
            $this->{"{$rol}_dni"}         = $persona->dni;
        });

        $this->cancelarMini();
    }

    public function guardarMiniFeligres(): void
    {
        $this->validate([
            'mini_f_fecha_ingreso' => ['nullable', 'date'],
            'mini_f_estado'        => ['required', 'in:Activo,Inactivo'],
        ]);

        $iglesiaId = session('tenant')
            ? TenantIglesia::currentId()
            : $this->iglesia_id;

        $rol     = $this->mini_rol;
        $persona = $this->{"{$rol}_persona"};

        $feligres = Feligres::create([
            'id_persona'    => $persona['id'],
            'id_iglesia'    => $iglesiaId,
            'fecha_ingreso' => $this->mini_f_fecha_ingreso ?: null,
            'estado'        => $this->mini_f_estado,
        ]);

        $this->{"{$rol}_feligres_id"} = $feligres->id;
        $this->{"{$rol}_estado"}      = 'found';
        $this->cancelarMini();
    }

    // ── Validación y guardado ─────────────────────────────────────────────

    protected function rules(): array
    {
        return [
            'iglesia_id'          => ['required', 'integer', 'exists:iglesias,id'],
            'fecha_confirmacion'  => ['required', 'date', 'before_or_equal:today'],
            'lugar_confirmacion'  => ['nullable', 'string', 'max:200'],
            'libro_confirmacion'  => ['nullable', 'string', 'max:50'],
            'folio'               => ['nullable', 'string', 'max:50'],
            'partida_numero'      => ['nullable', 'string', 'max:50'],
            'observaciones'       => ['nullable', 'string', 'max:500'],
            'nota_marginal'       => ['nullable', 'string', 'max:500'],
            'lugar_expedicion'    => ['nullable', 'string', 'max:150'],
            'exp_dia'             => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'             => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'             => ['nullable', 'integer', 'min:0', 'max:99'],
        ];
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function guardar(): void
    {
        $this->iglesia_id = session('tenant')
            ? TenantIglesia::currentId()
            : $this->confirmacion->iglesia_id;

        $this->lugar_confirmacion = self::LUGAR_CONFIRMACION_FIJO;

        $this->validate();

        $fechaExp = null;
        if ($this->exp_dia && $this->exp_mes && $this->exp_ano !== '') {
            try {
                $fechaExp = \Carbon\Carbon::createFromDate(
                    2000 + (int) $this->exp_ano,
                    (int) $this->exp_mes,
                    (int) $this->exp_dia
                )->format('Y-m-d');
            } catch (\Exception) {
                $fechaExp = null;
            }
        }

        $this->confirmacion->update([
            'iglesia_id'          => $this->confirmacion->iglesia_id,
            'ministro_id'         => $this->ministro_feligres_id,
            'fecha_confirmacion'  => $this->fecha_confirmacion,
            'lugar_confirmacion'  => $this->lugar_confirmacion ?: null,
            'padre_id'            => $this->padre_feligres_id,
            'madre_id'            => $this->madre_feligres_id,
            'padrino_id'          => $this->padrino_feligres_id,
            'madrina_id'          => $this->madrina_feligres_id,
            'libro_confirmacion'  => $this->libro_confirmacion ?: null,
            'folio'               => $this->folio ?: null,
            'partida_numero'      => $this->partida_numero ?: null,
            'observaciones'       => $this->observaciones ?: null,
            'nota_marginal'       => $this->nota_marginal  ?: null,
            'lugar_expedicion'    => $this->lugar_expedicion ?: null,
            'fecha_expedicion'    => $fechaExp,
        ]);

        session()->flash('success', 'Confirmación actualizada correctamente.');
        $this->redirect(route('confirmacion.index'), navigate: false);
    }

    public function render()
    {
        $iglesiaActual = session('tenant')
            ? TenantIglesia::current()
            : Iglesias::find($this->confirmacion->iglesia_id);

        return view('livewire.confirmacion.confirmacion-edit', compact('iglesiaActual'));
    }
}