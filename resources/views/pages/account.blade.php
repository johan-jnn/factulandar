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
        <form action="{{ route('perform_user_edition') }}" method="post" x-data='{_changedpsw: ""}'>
            @csrf
            @method('put')

            <label>
                <span class="required">
                    Nom d'utilisateur
                </span>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
            </label>
            <label>
                <span class="required">
                    Adresse Email
                </span>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            </label>
            <label>
                <span>
                    Changer de mot de passe
                </span>
                <input type="password" name="password" x-model="_changedpsw" id="psw" value="{{ old('password') }}"
                    autocomplete="new-password">
            </label>
            <label x-show="_changedpsw" x-transition x-cloak>
                <span class="required">
                    Confirmez votre nouveau mot de passe
                </span>
                <input type="password" name="password_verification" id="psw_verif" :required="!!_changedpsw">
            </label>

            <div class="actions">
                <button type="submit">Valider les changements</button>
            </div>
        </form>

        <form x-data='{action: ""}' class="danger" :action="action" method="post">
            @csrf
            <button type="submit" @click='action = "{{ route('perform_logout') }}"'>Se déconnecter</button>
            <button type="submit" @click='action = "{{ route('perform_user_delete') }}"'>Supprimer mon compte</button>
        </form>
    </section>
    <section>
      <h2>Mes sociétés</h2>

    </section>
@endsection
