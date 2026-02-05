<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Crear Usuario</h1>
        <p class="text-gray-600 dark:text-gray-300">Agrega un nuevo usuario al sistema</p>
    </div>

    <form wire:submit="store" class="space-y-6">
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

        <!-- Contraseña -->
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Contraseña
                </label>
                <input
                    wire:model.defer="password"
                    type="password"
                    id="password"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    required
                >
                @error('password')
                    <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Confirmar Contraseña
                </label>
                <input
                    wire:model.defer="password_confirmation"
                    type="password"
                    id="password_confirmation"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    required
                >
            </div>
        </div>

        <!-- Roles -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Roles</h2>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($roles as $role)
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
                Crear Usuario
            </button>
        </div>
    </form>
</div>