<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Acesso</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/login.js'])
</head>

<body class="font-body bg-[#0d0f14] text-[#cdd3e2] min-h-screen flex items-center justify-center overflow-x-hidden">

    {{-- ── Grade decorativa de fundo ── --}}
    <div class="fixed inset-0 z-0 bg-grid pointer-events-none"></div>

    {{-- ── Orbes de luz ── --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-48 -right-48 w-[700px] h-[700px] rounded-full"
            style="background: radial-gradient(circle, rgba(232,201,126,.08) 0%, transparent 70%)"></div>
        <div class="absolute -bottom-24 -left-24 w-[500px] h-[500px] rounded-full"
            style="background: radial-gradient(circle, rgba(111,208,164,.06) 0%, transparent 70%)"></div>
    </div>

    {{-- Card principal --}}
    <div class="relative z-10 w-full max-w-[460px] px-5 py-8">
        <div
            class="bg-[#1a1e28] border border-[#272b38] rounded-[20px] px-10 py-10
                    shadow-[0_32px_64px_rgba(0,0,0,.45),_0_0_0_1px_rgba(255,255,255,.03)]
                    max-sm:px-6 max-sm:py-8">

            {{-- ── Brand ── --}}
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-[10px] flex items-center justify-center
                            text-lg font-bold text-[#0d0f14] shrink-0"
                    style="background: linear-gradient(135deg, #e8c97e, #c9a84c)">
                    ✦
                </div>
                <span class="font-display text-[1.4rem] text-white tracking-tight">
                    {{ config('app.name', 'Aplicação') }}
                </span>
            </div>

            {{-- ── Tabs ── --}}
            <div class="flex bg-[#13161d] border border-[#272b38] rounded-[10px] p-1 mb-8" role="tablist">
                {{-- Botão Login (começa ativo) --}}
                <button id="tab-login"
                    class="tab-btn flex-1 py-2 text-sm font-medium rounded-lg cursor-pointer
                               border-0 transition-all duration-200
                               bg-[#1a1e28] text-[#e8c97e] shadow-[0_2px_8px_rgba(0,0,0,.35)]"
                    role="tab" aria-selected="true" aria-controls="panel-login" onclick="switchTab('login')">
                    Entrar
                </button>
                {{-- Botão Registro (começa inativo) --}}
                <button id="tab-register"
                    class="tab-btn flex-1 py-2 text-sm font-medium rounded-lg cursor-pointer
                               border-0 transition-all duration-200
                               bg-transparent text-[#4a5068]"
                    role="tab" aria-selected="false" aria-controls="panel-register" onclick="switchTab('register')">
                    Criar conta
                </button>
            </div>

            {{-- ── Alerta global ── --}}
            <div id="global-alert" class="hidden rounded-[10px] px-4 py-3 text-sm mb-5" role="alert"></div>


            {{-- ════════════════════════════════
                 PAINEL — LOGIN
            ════════════════════════════════ --}}
            <div class="form-panel active" id="panel-login" role="tabpanel" aria-labelledby="tab-login">
                <form id="form-login" novalidate>
                    @csrf

                    {{-- E-mail --}}
                    <div class="mb-5">
                        <label
                            class="block text-[.74rem] font-medium text-[#4a5068]
                                      uppercase tracking-[.06em] mb-2"
                            for="login-email">E-mail</label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2
                                         text-[#4a5068] pointer-events-none select-none text-sm">✉</span>
                            <input id="login-email" name="email" type="email" placeholder="seu@email.com"
                                autocomplete="email" required
                                class="input-field w-full pl-10 pr-4 py-3
                                          bg-[#13161d] border border-[#272b38] rounded-[10px]
                                          text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                          transition-all duration-200">
                        </div>
                        <p id="err-login-email" class="field-error hidden text-[#f47f7f] text-[.73rem] mt-1.5"></p>
                    </div>

                    {{-- Senha --}}
                    <div class="mb-6">
                        <label
                            class="block text-[.74rem] font-medium text-[#4a5068]
                                      uppercase tracking-[.06em] mb-2"
                            for="login-password">Senha</label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2
                                         text-[#4a5068] pointer-events-none select-none text-sm">🔒</span>
                            <input id="login-password" name="password" type="password" placeholder="••••••••"
                                autocomplete="current-password" required
                                class="input-field w-full pl-10 pr-11 py-3
                                          bg-[#13161d] border border-[#272b38] rounded-[10px]
                                          text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                          transition-all duration-200">
                            <button type="button" onclick="togglePass('login-password', this)"
                                aria-label="Mostrar senha"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2
                                           text-[#4a5068] hover:text-[#cdd3e2]
                                           bg-transparent border-0 cursor-pointer p-0 text-sm
                                           transition-colors duration-200">👁</button>
                        </div>
                        <p id="err-login-password" class="field-error hidden text-[#f47f7f] text-[.73rem] mt-1.5"></p>
                    </div>

                    {{-- Botão --}}
                    <button type="submit" id="btn-login"
                        class="w-full py-3.5 rounded-[10px] font-semibold text-[#0d0f14] text-sm
                                   tracking-wide border-0 cursor-pointer
                                   hover:-translate-y-px hover:opacity-90
                                   active:translate-y-0
                                   disabled:opacity-55 disabled:cursor-not-allowed disabled:transform-none
                                   transition-all duration-200"
                        style="background: linear-gradient(135deg, #e8c97e, #c9a84c)">
                        Entrar
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative flex items-center my-6">
                    <div class="flex-1 h-px bg-[#272b38]"></div>
                    <span class="px-3 text-[.73rem] text-[#4a5068]">ou</span>
                    <div class="flex-1 h-px bg-[#272b38]"></div>
                </div>

                <p class="text-center text-[.84rem] text-[#4a5068]">
                    Não tem conta?
                    <a href="#" onclick="switchTab('register'); return false;"
                        class="text-[#e8c97e] font-medium no-underline hover:opacity-75 transition-opacity">
                        Criar agora
                    </a>
                </p>
            </div>


            {{-- PAINEL — REGISTRO --}}
            <div class="form-panel" id="panel-register" role="tabpanel" aria-labelledby="tab-register">
                <form id="form-register" novalidate>
                    @csrf

                    {{-- Nome --}}
                    <div class="mb-5">
                        <label
                            class="block text-[.74rem] font-medium text-[#4a5068]
                                      uppercase tracking-[.06em] mb-2"
                            for="reg-name">Nome completo</label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2
                                         text-[#4a5068] pointer-events-none select-none text-sm">◉</span>
                            <input id="reg-name" name="name" type="text" placeholder="João Silva"
                                autocomplete="name" required
                                class="input-field w-full pl-10 pr-4 py-3
                                          bg-[#13161d] border border-[#272b38] rounded-[10px]
                                          text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                          transition-all duration-200">
                        </div>
                        <p id="err-reg-name" class="field-error hidden text-[#f47f7f] text-[.73rem] mt-1.5"></p>
                    </div>

                    {{-- E-mail --}}
                    <div class="mb-5">
                        <label
                            class="block text-[.74rem] font-medium text-[#4a5068]
                                      uppercase tracking-[.06em] mb-2"
                            for="reg-email">E-mail</label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2
                                         text-[#4a5068] pointer-events-none select-none text-sm">✉</span>
                            <input id="reg-email" name="email" type="email" placeholder="seu@email.com"
                                autocomplete="email" required
                                class="input-field w-full pl-10 pr-4 py-3
                                          bg-[#13161d] border border-[#272b38] rounded-[10px]
                                          text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                          transition-all duration-200">
                        </div>
                        <p id="err-reg-email" class="field-error hidden text-[#f47f7f] text-[.73rem] mt-1.5"></p>
                    </div>

                    {{-- Senha --}}
                    <div class="mb-5">
                        <label
                            class="block text-[.74rem] font-medium text-[#4a5068]
                                      uppercase tracking-[.06em] mb-2"
                            for="reg-password">Senha</label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2
                                         text-[#4a5068] pointer-events-none select-none text-sm">🔒</span>
                            <input id="reg-password" name="password" type="password" placeholder="••••••••"
                                autocomplete="new-password" required oninput="updateStrength(this.value)"
                                class="input-field w-full pl-10 pr-11 py-3
                                          bg-[#13161d] border border-[#272b38] rounded-[10px]
                                          text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                          transition-all duration-200">
                            <button type="button" onclick="togglePass('reg-password', this)"
                                aria-label="Mostrar senha"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2
                                           text-[#4a5068] hover:text-[#cdd3e2]
                                           bg-transparent border-0 cursor-pointer p-0 text-sm
                                           transition-colors duration-200">👁</button>
                        </div>

                        {{-- Barra de força (4 segmentos) --}}
                        <div class="flex gap-1 mt-2 h-[3px]" aria-hidden="true">
                            <div id="seg1" class="flex-1 rounded-sm bg-[#272b38] transition-colors duration-300">
                            </div>
                            <div id="seg2" class="flex-1 rounded-sm bg-[#272b38] transition-colors duration-300">
                            </div>
                            <div id="seg3" class="flex-1 rounded-sm bg-[#272b38] transition-colors duration-300">
                            </div>
                            <div id="seg4" class="flex-1 rounded-sm bg-[#272b38] transition-colors duration-300">
                            </div>
                        </div>

                        {{-- Critérios de política --}}
                        <ul class="grid grid-cols-2 gap-y-1.5 gap-x-2 mt-2.5 p-0 m-0 list-none"
                            aria-label="Critérios de senha">
                            <li class="policy-item flex items-center gap-1.5 text-[.72rem] text-[#4a5068] transition-colors duration-200"
                                id="p-len">
                                <span
                                    class="policy-dot inline-block w-1.5 h-1.5 rounded-full bg-[#272b38] shrink-0 transition-colors duration-200"></span>
                                8+ caracteres
                            </li>
                            <li class="policy-item flex items-center gap-1.5 text-[.72rem] text-[#4a5068] transition-colors duration-200"
                                id="p-upper">
                                <span
                                    class="policy-dot inline-block w-1.5 h-1.5 rounded-full bg-[#272b38] shrink-0 transition-colors duration-200"></span>
                                Maiúscula
                            </li>
                            <li class="policy-item flex items-center gap-1.5 text-[.72rem] text-[#4a5068] transition-colors duration-200"
                                id="p-num">
                                <span
                                    class="policy-dot inline-block w-1.5 h-1.5 rounded-full bg-[#272b38] shrink-0 transition-colors duration-200"></span>
                                Número
                            </li>
                            <li class="policy-item flex items-center gap-1.5 text-[.72rem] text-[#4a5068] transition-colors duration-200"
                                id="p-special">
                                <span
                                    class="policy-dot inline-block w-1.5 h-1.5 rounded-full bg-[#272b38] shrink-0 transition-colors duration-200"></span>
                                Especial (!@#…)
                            </li>
                        </ul>

                        <p id="err-reg-password" class="field-error hidden text-[#f47f7f] text-[.73rem] mt-1.5"></p>
                    </div>

                    {{-- Confirmar senha --}}
                    <div class="mb-6">
                        <label
                            class="block text-[.74rem] font-medium text-[#4a5068]
                                      uppercase tracking-[.06em] mb-2"
                            for="reg-confirm">Confirmar senha</label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2
                                         text-[#4a5068] pointer-events-none select-none text-sm">🔑</span>
                            <input id="reg-confirm" name="password_confirmation" type="password"
                                placeholder="••••••••" autocomplete="new-password" required
                                class="input-field w-full pl-10 pr-11 py-3
                                          bg-[#13161d] border border-[#272b38] rounded-[10px]
                                          text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                          transition-all duration-200">
                            <button type="button" onclick="togglePass('reg-confirm', this)"
                                aria-label="Mostrar senha"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2
                                           text-[#4a5068] hover:text-[#cdd3e-2]
                                           bg-transparent border-0 cursor-pointer p-0 text-sm
                                           transition-colors duration-200">👁</button>
                        </div>
                        <p id="err-reg-confirm" class="field-error hidden text-[#f47f7f] text-[.73rem] mt-1.5"></p>
                    </div>

                    {{-- Botão --}}
                    <button type="submit" id="btn-register"
                        class="w-full py-3.5 rounded-[10px] font-semibold text-[#0d0f14] text-sm
                                   tracking-wide border-0 cursor-pointer
                                   hover:-translate-y-px hover:opacity-90
                                   active:translate-y-0
                                   disabled:opacity-55 disabled:cursor-not-allowed disabled:transform-none
                                   transition-all duration-200"
                        style="background: linear-gradient(135deg, #e8c97e, #c9a84c)">
                        Criar conta
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative flex items-center my-6">
                    <div class="flex-1 h-px bg-[#272b38]"></div>
                    <span class="px-3 text-[.73rem] text-[#4a5068]">ou</span>
                    <div class="flex-1 h-px bg-[#272b38]"></div>
                </div>

                <p class="text-center text-[.84rem] text-[#4a5068]">
                    Já tem conta?
                    <a href="#" onclick="switchTab('login'); return false;"
                        class="text-[#e8c97e] font-medium no-underline hover:opacity-75 transition-opacity">
                        Entrar
                    </a>
                </p>
            </div>

        </div>{{-- /card --}}
    </div>{{-- /wrapper --}}
</body>

</html>
