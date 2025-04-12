@extends('layouts.app', [
    'title' => 'Créer son compte',
])

@section('body')
    <h1>Créez votre compte</h1>
    <p>Merci de nous rejoindre !</p>
    <form method="post" action="{{ route('perform_register') }}">
        @csrf

        <label>
            <span class="required">
                Nom d'utilisateur
            </span>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </label>
        <fieldset>
            <legend>Informations de connexion</legend>
            <label>
                <span class="required">
                    Email
                </span>
                <input type="email" value="{{ old('email') }}" name="email" id="email" required>
            </label>
            <label>
                <span class="required">
                    Mot de passe
                </span>
                <input type="password" value="{{ old('password') }}" name="password" required>
            </label>
            <label>
                <span class="required">
                    Confirmez votre mot de passe
                </span>
                <input type="password" name="password_confirmation" required>
            </label>
        </fieldset>

        <div class="actions">
            <button type="submit">
                Créer mon compte
            </button>
            <a href="{{ route('login') }}">Vous avez déjà un compte ?</a>
        </div>
    </form>
@endsection
