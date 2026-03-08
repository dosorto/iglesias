<div id="sidebar-component">
    {{-- Sidebar --}}
    <aside id="sidebar"
           class="block relative h-screen shrink-0
                  bg-gradient-to-b from-[#6C5DD3]/20 to-[#8A7BE9]/20 dark:from-gray-900 dark:to-gray-800
                  border-r-2 border-gray-300 dark:border-gray-700
                  transition-all duration-300 ease-in-out shadow-xl dark:shadow-gray-900
                  {{ $isCollapsed ? 'w-16' : 'w-64' }}"
           data-collapsed="{{ $isCollapsed ? 'true' : 'false' }}">

        <div class="h-full px-3 py-4 overflow-y-auto flex flex-col">

            {{-- Logo/Header --}}
            <div class="flex items-center mb-6 px-2 {{ $isCollapsed ? 'justify-center' : '' }}">
                <img src="{{ asset('image/Logo_guest.png') }}" 
                     alt="Logo Iglesia"
                     class="w-10 h-10 object-contain {{ $isCollapsed ? '' : 'mr-3' }}" />
                @if(!$isCollapsed)
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Iglesia Admin</span>
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

                    {{-- Dashboard --}}
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                  rounded-xl text-gray-700 dark:text-gray-200 hover:bg-[#4B3FBD]/20 dark:hover:bg-[#6C5DD3]/20
                                  hover:text-[#4B3FBD] dark:hover:text-[#B2A4F2] transition-colors duration-200 group"
                           title="{{ $isCollapsed ? 'Dashboard' : '' }}">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-[#4B3FBD] dark:group-hover:text-[#B2A4F2] transition-colors duration-200"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                            </svg>
                            @if(!$isCollapsed)
                                <span class="ml-3">Dashboard</span>
                            @endif
                        </a>
                    </li>

                    {{-- Personas --}}
                    @can('personas.view')
                        <li>
                            <a href="{{ route('personas.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900
                                      hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Personas' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Personas</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    {{-- Feligreses --}}
                    @can('feligres.view')
                        <li>
                            <a href="{{ route('feligres.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-indigo-100 dark:hover:bg-indigo-900
                                      hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Feligreses' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Feligreses</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    {{-- Instructores --}}
                    @can('instructor.view')
                        <li>
                            <a href="{{ route('instructor.index') }}"
                            class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                    rounded-xl text-gray-700 dark:text-gray-200 hover:bg-amber-100 dark:hover:bg-amber-900
                                    hover:text-amber-600 dark:hover:text-amber-400 transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Instructores' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Instructores</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    {{-- Bautismos --}}
                    @can('bautismo.view')
                        <li>
                            <a href="{{ route('bautismo.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-sky-100 dark:hover:bg-sky-900
                                      hover:text-sky-600 dark:hover:text-sky-400 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Bautismos' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors duration-200"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Bautismos</span>
                                @endif
                            </a>
                        </li>
                    @endcan


                    {{-- Cursos --}}
                    @can('curso.view')
                    <li>
                        <a href="{{ route('curso.index') }}"
                        class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                rounded-xl text-gray-700 dark:text-gray-200 hover:bg-blue-100 dark:hover:bg-blue-900
                                hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 group"
                        title="{{ $isCollapsed ? 'Cursos' : '' }}">
                        
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v11.494m-5.747-8.12l11.494 4.373M6.253 14.373l11.494-4.373"/>
                            </svg>

                            @if(!$isCollapsed)
                                <span class="ml-3">Cursos</span>
                            @endif

                        </a>
                    </li>
                    @endcan
                    
                    {{-- Iglesias --}}
                    @can('iglesias.view')
                        <li>
                            <a href="{{ route('iglesias.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900
                                      hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Iglesias' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Iglesias</span>
                                @endif
                            </a>
                        </li>
                    @endcan
                    
                    {{-- Primera Comunion --}}
                    @can('primera-comunion.view')
                        <li>
                            <a href="{{ route('primera-comunion.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900
                                      hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'primera-comunion' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Primera Comunion </span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    {{-- Religion --}}
                    @can('religion.view')
                        <li>
                            <a href="{{ route('religion.index') }}"
                                class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                    rounded-xl text-gray-700 dark:text-gray-200 hover:bg-purple-100 dark:hover:bg-purple-900
                                    hover:text-purple-600 dark:hover:text-purple-400 transition-colors duration-200 group"
                                title="{{ $isCollapsed ? 'Religion' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Religion</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    

                    {{-- Tipos de Cursos --}}
                    @can('tipocurso.view')
                        <li>
                            <a href="{{ route('tipocurso.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-teal-100 dark:hover:bg-teal-900
                                      hover:text-teal-600 dark:hover:text-teal-400 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Tipos de Cursos' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Tipos de Cursos</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    {{-- Usuarios --}}
                    @can('users.view')
                        <li>
                            <a href="{{ route('users.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-green-100 dark:hover:bg-green-900
                                      hover:text-green-600 dark:hover:text-green-400 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Usuarios' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
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
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-orange-100 dark:hover:bg-orange-900
                                      hover:text-orange-600 dark:hover:text-orange-400 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Configuración' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
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
                        <div class="p-3 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-center">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Sistema de Gestión de Iglesia</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </aside>
</div>