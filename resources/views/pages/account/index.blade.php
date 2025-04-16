@extends('layouts.account', [
    'title' => 'Mon compte',
])
@use(App\Models\Society)

@php
    /**
     * @var App\Models\User
     */
    $user = Auth::user();
@endphp

@section('page')
    <section class="skip-pad">
        <h1>Informations de compte</h1>
        <form action="{{ route('user.update') }}" method="post" x-data='{_changedpsw: ""}'>
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

        <form x-data='{action: ""}' x-ref="danger_form" class="danger" :action="action" method="post">
            @csrf
            @method('delete')
            <h3>Zone de danger</h3>
            <a href="{{ route('user.do_logout') }}">
              <button type="button"'>Se déconnecter</button>
            </a>
            <button type="submit"
                @click.prevent='
            if(!confirm("Es-tu certain de vouloir continuer ?\nTu perdras toutes les informations de ton compte (sociétés, clients, factures, ...)")) return;
            action = "{{ route('user.destroy') }}";
            $refs.danger_form.submit();
          '>
                Supprimer mon compte
            </button>
        </form>
    </section>
@endsection
