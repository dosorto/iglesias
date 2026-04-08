<div id="sidebar-component">
    {{-- Sidebar --}}
    <aside id="sidebar"
           class="block relative h-screen shrink-0
                bg-gradient-to-b from-emerald-50 to-white dark:from-gray-900 dark:to-gray-800
                border-r border-emerald-200 dark:border-gray-700
                  transition-all duration-300 ease-in-out shadow-xl dark:shadow-gray-900
                  {{ $isCollapsed ? 'w-16' : 'w-64' }}"
           data-collapsed="{{ $isCollapsed ? 'true' : 'false' }}">

        <div class="h-full px-3 py-4 overflow-y-auto flex flex-col">

            {{-- Logo/Header --}}
            <div class="flex items-center mb-6 px-2 {{ $isCollapsed ? 'justify-center' : '' }}">
                 <img src="{{ $logoUrl ?? asset('image/Logo_guest.png') }}" 
                     alt="Logo Parroquial"
                     class="w-10 h-10 object-contain {{ $isCollapsed ? '' : 'mr-3' }}" />
                @if(!$isCollapsed)
                    <span class="text-xl font-bold text-[var(--color-purpura-sagrado)] dark:text-white">{{ $churchName }}</span>
                @endif
            </div>

            {{-- Contenido principal --}}
            <div class="flex-1">
                @php
                    $isInstructorOnly = auth()->user()?->hasRole('instructor')
                        && ! auth()->user()?->hasAnyRole(['root', 'admin']);
                @endphp

                {{-- Sección Principal --}}
                @if(!$isCollapsed)
                    <div class="mb-4">
                        <h3 class="px-2 text-xs font-semibold text-emerald-700 dark:text-gray-400 uppercase tracking-wider">
                            Principal
                        </h3>
                    </div>
                @endif

                <ul class="space-y-2 font-medium mb-8">

                    {{-- Dashboard --}}
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                  rounded-xl text-gray-700 dark:text-gray-200 hover:bg-emerald-100 dark:hover:bg-emerald-900/30
                                  hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors duration-200 group"
                           title="{{ $isCollapsed ? 'Dashboard' : '' }}">
                               <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors duration-200"
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
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-emerald-100 dark:hover:bg-emerald-900/30
                                      hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Personas' : '' }}">
                                  <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors duration-200"
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
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-emerald-100 dark:hover:bg-emerald-900/30
                                      hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Feligreses' : '' }}">
                                  <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors duration-200"
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
                    @if(auth()->user()?->can('instructor.view') && ! $isInstructorOnly)
                        <li>
                            <a href="{{ route('instructor.index') }}"
                                class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                    rounded-xl text-gray-700 dark:text-gray-200 hover:bg-amber-100 dark:hover:bg-amber-900/30
                                    hover:text-amber-700 dark:hover:text-amber-300 transition-colors duration-200 group"
                            title="{{ $isCollapsed ? 'Instructores' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-amber-700 dark:group-hover:text-amber-300 transition-colors duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Instructores</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    {{-- Sacramentos --}}
                    @canany(['bautismo.view', 'matrimonio.view', 'confirmacion.view', 'primera-comunion.view'])
                        <li>
                            <a href="{{ route('sacramentos.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-sky-100 dark:hover:bg-sky-900/30
                                      hover:text-sky-700 dark:hover:text-sky-300 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Sacramentos' : '' }}">
                                  <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-sky-700 dark:group-hover:text-sky-300 transition-colors duration-200"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 2l2.2 4.5 5 .7-3.6 3.5.9 4.9L12 13.9 7.5 15.6l.9-4.9-3.6-3.5 5-.7L12 2z" />
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Sacramentos</span>
                                @endif
                            </a>
                        </li>
                    @endcanany

                    {{-- Cursos --}}
                    @can('curso.view')
                    <li>
                        <a href="{{ route('curso.index') }}"
                        class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                            rounded-xl text-gray-700 dark:text-gray-200 hover:bg-sky-100 dark:hover:bg-sky-900/30
                            hover:text-sky-700 dark:hover:text-sky-300 transition-colors duration-200 group"
                        title="{{ $isCollapsed ? 'Cursos' : '' }}">
                        
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-sky-700 dark:group-hover:text-sky-300 transition-colors duration-200"
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
                    
                    {{-- Parroquias --}}
                    @can('iglesias.view')
                        <li>
                            <a href="{{ route('iglesias.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-emerald-100 dark:hover:bg-emerald-900/30
                                      hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Parroquias' : '' }}">
                                  <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors duration-200"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Parroquias</span>
                                @endif
                            </a>
                        </li>
                    @endcan
                    
                    {{-- Religion --}}
                    @can('religion.view')
                        <li>
                            <a href="{{ route('religion.index') }}"
                                class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                    rounded-xl text-gray-700 dark:text-gray-200 hover:bg-emerald-100 dark:hover:bg-emerald-900/30
                                    hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors duration-200 group"
                                title="{{ $isCollapsed ? 'Religion' : '' }}">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors duration-200"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                </svg>
                                @if(!$isCollapsed)
                                    <span class="ml-3">Religion</span>
                                @endif
                            </a>
                        </li>
                    @endcan

                    {{-- Usuarios --}}
                    @can('users.view')
                        <li>
                            <a href="{{ route('users.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-emerald-100 dark:hover:bg-emerald-900/30
                                      hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Usuarios' : '' }}">
                                  <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors duration-200"
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
                        <h3 class="px-2 text-xs font-semibold text-emerald-700 dark:text-gray-400 uppercase tracking-wider">
                            Configuración
                        </h3>
                    </div>
                @endif

                <ul class="space-y-2 font-medium">
                    @can('roles.view')
                        <li>
                            <a href="{{ route('settings.index') }}"
                               class="flex items-center {{ $isCollapsed ? 'justify-center px-2' : 'p-3' }}
                                      rounded-xl text-gray-700 dark:text-gray-200 hover:bg-amber-100 dark:hover:bg-amber-900/30
                                      hover:text-amber-700 dark:hover:text-amber-300 transition-colors duration-200 group"
                               title="{{ $isCollapsed ? 'Configuración' : '' }}">
                                  <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-amber-700 dark:group-hover:text-amber-300 transition-colors duration-200"
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
                        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-gray-800 border border-emerald-200 dark:border-gray-700 text-center">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Sistema de Gestión Parroquial</p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </aside>
</div>