@extends('layout.base')
@section('content')
    <h1 class="welcome">Olá, {{ Auth::user()->name }} 👋</h1>
    <p class="subtitle">Bem-vindo ao painel. Sua sessão está ativa e segura.</p>
    <div class="card">
        <h3>Informações da conta</h3>
        <p><strong>E-mail:</strong> {{ Auth::user()->email }}<br>
            <strong>Membro desde:</strong> {{ Auth::user()->created_at->format('d/m/Y') }}
        </p>
    </div>
@endsection
