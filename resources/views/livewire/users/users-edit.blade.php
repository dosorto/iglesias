<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Editar Usuario</h1>
        <p class="text-gray-600 dark:text-gray-300">Modifica la información del usuario seleccionado</p>
    </div>

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <p class="text-red-800 dark:text-red-200 font-medium text-sm">{{ session('error') }}</p>
        </div>
    @endif

    @if (session()->has('user_credentials'))
        @php($credentials = session('user_credentials'))
        <div class="mb-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M22 12a10 10 0 11-20 0 10 10 0 0120 0z"></path>
                </svg>
                <div>
                    <p class="text-amber-800 dark:text-amber-200 font-semibold">Credenciales temporales del instructor</p>
                    <p class="text-amber-800 dark:text-amber-200 text-sm mt-1">Correo: {{ $credentials['email'] ?? '-' }}</p>
                    @if(!empty($credentials['password']))
                        <p class="text-amber-800 dark:text-amber-200 text-sm">Clave temporal: {{ $credentials['password'] }}</p>
                        <p class="text-amber-700 dark:text-amber-300 text-xs mt-2">Comparte esta clave solo una vez; se ocultará automáticamente después del primer login del instructor.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="update" class="space-y-6">
        <!-- Nombre -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nombre Completo
            </label>
            <input
                wire:model.defer="name"
                type="text"
                id="name"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                required
            >
            @error('name')
                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Correo Electrónico
            </label>
            <input
                wire:model.defer="email"
                type="email"
                id="email"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                required
            >
            @error('email')
                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Contraseña (opcional) -->
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nueva Contraseña (opcional)
                </label>
                <input
                    wire:model.defer="password"
                    type="password"
                    id="password"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    placeholder="Dejar vacío para mantener la actual"
                >
                @error('password')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Confirmar Nueva Contraseña
                </label>
                <input
                    wire:model.defer="password_confirmation"
                    type="password"
                    id="password_confirmation"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                >
            </div>
        </div>

        <!-- Roles -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Roles</h2>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($roles as $role)
                    @if($role->name === 'root' && ! $canAssignRootRole)
                        @continue
                    @endif
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            wire:model="selectedRoles"
                            value="{{ $role->name }}"
                            class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-800">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            {{ $role->name }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('selectedRoles')
                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
            <a href="{{ route('users.index') }}"
               class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                Cancelar
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                Actualizar Usuario
            </button>
        </div>
    </form>
</div>