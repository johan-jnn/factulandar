@extends('layouts.app', [
    'title' => 'Mon compte',
])

@php
    /**
     * @var App\Models\User
     */
    $user = Auth::user();
@endphp

@section('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('body')
    <h1>Gérer ici votre compte, {{ $user->name }}</h1>

    <section>
        <h2>Informations de compte</h2>
        <form action="{{ route('perform_user_edition') }}" method="post">
            @csrf
            @method('put')

            <label>
                <span class="required">
                    Adresse Email
                </span>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
            </label>
            <label>
                Changer de mot de passe
                <input type="password" name="password" id="psw" x-model="test" value="{{ old('password') }}"
                    autocomplete="new-password">
            </label>
            <label x-show="">
                Confirmez votre nouveau mot de passe
                <input type="password" name="password_verification" id="psw_verif"
                    value="{{ old('password_verification') }}">
            </label>

            <div class="actions">
              <button type="submit">Valider les changements</button>
            </div>
        </form>
    </section>
@endsection
