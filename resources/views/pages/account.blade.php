@extends('layouts.app', [
    'title' => 'Mon compte',
])
@use(App\Models\Society)

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
    <main
        x-data='{
        show_form: "{{ old('_form') }}",
        society_edition: {{ Society::where('id', old('_old_society_id'))->first()?->toJson() ?? 'null' }},
    }'
        @keyup.escape="show_form = ''">
        <h1>Gérer vos informations ici, {{ $user->name }}</h1>

        <dialog :open="show_form === 'new_society'">
            <form action="{{ route('perform_society_creation') }}" method="post">
                @csrf
                <input type="hidden" name="_form" value="new_society">
                <h3>Création d'une nouvelle société</h3>
                <label>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <span class="required">Nom de la société</span>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>
                    <span class="required">Addresse postale</span>
                    @error('address')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <textarea name="address" required>{{ old('address') }}</textarea>
                </label>

                <div class="actions">
                    <button type="submit">Créer</button>
                    <a href="#" @click="show_form = ''">Annuler</a>
                </div>
            </form>
        </dialog>

        <dialog :open="show_form === 'edit_society' && society_edition !== null"
            x-data='{
          olds: {
            name: "{{ old('new_name') }}",
            address: "{{ old('new_address') }}"
          }
        }'>
            @php
                //! Code bellow includes Alpine x-bind syntax
                $action = '"' . route('perform_society_edition', ['society' => 0]) . '" + society_edition?.id';
            @endphp
            <form :action="{{ $action }}" method="post">
                @csrf
                @method('put')
                <input type="hidden" name="_form" value="edit_society">
                <input type="hidden" name="_old_society_id" :value="society_edition?.id">

                <h3>Modification de <span x-text="society_edition?.name"></span></h3>
                <label>
                    @error('new_name')
                        <span class="error"x-show="!!olds">{{ $message }}</span>
                    @enderror
                    <span class="required">Nom de la société</span>
                    <input type="text" name="new_name" :value='olds?.name || society_edition?.name' required>
                </label>
                <label>
                    <span class="required">Addresse postale</span>
                    @error('new_address')
                        <span class="error" x-show="!!olds">{{ $message }}</span>
                    @enderror
                    <textarea name="new_address" x-text='olds?.address || society_edition?.address' required></textarea>
                </label>

                <div class="actions">
                    <button type="submit">Sauvegarder les modifications</button>
                    <a href="#" @click="show_form = '';olds = null">Annuler</a>
                </div>
            </form>
        </dialog>

        <section>
            <h2>Mes sociétés</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sociétés</th>
                        <th>Edition</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->societies()->orderBy('updated_at', 'desc')->get() as $society)
                        <tr>
                            <td>{{ $society->name }}</td>
                            <td>
                                <form
                                    action="{{ route('perform_society_deletion', [
                                        'society' => $society,
                                    ]) }}"
                                    method="post">
                                    @csrf
                                    @method('delete')

                                    <button type="button" title="Modifier la société"
                                        @click="
                                      society_edition = {{ $society->toJson() }};
                                      show_form= 'edit_society';
                                    ">✍️</button>

                                    <button type="submit" title="Supprimer la société"
                                        @click.prevent='
                                    if(!confirm("Es-tu certain de vouloir continuer ?\nTu perdras toutes les factures liées à cette société")) return;
                                    action = "{{ route('perform_society_deletion', [
                                        'society' => $society,
                                    ]) }}";
                                    $refs.danger_form.submit();
                                    '>🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <button type="button" style="width: 100%;" title="Créer une nouvelle société"
                                @click="show_form = 'new_society'">➕</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </section>

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
                    <input type="password" name="password" x-model="_changedpsw" id="psw"
                        value="{{ old('password') }}" autocomplete="new-password">
                </label>
                <label x-show="_changedpsw" x-transition x-cloak>
                    <span class="required">
                        Confirmez votre nouveau mot de passe
                    </span>
                    <input type="password" name="password_verification" id="psw_verif" :required="!!_changedpsw">
                </label>

                <div class="actions">
                    <button type="submit">Valider les changements</button>
                    <button type="submit" @click='action = "{{ route('perform_logout') }}"'>Se déconnecter</button>
                </div>
            </form>

            <form x-data='{action: ""}' x-ref="danger_form" class="danger" :action="action" method="post">
                @csrf
                <h3>Zone de danger</h3>
                <button type="submit"
                    @click.prevent='
            if(!confirm("Es-tu certain de vouloir continuer ?\nTu perdras toutes les informations de ton compte (sociétés, clients, factures, ...)")) return;
            action = "{{ route('perform_user_delete') }}";
            $refs.danger_form.submit();
          '>
                    Supprimer mon compte
                </button>
            </form>
        </section>
    </main>
@endsection
