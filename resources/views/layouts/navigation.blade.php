<nav class="bg-white border-b p-4 flex gap-4">
    <a href="/" class="font-bold">Dashboard</a>

    @can('users.view')
        <a href="{{ route('users.index') }}">Usuarios</a>
    @endcan

    @can('roles.view')
        <a href="{{ route('roles.index') }}">Roles</a>
    @endcan

    <form method="POST" action="{{ route('logout') }}" class="ml-auto">
        @csrf
        <button class="text-red-600">Salir</button>
    </form>
</nav>
