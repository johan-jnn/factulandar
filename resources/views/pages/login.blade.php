@extends('layouts.app', [
    'title' => 'Connexion',
])

@section('body')
    <h1>Connectez-vous</h1>
    <form action="{{ route('user.do_login') }}" method="post">
        @csrf

        <label>
            <span class="required">
                Email
            </span>
            <input type="email" name="email" id="email" required>
        </label>
        <label>
            <span class="required">
                Mot de passe
            </span>
            <input type="password" name="password" required>
        </label>

        <div class="actions">
            <button type="submit">
                Connexion
            </button>
            <a href="{{ route('user.register') }}">Pas encore de compte ?</a>
        </div>
    </form>
@endsection
