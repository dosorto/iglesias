<?php

namespace App\Livewire\Matrimonio;

use App\Models\Matrimonio;
use App\Models\Iglesias;
use App\Models\Encargado;
use App\Models\Feligres;
use App\Models\Persona;
use App\Models\TenantIglesia;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MatrimonioEdit extends Component
{
    public Matrimonio $matrimonio;

    public ?int   $iglesia_id        = null;
    public ?int   $encargado_id      = null;
    public string $fecha_matrimonio  = '';
    public string $libro_matrimonio  = '';
    public string $folio             = '';
    public string $partida_numero    = '';
    public string $observaciones     = '';
    public string $nota_marginal     = '';
    public string $lugar_expedicion  = '';
    public string $exp_dia           = '';
    public string $exp_mes           = '';
    public string $exp_ano           = '';

    // Roles editables: esposo, esposa, testigos
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

    // Busqueda compartida
    public array   $busqueda_resultados = [];
    public ?string $busqueda_rol        = null;

    public function mount(Matrimonio $matrimonio): void
    {
        $this->matrimonio       = $matrimonio;
        $this->iglesia_id       = session('tenant') ? TenantIglesia::currentId() : $matrimonio->iglesia_id;
        $this->encargado_id     = $matrimonio->encargado_id;
        $this->fecha_matrimonio = $matrimonio->fecha_matrimonio?->format('Y-m-d') ?? '';
        $this->libro_matrimonio = $matrimonio->libro_matrimonio ?? '';
        $this->folio            = $matrimonio->folio ?? '';
        $this->partida_numero   = $matrimonio->partida_numero ?? '';
        $this->observaciones    = $matrimonio->observaciones ?? '';
        $this->nota_marginal    = $matrimonio->nota_marginal ?? '';
        $this->lugar_expedicion = $matrimonio->lugar_expedicion ?? '';

        $fechaExp      = $matrimonio->fecha_expedicion;
        $this->exp_dia = $fechaExp?->day   ? (string) $fechaExp->day   : '';
        $this->exp_mes = $fechaExp?->month ? (string) $fechaExp->month : '';
        $this->exp_ano = $fechaExp?->year  ? (string) ($fechaExp->year - 2000) : '';

        $this->cargarRolExistente('esposo', $matrimonio->esposo_id);
        $this->cargarRolExistente('esposa', $matrimonio->esposa_id);
        $this->cargarRolExistente('testigo1', $matrimonio->testigo1_id);
        $this->cargarRolExistente('testigo2', $matrimonio->testigo2_id);
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
            'email'           => $persona->email ?? null,
        ];
        $this->{"{$rol}_feligres_id"} = $feligresId;
        $this->{"{$rol}_dni"}         = $persona->dni;
        $this->{"{$rol}_estado"}      = 'found';
    }

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

            $term = '%' . $input . '%';
            $personas = Persona::where(function ($q) use ($term) {
                $q->where('primer_nombre', 'like', $term)
                  ->orWhere('segundo_nombre', 'like', $term)
                  ->orWhere('primer_apellido', 'like', $term)
                  ->orWhere('segundo_apellido', 'like', $term);
            })->orderBy('primer_apellido')->orderBy('primer_nombre')->limit(15)->get();
        }

        if ($personas->isEmpty()) {
            $this->{"{$rol}_persona"}     = null;
            $this->{"{$rol}_feligres_id"} = null;
            $this->{"{$rol}_estado"}      = 'sin_persona';
            $this->busqueda_resultados      = [];
            $this->busqueda_rol             = null;
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
        $roles = ['esposo', 'esposa', 'testigo1', 'testigo2'];
        $labels = [
            'esposo' => 'Esposo',
            'esposa' => 'Esposa',
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
            'email'           => $persona->email ?? null,
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
    }

    protected function rules(): array
    {
        $tenantIglesiaId = session('tenant') ? TenantIglesia::currentId() : null;

        return [
            'iglesia_id'       => array_filter([
                'required',
                'integer',
                'exists:iglesias,id',
                $tenantIglesiaId ? Rule::in([$tenantIglesiaId]) : null,
            ]),
            'encargado_id'     => ['required', 'integer', 'exists:encargado,id'],
            'esposo_feligres_id' => ['required', 'integer', 'exists:feligres,id'],
            'esposa_feligres_id' => ['required', 'integer', 'exists:feligres,id'],
            'testigo1_feligres_id' => ['nullable', 'integer', 'exists:feligres,id'],
            'testigo2_feligres_id' => ['nullable', 'integer', 'exists:feligres,id'],
            'fecha_matrimonio' => ['required', 'date', 'before_or_equal:today'],
            'libro_matrimonio' => ['nullable', 'string', 'max:100'],
            'folio'            => ['nullable', 'string', 'max:50'],
            'partida_numero'   => ['nullable', 'string', 'max:50'],
            'observaciones'    => ['nullable', 'string', 'max:500'],
            'nota_marginal'    => ['nullable', 'string', 'max:500'],
            'lugar_expedicion' => ['nullable', 'string', 'max:150'],
            'exp_dia'          => ['nullable', 'integer', 'min:1', 'max:31'],
            'exp_mes'          => ['nullable', 'integer', 'min:1', 'max:12'],
            'exp_ano'          => ['nullable', 'integer', 'min:0', 'max:99'],
        ];
    }

    protected function messages(): array
    {
        return [
            'iglesia_id.required'            => 'Debes seleccionar una iglesia.',
            'iglesia_id.exists'              => 'La iglesia seleccionada no existe.',
            'encargado_id.required'          => 'Debes seleccionar un encargado.',
            'encargado_id.exists'            => 'El encargado seleccionado no existe.',
            'esposo_feligres_id.required'    => 'Debes asignar un esposo feligrés.',
            'esposo_feligres_id.exists'      => 'El esposo seleccionado no existe.',
            'esposa_feligres_id.required'    => 'Debes asignar una esposa feligrés.',
            'esposa_feligres_id.exists'      => 'La esposa seleccionada no existe.',
            'fecha_matrimonio.required'      => 'La fecha de matrimonio es obligatoria.',
            'fecha_matrimonio.date'          => 'La fecha de matrimonio no es válida.',
            'fecha_matrimonio.before_or_equal' => 'La fecha de matrimonio no puede ser futura.',
            'libro_matrimonio.max'           => 'El libro no puede superar los 100 caracteres.',
            'folio.max'                      => 'El folio no puede superar los 50 caracteres.',
            'partida_numero.max'             => 'La partida no puede superar los 50 caracteres.',
            'observaciones.max'              => 'Las observaciones no pueden superar los 500 caracteres.',
            'nota_marginal.max'              => 'La nota marginal no puede superar los 500 caracteres.',
            'lugar_expedicion.max'           => 'El lugar de expedición no puede superar los 150 caracteres.',
            'exp_dia.min'                    => 'El día de expedición debe ser entre 1 y 31.',
            'exp_mes.min'                    => 'El mes de expedición debe ser entre 1 y 12.',
        ];
    }

    public function updated(string $field): void
    {
        $this->validateOnly($field);
    }

    public function guardar(): void
    {
        if (session('tenant')) {
            $this->iglesia_id = TenantIglesia::currentId();
        }

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

        $this->matrimonio->update([
            'iglesia_id'       => $this->iglesia_id,
            'encargado_id'     => $this->encargado_id,
            'esposo_id'        => $this->esposo_feligres_id,
            'esposa_id'        => $this->esposa_feligres_id,
            'testigo1_id'      => $this->testigo1_feligres_id,
            'testigo2_id'      => $this->testigo2_feligres_id,
            'fecha_matrimonio' => $this->fecha_matrimonio,
            'libro_matrimonio' => $this->libro_matrimonio ?: null,
            'folio'            => $this->folio            ?: null,
            'partida_numero'   => $this->partida_numero   ?: null,
            'observaciones'    => $this->observaciones    ?: null,
            'nota_marginal'    => $this->nota_marginal    ?: null,
            'lugar_expedicion' => $this->lugar_expedicion ?: null,
            'fecha_expedicion' => $fechaExp,
        ]);

        session()->flash('success', 'Matrimonio actualizado correctamente.');
        $this->redirect(route('matrimonio.index'), navigate: false);
    }

    public function render()
    {
        if (session('tenant')) {
            $iglesias = TenantIglesia::query()->where('id', TenantIglesia::currentId())->get();
        } else {
            $centralConn = config('tenancy.central_connection', 'mysql');
            $iglesias = Iglesias::on($centralConn)->where('estado', 'Activo')->orderBy('nombre')->get();
        }
        $iglesiaActual = $iglesias->firstWhere('id', $this->iglesia_id);
        $encargados  = Encargado::with('feligres.persona')->get();

        return view('livewire.matrimonio.matrimonio-edit', compact('iglesias', 'iglesiaActual', 'encargados'));
    }
}
