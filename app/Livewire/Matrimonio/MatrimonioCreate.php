<?php

namespace App\Livewire\Matrimonio;

use Livewire\Component;
use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Iglesias;
use App\Models\TenantIglesia;
use App\Models\Encargado;
use App\Models\Matrimonio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MatrimonioCreate extends Component
{
    // Wizard
    public int $paso = 1;

    // Paso 1 – datos generales
    public $iglesia_id        = null;
    public string $fecha_matrimonio = '';
    public $encargado_id      = null;

    // Roles: esposo, esposa, testigo1, testigo2
    public string $esposo_dni         = '';
    public ?array $esposo_persona     = null;
    public ?int   $esposo_feligres_id = null;
    public string $esposo_estado      = 'idle';

    public string $esposa_dni         = '';
    public ?array $esposa_persona     = null;
    public ?int   $esposa_feligres_id = null;
    public string $esposa_estado      = 'idle';

    public string $testigo1_dni         = '';
    public ?array $testigo1_persona     = null;
    public ?int   $testigo1_feligres_id = null;
    public string $testigo1_estado      = 'idle';

    public string $testigo2_dni         = '';
    public ?array $testigo2_persona     = null;
    public ?int   $testigo2_feligres_id = null;
    public string $testigo2_estado      = 'idle';

    // Busqueda con resultados multiples
    public array   $busqueda_resultados = [];
    public ?string $busqueda_rol        = null;

    // Mini-form compartido
    public ?string $mini_rol  = null;
    public ?string $mini_tipo = null;

    public string $mini_p_dni              = '';
    public string $mini_p_primer_nombre    = '';
    public string $mini_p_segundo_nombre   = '';
    public string $mini_p_primer_apellido  = '';
    public string $mini_p_segundo_apellido = '';
    public string $mini_p_fecha_nacimiento = '';
    public string $mini_p_sexo             = '';
    public string $mini_p_telefono         = '';
    public string $mini_p_email            = '';

    public string $mini_f_fecha_ingreso = '';
    public string $mini_f_estado        = 'Activo';

    public string $advertenciaDuplicado = '';
    public bool   $confirmarDuplicado   = false;

    // Paso 2 – datos del registro
    public string $libro_matrimonio = '';
    public string $folio            = '';
    public string $partida_numero   = '';
    public string $observaciones    = '';
    public string $nota_marginal    = '';
    public string $lugar_expedicion = '';
    public string $exp_dia          = '';
    public string $exp_mes          = '';
    public string $exp_ano          = '';

    public function mount(): void
    {
        $this->fecha_matrimonio     = now()->format('Y-m-d');
        $this->mini_f_fecha_ingreso = now()->format('Y-m-d');
        $this->exp_dia              = now()->format('j');
        $this->exp_mes              = now()->format('n');
        $this->exp_ano              = now()->format('y');
        $this->iglesia_id           = TenantIglesia::currentId();
        $this->aplicarLugarExpedicionPorDefecto();

        $encargadoDefault   = Encargado::with('feligres.persona')->where('estado', 'Activo')->first();
        $this->encargado_id = $encargadoDefault?->id;
    }

    private function aplicarLugarExpedicionPorDefecto(): void
    {
        if (trim($this->lugar_expedicion) !== '') {
            return;
        }

        $direccion = '';

        if (session('tenant')) {
            $direccion = trim((string) (TenantIglesia::current()?->direccion ?? ''));
        } elseif ($this->iglesia_id) {
            $direccion = trim((string) (Iglesias::query()->find($this->iglesia_id)?->direccion ?? ''));
        }

        if ($direccion !== '') {
            $this->lugar_expedicion = $direccion;
        }
    }

    private function resolverLugarExpedicionConfiguracion(): ?string
    {
        if (session('tenant')) {
            $direccion = trim((string) (TenantIglesia::current()?->direccion ?? ''));
        } else {
            $direccion = trim((string) (Iglesias::query()->find($this->iglesia_id)?->direccion ?? ''));
        }

        return $direccion !== '' ? $direccion : null;
    }

    // Navegación

    public function siguientePaso(): void
    {
        if ($this->paso === 1) {
            if (! $this->esposo_feligres_id) {
                $this->addError('esposo_dni', 'El esposo es obligatorio y debe estar registrado como feligrés.');
                return;
            }
            if (! $this->esposa_feligres_id) {
                $this->addError('esposa_dni', 'La esposa es obligatoria y debe estar registrada como feligrés.');
                return;
            }

            if (! $this->validarEspososSexoDiferente()) {
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

    // Buscar persona por DNI o nombre

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
        $this->busqueda_rol          = $rol;
        $this->{"{$rol}_estado"}     = 'multiples';
    }

    // Seleccionar un resultado de la lista

    public function seleccionarResultado(int $personaId): void
    {
        $rol = $this->busqueda_rol;
        if (! $rol) return;

        $persona = Persona::findOrFail($personaId);
        $this->asignarPersonaARol($rol, $persona);
    }

    // Asignar persona a un rol con validación de duplicados

    private function asignarPersonaARol(string $rol, Persona $persona): void
    {
        $roles  = ['esposo', 'esposa', 'testigo1', 'testigo2'];
        $labels = [
            'esposo'   => 'Esposo',
            'esposa'   => 'Esposa',
            'testigo1' => 'Testigo 1',
            'testigo2' => 'Testigo 2',
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

    // Limpiar un rol

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
    }

    // Mini-form: abrir

    public function abrirMini(string $rol, string $tipo): void
    {
        $this->mini_rol  = $rol;
        $this->mini_tipo = $tipo;

        $this->mini_p_dni              = '';
        $this->mini_p_primer_nombre    = '';
        $this->mini_p_segundo_nombre   = '';
        $this->mini_p_primer_apellido  = '';
        $this->mini_p_segundo_apellido = '';
        $this->mini_p_fecha_nacimiento = '';
        $this->mini_p_sexo             = '';
        $this->mini_p_telefono         = '';
        $this->mini_p_email            = '';
        $this->mini_f_fecha_ingreso    = now()->format('Y-m-d');
        $this->mini_f_estado           = 'Activo';

        if ($tipo === 'feligres' && $this->{"{$rol}_persona"}) {
            $p = $this->{"{$rol}_persona"};
            $this->mini_p_dni              = $p['dni']             ?? '';
            $this->mini_p_primer_nombre    = $p['primer_nombre']    ?? '';
            $this->mini_p_primer_apellido  = $p['primer_apellido']  ?? '';
        }
    }

    // Mini-form: cancelar

    public function cancelarMini(): void
    {
        $this->mini_rol  = null;
        $this->mini_tipo = null;
        $this->resetErrorBag();
    }

    public function confirmarYGuardarMiniPersona(): void
    {
        $this->confirmarDuplicado = true;
        $this->guardarMiniPersona();
    }

    // Mini-form: guardar nueva persona + feligrés

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
        ], [
            'mini_p_dni.required'             => 'El número de identidad es obligatorio.',
            'mini_p_dni.min'                  => 'El DNI debe tener al menos 8 caracteres.',
            'mini_p_dni.unique'               => 'Ya existe una persona con ese DNI.',
            'mini_p_primer_nombre.required'   => 'El primer nombre es obligatorio.',
            'mini_p_primer_nombre.regex'      => 'El nombre solo puede contener letras.',
            'mini_p_primer_apellido.required' => 'El primer apellido es obligatorio.',
            'mini_p_primer_apellido.regex'    => 'El apellido solo puede contener letras.',
            'mini_p_fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'mini_p_fecha_nacimiento.before'   => 'La fecha de nacimiento debe ser anterior a hoy.',
            'mini_p_sexo.required'             => 'El sexo es obligatorio.',
            'mini_p_telefono.required'         => 'El teléfono es obligatorio.',
            'mini_p_telefono.regex'            => 'El teléfono solo puede contener números, + y -.',
        ]);

        $rol = $this->mini_rol;

        if (! $this->mini_p_dni && ! $this->confirmarDuplicado) {
            $duplicado = Persona::where('primer_nombre', $this->mini_p_primer_nombre)
                ->where('primer_apellido', $this->mini_p_primer_apellido)
                ->whereNull('dni')
                ->first();
            if ($duplicado) {
                $this->advertenciaDuplicado = $duplicado->nombre_completo;
                return;
            }
        }
        $this->advertenciaDuplicado = '';
        $this->confirmarDuplicado   = false;

        DB::transaction(function () use ($rol) {
            if (session('tenant')) {
                $this->iglesia_id = TenantIglesia::currentId();
            }

            $persona = Persona::create([
                'dni'              => $this->mini_p_dni ?: null,
                'primer_nombre'    => Str::title($this->mini_p_primer_nombre),
                'segundo_nombre'   => $this->mini_p_segundo_nombre ? Str::title($this->mini_p_segundo_nombre) : null,
                'primer_apellido'  => Str::title($this->mini_p_primer_apellido),
                'segundo_apellido' => $this->mini_p_segundo_apellido ? Str::title($this->mini_p_segundo_apellido) : null,
                'fecha_nacimiento' => $this->mini_p_fecha_nacimiento ?: null,
                'sexo'             => $this->mini_p_sexo === 'M' ? 'M' : ($this->mini_p_sexo === 'F' ? 'F' : null),
                'telefono'         => $this->mini_p_telefono ?: null,
                'email'            => $this->mini_p_email    ?: null,
            ]);

            $feligres = Feligres::create([
                'id_persona'    => $persona->id,
                'id_iglesia'    => $this->iglesia_id,
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

    // Mini-form: registrar persona existente como feligrés

    public function guardarMiniFeligres(): void
    {
        $this->validate([
            'iglesia_id'           => ['required'],
            'mini_f_fecha_ingreso' => ['nullable', 'date'],
            'mini_f_estado'        => ['required', 'in:Activo,Inactivo'],
        ], [
            'iglesia_id.required' => 'No se pudo determinar la iglesia.',
        ]);

        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

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

    // Guardar matrimonio final

    public function guardar(): void
    {
        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

        // Estos datos se manejan automaticamente en creacion.
        $this->fecha_matrimonio = now()->format('Y-m-d');
        $this->encargado_id = Encargado::where('estado', 'Activo')->value('id');

        $this->validate([
            'fecha_matrimonio' => ['required', 'date'],
            'nota_marginal'    => ['nullable', 'string', 'max:500'],
            'exp_dia'          => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'          => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'          => ['nullable', 'integer', 'min:0', 'max:99'],
        ], [
            'fecha_matrimonio.required' => 'La fecha de matrimonio es obligatoria.',
            'fecha_matrimonio.date'     => 'La fecha de matrimonio no es válida.',
            'nota_marginal.max'         => 'La nota marginal no puede superar los 500 caracteres.',
            'exp_dia.min'               => 'El día debe ser entre 1 y 31.',
            'exp_mes.min'               => 'El mes debe ser entre 1 y 12.',
        ]);

        if (! $this->esposo_feligres_id) {
            $this->addError('esposo_dni', 'El esposo es obligatorio.');
            return;
        }
        if (! $this->esposa_feligres_id) {
            $this->addError('esposa_dni', 'La esposa es obligatoria.');
            return;
        }

        if (! $this->validarEspososSexoDiferente()) {
            return;
        }

        if (! $this->validarFechaPosteriorNacimiento(
            $this->esposo_feligres_id,
            $this->fecha_matrimonio,
            'esposo_dni',
            'matrimonio'
        )) {
            return;
        }

        if (! $this->validarFechaPosteriorNacimiento(
            $this->esposa_feligres_id,
            $this->fecha_matrimonio,
            'esposa_dni',
            'matrimonio'
        )) {
            return;
        }

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

        $lugarExpedicion = $this->resolverLugarExpedicionConfiguracion();
        $this->lugar_expedicion = $lugarExpedicion ?? '';

        Matrimonio::create([
            'iglesia_id'       => $this->iglesia_id,
            'fecha_matrimonio' => $this->fecha_matrimonio,
            'encargado_id'     => $this->encargado_id ?: null,
            'esposo_id'        => $this->esposo_feligres_id,
            'esposa_id'        => $this->esposa_feligres_id,
            'testigo1_id'      => $this->testigo1_feligres_id,
            'testigo2_id'      => $this->testigo2_feligres_id,
            'libro_matrimonio' => $this->libro_matrimonio ?: null,
            'folio'            => $this->folio            ?: null,
            'partida_numero'   => $this->partida_numero   ?: null,
            'observaciones'    => $this->observaciones    ?: null,
            'nota_marginal'    => $this->nota_marginal    ?: null,
            'lugar_expedicion' => $lugarExpedicion,
            'fecha_expedicion' => $fechaExp,
        ]);

        session()->flash('success', 'Matrimonio registrado correctamente.');
        $this->redirect(route('matrimonio.index'), navigate: false);
    }

    public function render()
    {
        $centralConn = config('tenancy.central_connection', 'mysql');

        if (session('tenant')) {
            $iglesias = collect([TenantIglesia::current()])->filter();
        } else {
            $iglesias = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        }

        return view('livewire.matrimonio.matrimonio-create', [
            'iglesias'   => $iglesias,
        ]);
    }

    private function validarFechaPosteriorNacimiento(?int $feligresId, string $fechaSacramento, string $campo, string $label): bool
    {
        if (! $feligresId || ! $fechaSacramento) {
            return true;
        }

        $persona = \App\Models\Feligres::with('persona:id,fecha_nacimiento')->find($feligresId)?->persona;
        if (! $persona?->fecha_nacimiento) {
            return true;
        }

        try {
            if (\Carbon\Carbon::parse($fechaSacramento)->lt(\Carbon\Carbon::parse($persona->fecha_nacimiento))) {
                $this->addError($campo, "La fecha de {$label} no puede ser anterior a la fecha de nacimiento.");
                return false;
            }
        } catch (\Exception) {}

        return true;
    }

    private function validarEspososSexoDiferente(): bool
    {
        if (! $this->esposo_feligres_id || ! $this->esposa_feligres_id) {
            return true;
        }

        $esposoSexo = Feligres::with('persona:id,sexo')->find($this->esposo_feligres_id)?->persona?->sexo;
        $esposaSexo = Feligres::with('persona:id,sexo')->find($this->esposa_feligres_id)?->persona?->sexo;

        $esposoSexoCanon = $this->normalizarSexoCanonico($esposoSexo);
        $esposaSexoCanon = $this->normalizarSexoCanonico($esposaSexo);

        if (! $esposoSexoCanon || ! $esposaSexoCanon) {
            return true;
        }

        if ($esposoSexoCanon === $esposaSexoCanon) {
            $this->paso = 1;
            $this->addError('esposa_dni', 'No se pueden casar los del mismo sexo.');
            return false;
        }

        if ($esposoSexoCanon !== 'M' || $esposaSexoCanon !== 'F') {
            $this->paso = 1;
            $this->addError('esposo_dni', 'El matrimonio debe registrarse con hombre como esposo y mujer como esposa.');
            return false;
        }

        return true;
    }

    private function normalizarSexoCanonico(?string $sexo): ?string
    {
        if (! $sexo) {
            return null;
        }

        $valor = mb_strtolower(trim($sexo));

        if (in_array($valor, ['m', 'masculino', 'hombre'], true)) {
            return 'M';
        }

        if (in_array($valor, ['f', 'femenino', 'mujer'], true)) {
            return 'F';
        }

        return null;
    }
}
