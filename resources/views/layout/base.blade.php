<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel') — {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-body bg-[#0d0f14] text-[#cdd3e2] h-full flex overflow-hidden">

    {{-- ══ OVERLAY MOBILE ══ --}}
    <div id="sidebar-overlay" class="fixed inset-0 z-20 bg-black/60 backdrop-blur-sm hidden opacity-0"
        onclick="closeSidebar()"></div>

    {{-- ══════════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════════ --}}
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-30 flex flex-col w-64
                  bg-[#13161d] border-r border-[#272b38]
                  -translate-x-full lg:translate-x-0 overflow-hidden">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-4 h-16 border-b border-[#272b38] shrink-0">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold text-[#0d0f14] shrink-0"
                style="background: linear-gradient(135deg,#e8c97e,#c9a84c)">✦</div>
            <span class="sidebar-brand-name font-display text-lg text-white tracking-tight truncate">
                {{ config('app.name', 'App') }}
            </span>
        </div>

        {{-- Avatar do usuário --}}
        <div class="flex items-center gap-3 px-4 py-4 border-b border-[#272b38] shrink-0">
            <div class="relative shrink-0">
                @if (Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                        class="w-9 h-9 rounded-full object-cover ring-2 ring-[#272b38]">
                @else
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold text-[#0d0f14]"
                        style="background: linear-gradient(135deg,#e8c97e,#c9a84c)">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <span
                    class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-[#6fd0a4] border-2 border-[#13161d] rounded-full"></span>
            </div>
            <div class="overflow-hidden nav-label">
                <p class="text-[.82rem] font-medium text-[#cdd3e2] truncate">{{ Auth::user()->name }}</p>
                <p class="text-[.72rem] text-[#4a5068] truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>

        {{-- Navegação --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            {{-- ── Seção Principal ── --}}
            <p class="nav-section-label px-2 mb-2 text-[.68rem] uppercase tracking-[.1em] text-[#4a5068] font-semibold">
                Principal</p>

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">⬡</span>
                <span class="nav-label">Dashboard</span>
            </a>

            {{-- ── ADICIONE NOVAS ROTAS ABAIXO DESTE COMENTÁRIO ── --}}
            {{--
            <a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                <span class="nav-icon">📝</span>
                <span class="nav-label">Posts</span>
            </a>
            --}}

            {{-- ── Seção Configurações ── --}}
            <div class="pt-4">
                <p
                    class="nav-section-label px-2 mb-2 text-[.68rem] uppercase tracking-[.1em] text-[#4a5068] font-semibold">
                    Conta</p>

                <a href="{{ route('profile.edit') }}"
                    class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <span class="nav-icon">◎</span>
                    <span class="nav-label">Meu Perfil</span>
                </a>
            </div>
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-[#272b38] shrink-0">
            <form action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="nav-link w-full text-left text-[#f47f7f] hover:bg-[rgba(244,127,127,.08)] hover:text-[#f47f7f]">
                    <span class="nav-icon">↩</span>
                    <span class="nav-label">Sair</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════════
         ÁREA PRINCIPAL
    ══════════════════════════════════════════════ --}}
    <div id="main-area" class="flex flex-col flex-1 min-h-screen lg:ml-64 transition-all duration-[280ms]">

        {{-- Topbar --}}
        <header
            class="sticky top-0 z-10 flex items-center gap-4 h-16 px-6
                        bg-[#13161d]/80 backdrop-blur border-b border-[#272b38] shrink-0">

            {{-- Hamburguer (mobile) / colapsar (desktop) --}}
            <button onclick="toggleSidebar()"
                class="flex items-center justify-center w-9 h-9 rounded-lg
                           bg-[#1a1e28] border border-[#272b38] text-[#4a5068]
                           hover:text-[#e8c97e] hover:border-[#e8c97e]/30
                           transition-all duration-200 shrink-0"
                aria-label="Menu">
                <span id="sidebar-toggle-icon" class="text-base transition-transform duration-200">☰</span>
            </button>

            {{-- Breadcrumb / título --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-sm font-medium text-[#cdd3e2] truncate">@yield('page-title', 'Dashboard')</h1>
            </div>

            {{-- Avatar rápido --}}
            <a href="{{ route('profile.edit') }}" class="shrink-0">
                @if (Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Perfil"
                        class="w-8 h-8 rounded-full object-cover ring-2 ring-[#272b38] hover:ring-[#e8c97e]/40 transition">
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold text-[#0d0f14]
                                hover:ring-2 hover:ring-[#e8c97e]/40 transition"
                        style="background: linear-gradient(135deg,#e8c97e,#c9a84c)">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </a>
        </header>

        {{-- Conteúdo --}}
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">

            {{-- Flash messages --}}
            @if (session('success'))
                <div id="flash-success"
                    class="flex items-center gap-3 mb-6 px-4 py-3 rounded-xl text-sm
                            bg-[rgba(111,208,164,.1)] border border-[rgba(111,208,164,.25)] text-[#6fd0a4]">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()"
                        class="ml-auto text-[#6fd0a4]/60 hover:text-[#6fd0a4] bg-transparent border-0 cursor-pointer">✕</button>
                </div>
            @endif
            @if (session('error'))
                <div id="flash-error"
                    class="flex items-center gap-3 mb-6 px-4 py-3 rounded-xl text-sm
                            bg-[rgba(244,127,127,.1)] border border-[rgba(244,127,127,.25)] text-[#f47f7f]">
                    <span>✕</span>
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()"
                        class="ml-auto text-[#f47f7f]/60 hover:text-[#f47f7f] bg-transparent border-0 cursor-pointer">✕</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        /* ── Sidebar toggle ── */
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const mainArea = document.getElementById('main-area');
        const isDesktop = () => window.innerWidth >= 1024;

        function toggleSidebar() {
            if (isDesktop()) {
                // Desktop: colapsa para modo ícone
                document.body.classList.toggle('sidebar-collapsed');
                mainArea.classList.toggle('lg:ml-64');
                mainArea.classList.toggle('lg:ml-[68px]');
            } else {
                // Mobile: desliza sidebar
                const open = !sidebar.classList.contains('-translate-x-full');
                open ? closeSidebar() : openSidebar();
            }
        }

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => overlay.classList.remove('opacity-0'));
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 280);
        }

        /* Fecha sidebar no resize para desktop */
        window.addEventListener('resize', () => {
            if (isDesktop()) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden', 'opacity-0');
            }
        });

        /* Auto-remove flash messages */
        setTimeout(() => {
            ['flash-success', 'flash-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.style.opacity = '0';
                    el.style.transition = 'opacity .5s';
                    setTimeout(() => el.remove(), 500);
                }
            });
        }, 4000);
    </script>

    @stack('scripts')
</body>

</html>
