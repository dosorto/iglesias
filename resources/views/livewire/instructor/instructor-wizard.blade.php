<div class="space-y-6">

    {{-- STEPPER --}}
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold
                {{ $step > 1 ? 'bg-green-500 text-white' : 'bg-blue-600 text-white' }}">
                {{ $step > 1 ? '✓' : '1' }}
            </span>
            <span class="text-sm font-medium">Persona</span>
        </div>

        <div class="h-px flex-1 bg-gray-200"></div>

        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold
                {{ $step === 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                2
            </span>
            <span class="text-sm font-medium">Firma</span>
        </div>
    </div>

    {{-- PASO 1 --}}
    @if($step === 1)
        <div class="space-y-4">

            <div>
                <label>DNI *</label>

                <div class="flex gap-2">
                    <input type="text" wire:model="dni"
                        class="border rounded w-full px-3 py-2">

                    <button wire:click="buscarPersona"
                        class="bg-blue-600 text-white px-4 rounded">
                        Buscar
                    </button>

                    <button wire:click="limpiarCampos" type="button"
                        class="bg-gray-400 text-white px-4 rounded">
                        Limpiar
                    </button>
                </div>

                @error('dni')
                    <span class="text-red-600 text-sm block mt-1">{{ $message }}</span>
                @enderror
            </div>
            

            <div class="grid grid-cols-2 gap-4">
                <input type="text" wire:model="primer_nombre"
                    @if($personaExiste) readonly @endif
                    placeholder="Primer Nombre"
                    class="border rounded px-3 py-2">
                @error('primer_nombre')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror

                <input type="text" wire:model="primer_apellido"
                    @if($personaExiste) readonly @endif
                    placeholder="Primer Apellido"
                    class="border rounded px-3 py-2">
                    @error('primer_apellido')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror

                <input type="text" wire:model="telefono"
                    @if($personaExiste) readonly @endif
                    placeholder="Teléfono"
                    class="border rounded px-3 py-2">

                <input type="email" wire:model="email"
                    @if($personaExiste) readonly @endif
                    placeholder="Email"
                    class="border rounded px-3 py-2">
            </div>

            <div class="flex justify-end">
                <button wire:click="nextStep"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Siguiente →
                </button>
            </div>
        </div>
    @endif

    {{-- PASO 2 --}}
    @if($step === 2)
        <div class="space-y-4">

            <div class="border rounded p-3 bg-gray-50">
                <strong>{{ $primer_nombre }} {{ $primer_apellido }}</strong><br>
                DNI: {{ $dni }}
            </div>

            

            <div>
                <label>Firma *</label>
                <input type="file" wire:model="firma">
                @error('firma') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            @if($firma)
                <img src="{{ $firma->temporaryUrl() }}" class="h-24 border rounded">
            @endif

            <div class="flex justify-between">
                <button wire:click="previousStep"
                        class="bg-gray-300 px-4 py-2 rounded">
                    ← Volver
                </button>

                <button wire:click="guardarInstructor"
                        class="bg-blue-600 text-white px-6 py-2 rounded">
                    Guardar Instructor
                </button>
            </div>

        </div>
    @endif

</div>