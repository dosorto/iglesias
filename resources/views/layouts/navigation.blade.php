<nav class="bg-white border-b p-4 flex gap-4">
    <a href="/" class="font-bold">Dashboard</a>

    @if(auth()->user()?->hasAnyRole(['admin', 'root']))
        <a href="{{ route('users.index') }}">Usuarios</a>
    @endif

    @if(auth()->user()?->hasAnyRole(['admin', 'root']))
        <a href="{{ route('roles.index') }}">Roles</a>
    @endif

    <form method="POST" action="{{ route('logout') }}" class="ml-auto">
        @csrf
        <button class="text-red-600">Salir</button>
    </form>
</nav>
