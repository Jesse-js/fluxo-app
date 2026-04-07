<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav class="topbar">
        <div class="brand">
            <div class="brand-icon">✦</div>
            {{ config('app.name') }}
        </div>
        <div class="user-info">
            <span class="user-name">{{ Auth::user()->name }}</span>
            <form action="{{ route('auth.logout') }}" method="POST" style="margin:0">
                @csrf
                <button type="submit" class="btn-logout">Sair</button>
            </form>
        </div>
    </nav>
    <main class="main">
        @yield('content')
    </main>
</body>

</html>
