<aside id="sidebar"
       class="fixed top-16 left-0 z-40 w-64 h-full
              bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700
              transition-transform -translate-x-full sm:translate-x-0
              shadow-xl dark:shadow-gray-900">

    <div class="h-full px-3 py-4 overflow-y-auto bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
        <!-- Logo/Header -->
        <div class="flex items-center mb-6 px-2">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
            </svg>
            <span class="text-xl font-bold text-gray-900 dark:text-white"> Admin</span>
        </div>

        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            @if(auth()->user()?->hasAnyRole(['admin', 'root']))
                <li>
                    <a href="{{ route('users.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                        <span class="ml-3">Usuarios</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()?->hasAnyRole(['admin', 'root']))
                <li>
                    <a href="{{ route('roles.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Roles</span>
                    </a>
                </li>
            @endif

            @can('audit.view')
                <li>
                    <a href="{{ route('audit.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="ml-3">Auditoría</span>
                    </a>
                </li>
            @endcan

            {{-- ── Membresía ─────────────────────────────────── --}}
            @php
                $isInstructorOnly = auth()->user()?->can('instructor.view')
                    && ! auth()->user()?->can('users.view')
                    && ! auth()->user()?->can('roles.view');

                $showInstructorMenu = auth()->user()?->can('instructor.view')
                    && ! $isInstructorOnly;
            @endphp
            @if(auth()->user()?->canAny(['feligres.view','encargado.view']) || (auth()->user()?->can('personas.view') && auth()->user()?->hasRole('root')) || $showInstructorMenu)
                <li class="pt-3">
                    <p class="px-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                        Membresía
                    </p>
                </li>

                @if(auth()->user()?->can('personas.view') && auth()->user()?->hasRole('root'))
                    <li>
                        <a href="{{ route('personas.index') }}"
                           class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="ml-3">Personas</span>
                        </a>
                    </li>
                @endif

                @can('feligres.view')
                    <li>
                        <a href="{{ route('feligres.index') }}"
                           class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="ml-3">Feligreses</span>
                        </a>
                    </li>
                @endcan

                @can('encargado.view')
                    <li>
                        <a href="{{ route('encargado.index') }}"
                           class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="ml-3">Encargados</span>
                        </a>
                    </li>
                @endcan

                @if($showInstructorMenu)
                    <li>
                        <a href="{{ route('instructor.index') }}"
                           class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="ml-3">Instructores</span>
                        </a>
                    </li>
                @endif
            @endif

            {{-- ── Actos Parroquiales ────────────────────────── --}}
            @canany(['bautismo.view','confirmacion.view','curso.view','inscripcion-curso.view','matrimonio.view','primera-comunion.view'])
                <li class="pt-3">
                    <p class="px-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                        Actos Parroquiales
                    </p>
                </li>
            @endcanany
            @canany(['bautismo.view','confirmacion.view','matrimonio.view','primera-comunion.view'])
                <li>
                    <a href="{{ route('sacramentos.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l2.2 4.5 5 .7-3.6 3.5.9 4.9L12 13.9 7.5 15.6l.9-4.9-3.6-3.5 5-.7L12 2z"/>
                        </svg>
                        <span class="ml-3">Sacramentos</span>
                    </a>
                </li>
            @endcanany

            @can('curso.view')
                <li>
                    <a href="{{ route('curso.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="ml-3">Cursos</span>
                    </a>
                </li>
            @endcan

            @can('inscripcion-curso.view')
                <li>
                    <a href="{{ route('inscripcion-curso.index') }}"
                       class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="ml-3">Inscripciones</span>
                    </a>
                </li>
            @endcan

            {{-- ── Configuración ─────────────────────────────── --}}
            @canany(['iglesias.view','religion.view'])
                <li class="pt-3">
                    <p class="px-3 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                        Configuración
                    </p>
                </li>

                @can('iglesias.view')
                    @if(!session('tenant.id_iglesia'))
                    <li>
                        <a href="{{ route('iglesias.index') }}"
                           class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="ml-3">Iglesias</span>
                        </a>
                    </li>
                    @endif
                @endcan

                @can('religion.view')
                    @if(auth()->user()?->hasAnyRole(['admin', 'root']) && !session('tenant.id_iglesia'))
                    <li>
                        <a href="{{ route('religion.index') }}"
                           class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 group">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            <span class="ml-3">Religiones</span>
                        </a>
                    </li>
                    @endif
                @endcan

            @endcanany

        </ul>

        <!-- Footer -->
        <div class="absolute bottom-4 left-3 right-3">
            <div class="p-3 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-600 dark:text-gray-400 text-center">Sistema de Gestión Iglesia</p>
            </div>
        </div>
    </div>
</aside>
