@extends('layout.base')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- ── Saudação ── --}}
    <div class="mb-8">
        <h2 class="font-display text-3xl text-white mb-1">
            Olá, {{ Auth::user()->name }} 👋
        </h2>
        <p class="text-[#4a5068] text-sm">Bem-vindo ao painel. Sua sessão está ativa e segura.</p>
    </div>

    {{-- ── Cards de métricas ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">

        {{-- Card 1 --}}
        <div
            class="bg-[#1a1e28] border border-[#272b38] rounded-2xl p-6
                    hover:border-[#3a3f52] transition-colors duration-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[.72rem] uppercase tracking-[.08em] font-semibold text-[#4a5068]">Conta</span>
                <span class="w-8 h-8 rounded-lg bg-[rgba(232,201,126,.1)] flex items-center justify-center text-sm">◎</span>
            </div>
            <p class="text-2xl font-semibold text-white mb-1">{{ Auth::user()->name }}</p>
            <p class="text-[.78rem] text-[#4a5068]">{{ Auth::user()->email }}</p>
        </div>

        {{-- Card 2 --}}
        <div
            class="bg-[#1a1e28] border border-[#272b38] rounded-2xl p-6
                    hover:border-[#3a3f52] transition-colors duration-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[.72rem] uppercase tracking-[.08em] font-semibold text-[#4a5068]">Membro desde</span>
                <span
                    class="w-8 h-8 rounded-lg bg-[rgba(111,208,164,.08)] flex items-center justify-center text-sm">📅</span>
            </div>
            <p class="text-2xl font-semibold text-white mb-1">
                {{ Auth::user()->created_at->format('d/m/Y') }}
            </p>
            <p class="text-[.78rem] text-[#4a5068]">
                {{ Auth::user()->created_at->diffForHumans() }}
            </p>
        </div>

        {{-- Card 3 — Perfil incompleto? --}}
        <div
            class="bg-[#1a1e28] border border-[#272b38] rounded-2xl p-6
                    hover:border-[#3a3f52] transition-colors duration-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[.72rem] uppercase tracking-[.08em] font-semibold text-[#4a5068]">Perfil</span>
                <span class="w-8 h-8 rounded-lg bg-[rgba(244,127,127,.08)] flex items-center justify-center text-sm">
                    {{ Auth::user()->avatar ? '✓' : '!' }}
                </span>
            </div>
            <p class="text-2xl font-semibold text-white mb-1">
                {{ Auth::user()->avatar ? 'Completo' : 'Incompleto' }}
            </p>
            <a href="{{ route('profile.edit') }}" class="text-[.78rem] text-[#e8c97e] hover:opacity-75 transition-opacity">
                {{ Auth::user()->avatar ? 'Editar perfil →' : 'Adicionar foto →' }}
            </a>
        </div>
    </div>

    {{-- ── Informações da conta ── --}}
    <div class="bg-[#1a1e28] border border-[#272b38] rounded-2xl p-6">
        <h3 class="text-[.72rem] uppercase tracking-[.08em] font-semibold text-[#4a5068] mb-4">
            Informações da conta
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-[.75rem] text-[#4a5068] mb-0.5">Nome</p>
                <p class="text-sm text-[#cdd3e2] font-medium">{{ Auth::user()->name }}</p>
            </div>
            <div>
                <p class="text-[.75rem] text-[#4a5068] mb-0.5">E-mail</p>
                <p class="text-sm text-[#cdd3e2] font-medium">{{ Auth::user()->email }}</p>
            </div>
            <div>
                <p class="text-[.75rem] text-[#4a5068] mb-0.5">Cadastro</p>
                <p class="text-sm text-[#cdd3e2] font-medium">{{ Auth::user()->created_at->format('d \d\e F \d\e Y') }}</p>
            </div>
            <div>
                <p class="text-[.75rem] text-[#4a5068] mb-0.5">Última atualização</p>
                <p class="text-sm text-[#cdd3e2] font-medium">{{ Auth::user()->updated_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

@endsection
