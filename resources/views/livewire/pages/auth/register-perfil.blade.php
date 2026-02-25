<?php

use App\Models\Persona;
use App\Models\Feligres;
use App\Models\Encargado;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.guest')] class extends Component
{
    use WithFileUploads;

    public int $step = 1;

    // --- Paso 1: Datos Persona ---
    public string $primer_nombre = '';
    public string $segundo_nombre = '';
    public string $primer_apellido = '';
    public string $segundo_apellido = '';
    public string $fecha_nacimiento = '';
    public string $sexo = '';
    public string $telefono = '';
    public string $dni = '';
    public string $email = '';

    // --- Paso 2: Datos Feligrés ---
    public string $fecha_ingreso = '';
    public string $estado = 'Activo';

    // --- Paso 3: Encargado / Firma ---
    public $path_firma_principal = null;

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validateStepOne();
        }

        if ($this->step === 2) {
            $this->validateStepTwo();
        }

        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function register(): void
    {
        $this->validateStepOne();
        $this->validateStepTwo();
        $this->validateStepThree();

        $firmaPath = null;
        if ($this->path_firma_principal) {
            $firmaPath = $this->path_firma_principal->store('firmas', 'public');
        }

        DB::transaction(function () use ($firmaPath) {
            // 1. Crear persona
            $persona = Persona::create([
                'primer_nombre'    => $this->primer_nombre,
                'segundo_nombre'   => $this->segundo_nombre ?: null,
                'primer_apellido'  => $this->primer_apellido,
                'segundo_apellido' => $this->segundo_apellido ?: null,
                'fecha_nacimiento' => $this->fecha_nacimiento ?: null,
                'sexo'             => $this->sexo ?: null,
                'telefono'         => $this->telefono ?: null,
                'dni'              => $this->dni ?: null,
                'email'            => $this->email ?: null,
            ]);

            // 2. Crear feligrés con los datos de esa persona
            $feligres = Feligres::create([
            'id_persona'    => $persona->id,
            'id_iglesia'    => DB::table('iglesias')->first()->id, // ← toma el id de la iglesia en el tenant
            'fecha_ingreso' => $this->fecha_ingreso ?: null,
            'estado'        => $this->estado,
            ]);

            // 3. Crear encargado con ese feligrés
            Encargado::create([
                'id_feligres'          => $feligres->id,
                'path_firma_principal' => $firmaPath,
            ]);
        });

        session()->flash('success', 'Encargado registrado exitosamente.');
        $this->redirect(route('dashboard'), navigate: true);
    }

    private function validateStepOne(): void
    {
        $this->validate([
            'primer_nombre'    => ['required', 'string', 'min:2', 'max:150'],
            'segundo_nombre'   => ['nullable', 'string', 'max:150'],
            'primer_apellido'  => ['required', 'string', 'min:2', 'max:100'],
            'segundo_apellido' => ['nullable', 'string', 'max:100'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'sexo'             => ['nullable', 'in:M,F'],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'dni'              => ['nullable', 'string'],
            'email'            => ['nullable', 'email', 'max:100'],
        ], [
            'primer_nombre.required'   => 'El primer nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
        ]);
    }

    private function validateStepTwo(): void
    {
        $this->validate([
            'fecha_ingreso' => ['nullable', 'date'],
            'estado'        => ['required', 'in:Activo,Inactivo'],
        ]);
    }

    private function validateStepThree(): void
    {
        $this->validate([
            'path_firma_principal' => ['nullable', 'image', 'max:2048'],
        ], [
            'path_firma_principal.image' => 'El archivo debe ser una imagen.',
            'path_firma_principal.max'   => 'La imagen no debe superar los 2MB.',
        ]);
    }
}; ?>

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">Registrar Encargado</h1>
        <p class="mt-2 text-sm text-gray-600">Completa los 3 pasos para registrar un nuevo encargado.</p>
    </div>

    {{-- Stepper --}}
    <div class="flex items-center gap-2">
        @foreach ([1 => 'Persona', 2 => 'Feligrés', 3 => 'Firma'] as $num => $label)
            <div class="flex items-center gap-2 {{ $num < 3 ? 'flex-1' : '' }}">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-semibold
                    {{ $step > $num ? 'bg-green-500 text-white' : ($step === $num ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500') }}">
                    {{ $step > $num ? '✓' : $num }}
                </span>
                <span class="text-sm {{ $step >= $num ? 'text-gray-900 font-medium' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @if ($num < 3)
                <div class="h-px flex-1 bg-gray-200"></div>
            @endif
        @endforeach
    </div>

    {{-- PASO 1: Datos Personales --}}
    @if ($step === 1)
        <form wire:submit="nextStep" class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Datos Personales</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="primer_nombre" value="Primer Nombre *" />
                    <x-text-input wire:model="primer_nombre" id="primer_nombre" class="mt-1 block w-full" type="text" required autofocus />
                    <x-input-error :messages="$errors->get('primer_nombre')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="segundo_nombre" value="Segundo Nombre" />
                    <x-text-input wire:model="segundo_nombre" id="segundo_nombre" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('segundo_nombre')" class="mt-1" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="primer_apellido" value="Primer Apellido *" />
                    <x-text-input wire:model="primer_apellido" id="primer_apellido" class="mt-1 block w-full" type="text" required />
                    <x-input-error :messages="$errors->get('primer_apellido')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="segundo_apellido" value="Segundo Apellido" />
                    <x-text-input wire:model="segundo_apellido" id="segundo_apellido" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('segundo_apellido')" class="mt-1" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="fecha_nacimiento" value="Fecha de Nacimiento" />
                    <x-text-input wire:model="fecha_nacimiento" id="fecha_nacimiento" class="mt-1 block w-full" type="date" />
                    <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="sexo" value="Sexo" />
                    <select wire:model="sexo" id="sexo"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">-- Seleccionar --</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                    <x-input-error :messages="$errors->get('sexo')" class="mt-1" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="dni" value="DNI / Documento" />
                    <x-text-input wire:model="dni" id="dni" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('dni')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="telefono" value="Teléfono" />
                    <x-text-input wire:model="telefono" id="telefono" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('telefono')" class="mt-1" />
                </div>
            </div>

            <div>
                <x-input-label for="email" value="Correo Electrónico" />
                <x-text-input wire:model="email" id="email" class="mt-1 block w-full" type="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>Siguiente →</x-primary-button>
            </div>
        </form>
    @endif

    {{-- PASO 2: Datos Feligrés --}}
    @if ($step === 2)
        <form wire:submit="nextStep" class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Datos de Feligrés</p>

            {{-- Resumen persona --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700 space-y-1">
                <div>Persona: <span class="font-semibold">{{ $primer_nombre }} {{ $primer_apellido }}</span></div>
                @if($dni) <div>DNI: <span class="font-semibold">{{ $dni }}</span></div> @endif
                @if($fecha_nacimiento) <div>Nacimiento: <span class="font-semibold">{{ $fecha_nacimiento }}</span></div> @endif
            </div>

            <div>
                <x-input-label for="fecha_ingreso" value="Fecha de Ingreso" />
                <x-text-input wire:model="fecha_ingreso" id="fecha_ingreso" class="mt-1 block w-full" type="date" />
                <x-input-error :messages="$errors->get('fecha_ingreso')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="estado" value="Estado *" />
                <select wire:model="estado" id="estado"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
                <x-input-error :messages="$errors->get('estado')" class="mt-1" />
            </div>

            <div class="flex items-center justify-between">
                <x-secondary-button type="button" wire:click="previousStep">← Volver</x-secondary-button>
                <x-primary-button>Siguiente →</x-primary-button>
            </div>
        </form>
    @endif

    {{-- PASO 3: Firma --}}
    @if ($step === 3)
        <form wire:submit="register" class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Firma del Encargado</p>

            {{-- Resumen --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700 space-y-1">
                <div>Persona: <span class="font-semibold">{{ $primer_nombre }} {{ $primer_apellido }}</span></div>
                @if($dni) <div>DNI: <span class="font-semibold">{{ $dni }}</span></div> @endif
                <div>Fecha ingreso: <span class="font-semibold">{{ $fecha_ingreso ?: 'No especificada' }}</span></div>
                <div>Estado: <span class="font-semibold">{{ $estado }}</span></div>
            </div>

            <div>
                <x-input-label for="path_firma_principal" value="Imagen de Firma (opcional)" />
                <div class="mt-1">
                    <input
                        wire:model="path_firma_principal"
                        id="path_firma_principal"
                        type="file"
                        accept="image/*"
                        class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100"
                    />
                </div>
                <x-input-error :messages="$errors->get('path_firma_principal')" class="mt-1" />

                @if ($path_firma_principal)
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 mb-1">Vista previa:</p>
                        <img src="{{ $path_firma_principal->temporaryUrl() }}"
                            alt="Vista previa firma"
                            class="h-32 w-auto rounded-md border border-gray-200 object-contain shadow-sm" />
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <x-secondary-button type="button" wire:click="previousStep">← Volver</x-secondary-button>
                <x-primary-button>
                    <span wire:loading.remove wire:target="register">Registrar Encargado</span>
                    <span wire:loading wire:target="register">Guardando...</span>
                </x-primary-button>
            </div>
        </form>
    @endif

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
</div>