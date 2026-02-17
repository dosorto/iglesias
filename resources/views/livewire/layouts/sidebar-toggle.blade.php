<div id="sidebar-component">
    {{-- Sidebar --}}
    <aside id="sidebar"
           class="block relative h-screen shrink-0
                  bg-gray-50 dark:bg-gray-900 border-r-2 border-gray-300 dark:border-gray-700
                  transition-all duration-300 ease-in-out shadow-lg dark:shadow-gray-900
                  {{ $isCollapsed ? 'w-16' : 'w-64' }}"
           data-collapsed="{{ $isCollapsed ? 'true' : 'false' }}">


        <div class="h-full px-3 py-4 overflow-y-auto bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 flex flex-col">
            {{-- Logo/Header --}}
            <div class="flex items-center mb-6 px-2 {{ $isCollapsed ? 'justify-center' : '' }}">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 {{ $isCollapsed ? '' : 'mr-3' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
                @if(!$isCollapsed)
                    <span class="text-xl font-bold text-gray-900 dark:text-white">DAO Admin</span>
                @endif
            </div>



            {{-- Contenido principal --}}
            <div class="flex-1">
                {{-- Sección Principal --}}
                @if(!$isCollapsed)
                    <div class="mb-4">
                        <h3 class="px-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Principal
                        </h3>
                    </div>
                @endif

                <ul class="space-y-2 font-medium mb-8">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                           title="{{ $isCollapsed ? 'Dashboard' : '' }}">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                            </svg>
                            @if(!$isCollapsed)
                                <span class="ml-3">Dashboard</span>
                            @endif
                        </a>
                    </li>

                    @can('personas.view')
                        <li>
                            <a href="{{ route('personas.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Personas' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Personas</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('iglesias.view')
                        <li>
                            <a href="{{ route('iglesias.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Iglesias' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Iglesias</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('religion.view')
                        <li>
                            <a href="{{ route('religion.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Religion' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Religion</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('tipocurso.view')
                        <li>
                            <a href="{{ route('tipocurso.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Tipos de Cursos' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a1 1 0 000 2h8a1 1 0 100-2H4zM4 7a1 1 0 000 2h8a1 1 0 100-2H4zM4 11a1 1 0 000 2h8a1 1 0 100-2H4zM4 15a1 1 0 000 2h8a1 1 0 100-2H4z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Tipos de Cursos</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    @can('users.view')
                        <li>
                            <a href="{{ route('users.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Usuarios' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Usuarios</span>
                                @endif
                            </a>
                        </li>
                    @endcan
                </ul>

                {{-- Sección Configuración --}}
                @if(!$isCollapsed)
                    <div class="mb-4">
                        <h3 class="px-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Configuración
                        </h3>
                    </div>
                @endif

                <ul class="space-y-2 font-medium">
                    @can('roles.view')
                        <li>
                            <a href="{{ route('settings.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }} rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Configuración' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Configuración</span>
                                @endif
                            </a>
                        </li>
                    @endcan


                </ul>
            </div>

            {{-- Footer --}}
            <div class="mt-auto">
                @if(!$isCollapsed)
                    <div class="px-2">
                        <div class="p-3 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-600 dark:text-gray-400 text-center">Sistema de Gestión DAO</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </aside>


</div>
