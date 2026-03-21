<?php

namespace App\Livewire\PrimeraComunion;

use App\Models\PrimeraComunion;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use App\Models\Feligres;
use App\Models\Persona;
use App\Models\Encargado;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PrimeraComunionEdit extends Component
{
    use WithFileUploads;

    public PrimeraComunion $primeraComunion;

    public $iglesia_id                    = null;
    public string $fecha_primera_comunion = '';
    public string $libro_comunion         = '';
    public string $folio                  = '';
    public string $partida_numero         = '';
    public string $observaciones          = '';
    public string $nota_marginal          = '';
    public string $lugar_celebracion      = '';
    public string $lugar_expedicion       = '';
    public string $exp_dia                = '';
    public string $exp_mes                = '';
    public string $exp_ano                = '';

    public $firma_nueva = null;

    // Encargado activo (párroco automático)
    public ?array $encargado_info = null;

    // Roles editables: solo catequista y ministro
    public string $catequista_dni         = '';
    public ?array $catequista_persona     = null;
    public ?int   $catequista_feligres_id = null;
    public string $catequista_estado      = 'idle';

    public string $ministro_dni         = '';
    public ?array $ministro_persona     = null;
    public ?int   $ministro_feligres_id = null;
    public string $ministro_estado      = 'idle';

    public array   $busqueda_resultados = [];
    public ?string $busqueda_rol        = null;

    public ?string $mini_rol               = null;
    public ?string $mini_tipo              = null;
    public string  $mini_p_dni             = '';
    public string  $mini_p_primer_nombre   = '';
    public string  $mini_p_segundo_nombre  = '';
    public string  $mini_p_primer_apellido = '';
    public string  $mini_p_segundo_apellido= '';
    public string  $mini_p_fecha_nacimiento= '';
    public string  $mini_p_sexo            = '';
    public string  $mini_p_telefono        = '';
    public string  $mini_p_email           = '';
    public string  $mini_f_fecha_ingreso   = '';
    public string  $mini_f_estado          = 'Activo';

    public function mount(PrimeraComunion $primeraComunion): void
    {
        $this->primeraComunion        = $primeraComunion;
        $this->iglesia_id             = session('tenant') ? TenantIglesia::currentId() : $primeraComunion->id_iglesia;
        $this->fecha_primera_comunion = $primeraComunion->fecha_primera_comunion?->format('Y-m-d') ?? '';
        $this->libro_comunion         = $primeraComunion->libro_comunion   ?? '';
        $this->folio                  = $primeraComunion->folio             ?? '';
        $this->partida_numero         = $primeraComunion->partida_numero    ?? '';
        $this->observaciones          = $primeraComunion->observaciones     ?? '';
        $this->nota_marginal          = $primeraComunion->nota_marginal     ?? '';
        $this->lugar_celebracion      = $primeraComunion->lugar_celebracion ?? '';
        $this->lugar_expedicion       = $primeraComunion->lugar_expedicion  ?? '';

        $fe = $primeraComunion->fecha_expedicion;
        $this->exp_dia = $fe ? (string) $fe->day   : '';
        $this->exp_mes = $fe ? (string) $fe->month : '';
        $this->exp_ano = $fe ? (string) ($fe->year - 2000) : '';

        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');

        // Cargar encargado activo (párroco automático)
        $this->cargarEncargado();

        // Cargar roles editables existentes
        $this->cargarRolExistente('catequista', $primeraComunion->id_catequista ?? null);
        $this->cargarRolExistente('ministro',   $primeraComunion->id_ministro   ?? null);
    }

    private function cargarEncargado(): void
    {
        // Primero intentar el encargado asignado al registro
        $encargado = null;
        if ($this->primeraComunion->encargado_id) {
            $encargado = Encargado::with('feligres.persona')->find($this->primeraComunion->encargado_id);
        }

        // Si no tiene asignado, tomar el activo del sistema
        if (! $encargado) {
            $encargado = Encargado::with('feligres.persona')->where('estado', 'Activo')->first();
        }

        if ($encargado?->feligres?->persona) {
            $persona = $encargado->feligres->persona;
            $this->encargado_info = [
                'encargado_id'    => $encargado->id,
                'feligres_id'     => $encargado->feligres->id,
                'nombre_completo' => $persona->nombre_completo,
                'dni'             => $persona->dni,
            ];
        }
    }

    private function cargarRolExistente(string $rol, ?int $feligresId): void
    {
        if (! $feligresId) return;
        $feligres = Feligres::with('persona')->find($feligresId);
        if (! $feligres?->persona) return;
        $persona = $feligres->persona;
        $this->{"{$rol}_persona"}     = [
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

    public function buscarPersona(string $rol): void
    {
        $input = trim($this->{"{$rol}_dni"});
        if (empty($input)) { $this->addError("{$rol}_dni", 'Ingresa un DNI o nombre.'); return; }

        if (ctype_digit($input)) {
            $personas = Persona::where('dni', $input)->get();
        } else {
            if (mb_strlen($input) < 3) { $this->addError("{$rol}_dni", 'Ingresa al menos 3 caracteres.'); return; }
            $term = '%'.$input.'%';
            $personas = Persona::where(fn($q) =>
                $q->where('primer_nombre',    'like', $term)
                  ->orWhere('segundo_nombre',   'like', $term)
                  ->orWhere('primer_apellido',  'like', $term)
                  ->orWhere('segundo_apellido', 'like', $term)
            )->orderBy('primer_apellido')->limit(15)->get();
        }

        if ($personas->isEmpty()) {
            $this->{"{$rol}_persona"}     = null;
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_persona';
            $this->busqueda_resultados    = [];
            $this->busqueda_rol           = null;
            return;
        }

        if ($personas->count() === 1) { $this->asignarPersonaARol($rol, $personas->first()); return; }

        $this->busqueda_resultados = $personas->map(fn($p) => [
            'id' => $p->id, 'dni' => $p->dni,
            'nombre_completo' => $p->nombre_completo,
            'telefono' => $p->telefono ?? null,
        ])->toArray();
        $this->busqueda_rol      = $rol;
        $this->{"{$rol}_estado"} = 'multiples';
    }

    public function seleccionarResultado(int $personaId): void
    {
        $rol = $this->busqueda_rol;
        if (! $rol) return;
        $this->asignarPersonaARol($rol, Persona::findOrFail($personaId));
    }

    private function asignarPersonaARol(string $rol, Persona $persona): void
    {
        $roles  = ['catequista', 'ministro'];
        $labels = ['catequista' => 'Catequista', 'ministro' => 'Ministro'];

        foreach ($roles as $r) {
            if ($r === $rol) continue;
            $existente = $this->{"{$r}_persona"};
            if ($existente && $existente['id'] === $persona->id) {
                $this->addError("{$rol}_dni", "Ya asignado como {$labels[$r]}.");
                return;
            }
        }

        $feligres = Feligres::where('id_persona', $persona->id)->first();
        $this->{"{$rol}_persona"} = [
            'id' => $persona->id, 'dni' => $persona->dni,
            'nombre_completo' => $persona->nombre_completo,
            'telefono' => $persona->telefono ?? null,
            'email'    => $persona->email    ?? null,
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
        if ($this->mini_rol === $rol) $this->cancelarMini();
    }

    public function limpiarRol(string $rol): void
    {
        $this->{"{$rol}_dni"}         = '';
        $this->{"{$rol}_persona"}     = null;
        $this->{"{$rol}_feligres_id"} = null;
        $this->{"{$rol}_estado"}      = 'idle';
        if ($this->busqueda_rol === $rol) { $this->busqueda_resultados = []; $this->busqueda_rol = null; }
        if ($this->mini_rol === $rol) $this->cancelarMini();
    }

    public function abrirCrearPersona(string $rol): void
    {
        $this->mini_rol   = $rol;
        $this->mini_tipo  = 'persona';
        $this->mini_p_dni = ctype_digit(trim($this->{"{$rol}_dni"})) ? trim($this->{"{$rol}_dni"}) : '';
        $this->reset(['mini_p_primer_nombre','mini_p_segundo_nombre','mini_p_primer_apellido','mini_p_segundo_apellido','mini_p_telefono','mini_p_email']);
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
        $this->mini_rol  = null; $this->mini_tipo = null;
        $this->reset(['mini_p_dni','mini_p_primer_nombre','mini_p_segundo_nombre','mini_p_primer_apellido','mini_p_segundo_apellido','mini_p_fecha_nacimiento','mini_p_sexo','mini_p_telefono','mini_p_email']);
        $this->mini_f_estado = 'Activo'; $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->resetErrorBag();
    }

    public function guardarMiniPersona(): void
    {
        $this->validate([
            'mini_p_dni'              => ['required','string','min:8','max:20', Rule::unique('personas','dni')],
            'mini_p_primer_nombre'    => ['required','string','max:150','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_primer_apellido'  => ['required','string','max:100','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_nombre'   => ['nullable','string','max:150','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_segundo_apellido' => ['nullable','string','max:100','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\']+$/u'],
            'mini_p_fecha_nacimiento' => ['required','date','before:today'],
            'mini_p_sexo'             => ['required','in:M,F'],
            'mini_p_telefono'         => ['required','string','max:20','regex:/^[0-9+\-]+$/'],
            'mini_p_email'            => ['nullable','email','max:255'],
            'mini_f_fecha_ingreso'    => ['nullable','date'],
            'mini_f_estado'           => ['required','in:Activo,Inactivo'],
        ]);
        $rol = $this->mini_rol;
        DB::transaction(function () use ($rol) {
            $iglesiaId = session('tenant') ? TenantIglesia::currentId() : $this->iglesia_id;
            $persona   = Persona::create([
                'dni' => $this->mini_p_dni, 'primer_nombre' => $this->mini_p_primer_nombre,
                'segundo_nombre'   => $this->mini_p_segundo_nombre  ?: null,
                'primer_apellido'  => $this->mini_p_primer_apellido,
                'segundo_apellido' => $this->mini_p_segundo_apellido ?: null,
                'fecha_nacimiento' => $this->mini_p_fecha_nacimiento ?: null,
                'sexo' => $this->mini_p_sexo,
                'telefono' => $this->mini_p_telefono ?: null,
                'email'    => $this->mini_p_email    ?: null,
            ]);
            $feligres = Feligres::create([
                'id_persona'    => $persona->id,
                'id_iglesia'    => $iglesiaId,
                'fecha_ingreso' => $this->mini_f_fecha_ingreso ?: now()->format('Y-m-d'),
                'estado'        => $this->mini_f_estado,
            ]);
            $this->{"{$rol}_persona"}     = ['id' => $persona->id, 'dni' => $persona->dni, 'nombre_completo' => $persona->nombre_completo, 'telefono' => $persona->telefono ?? null, 'email' => $persona->email ?? null];
            $this->{"{$rol}_feligres_id"} = $feligres->id;
            $this->{"{$rol}_estado"}      = 'found';
            $this->{"{$rol}_dni"}         = $persona->dni;
        });
        $this->cancelarMini();
    }

    public function guardarMiniFeligres(): void
    {
        $this->validate(['mini_f_fecha_ingreso' => ['nullable','date'], 'mini_f_estado' => ['required','in:Activo,Inactivo']]);
        $iglesiaId = session('tenant') ? TenantIglesia::currentId() : $this->iglesia_id;
        $rol = $this->mini_rol;
        $persona = $this->{"{$rol}_persona"};
        $feligres = Feligres::create(['id_persona' => $persona['id'], 'id_iglesia' => $iglesiaId, 'fecha_ingreso' => $this->mini_f_fecha_ingreso ?: null, 'estado' => $this->mini_f_estado]);
        $this->{"{$rol}_feligres_id"} = $feligres->id;
        $this->{"{$rol}_estado"}      = 'found';
        $this->cancelarMini();
    }

    protected function rules(): array
    {
        $tenantIglesiaId = session('tenant') ? TenantIglesia::currentId() : null;

        return [
            'iglesia_id'             => array_filter([
                'required',
                'integer',
                'exists:iglesias,id',
                $tenantIglesiaId ? Rule::in([$tenantIglesiaId]) : null,
            ]),
            'fecha_primera_comunion' => ['required','date','before_or_equal:today'],
            'libro_comunion'         => ['nullable','string','max:100'],
            'folio'                  => ['nullable','string','max:50'],
            'partida_numero'         => ['nullable','string','max:50'],
            'observaciones'          => ['nullable','string','max:500'],
            'nota_marginal'          => ['nullable','string','max:500'],
            'lugar_celebracion'      => ['nullable','string','max:200'],
            'lugar_expedicion'       => ['nullable','string','max:150'],
            'exp_dia'                => ['nullable','integer','min:1','max:31'],
            'exp_mes'                => ['nullable','integer','min:1','max:12'],
            'exp_ano'                => ['nullable','integer','min:0','max:99'],
        ];
    }

    public function updated(string $field): void { $this->validateOnly($field); }

    public function save(): void
    {
        $this->iglesia_id = session('tenant')
            ? TenantIglesia::currentId()
            : ($this->iglesia_id ?: $this->primeraComunion->id_iglesia);
        $this->validate();

        $fechaExp = null;
        if ($this->exp_dia && $this->exp_mes && $this->exp_ano !== '') {
            try {
                $fechaExp = \Carbon\Carbon::createFromDate(2000 + (int)$this->exp_ano, (int)$this->exp_mes, (int)$this->exp_dia)->format('Y-m-d');
            } catch (\Exception) {}
        }

        // Resolver encargado activo para actualizar párroco
        $encargado = null;
        if ($this->primeraComunion->encargado_id) {
            $encargado = Encargado::with('feligres')->find($this->primeraComunion->encargado_id);
        }
        if (! $encargado) {
            $encargado = Encargado::with('feligres')->where('estado', 'Activo')->first();
        }

        $this->primeraComunion->update([
            'id_iglesia'             => $this->iglesia_id,
            'fecha_primera_comunion' => $this->fecha_primera_comunion,
            'libro_comunion'         => $this->libro_comunion      ?: null,
            'folio'                  => $this->folio                ?: null,
            'partida_numero'         => $this->partida_numero       ?: null,
            'observaciones'          => $this->observaciones        ?: null,
            'nota_marginal'          => $this->nota_marginal        ?: null,
            'lugar_celebracion'      => $this->lugar_celebracion    ?: null,
            'lugar_expedicion'       => $this->lugar_expedicion     ?: null,
            'fecha_expedicion'       => $fechaExp,
            'id_catequista'          => $this->catequista_feligres_id,
            'id_ministro'            => $this->ministro_feligres_id,
            'id_parroco'             => $encargado?->feligres?->id,
            'encargado_id'           => $encargado?->id,
        ]);

        session()->flash('success', 'Primera comunión actualizada correctamente.');
        $this->redirect(route('primera-comunion.index'), navigate: false);
    }

    public function render()
    {
        $iglesiaActual = session('tenant')
            ? TenantIglesia::current()
            : Iglesias::find($this->primeraComunion->id_iglesia);

        return view('livewire.primera-comunion.primera-comunion-edit', compact('iglesiaActual'));
    }
}