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
    <section class="skip-pad"
        x-data='{
        show_form: "{{ old('_form') }}",
        society_edition: {{ Society::where('id', old('_old_society_id'))->first()?->toJson() ?? 'null' }},
    }'
        @keyup.escape="show_form = ''">

        <h1>Vos sociétés</h1>

        <dialog :open="show_form === 'new_society'">
            <form action="{{ route('societies.store') }}" method="post">
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
                    @error('invoices_no_format')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <span>Format des numéros de factures</span>
                    <input type="text" name="invoices_no_format" value="{{ old('invoices_no_format') }}">
                </label>
                <label>
                    <span class="required">Addresse postale</span>
                    @error('address')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <textarea name="address" required>{{ old('address') }}</textarea>
                </label>
                <label>
                    <span class="required">Termes de paiement</span>
                    @error('paiement_terms')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <textarea name="paiement_terms" required>{{ old('paiement_terms') }}</textarea>
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
            address: `{{ old('new_address') }}`,
            paiement_terms: `{{ old('new_paiement_terms') }}`,
            invoices_no_format: `{{ old('invoices_no_format') }}`
          }
        }'>
            @php
                //! Code bellow includes Alpine x-bind syntax
                $action = '"' . route('societies.update', ['society' => 0]) . '" + society_edition?.id';
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
                    @error('new_invoices_no_format')
                        <span class="error"x-show="!!olds">{{ $message }}</span>
                    @enderror
                    <span>Format des numéros de factures</span>
                    <input type="text" name="new_invoices_no_format" :value='olds?.invoices_no_format || society_edition?.invoices_no_format'>
                </label>
                <label>
                    <span class="required">Addresse postale</span>
                    @error('new_address')
                        <span class="error" x-show="!!olds">{{ $message }}</span>
                    @enderror
                    <textarea name="new_address" x-text='olds?.address || society_edition?.address' required></textarea>
                </label>
                <label>
                    <span class="required">Termes de paiement</span>
                    @error('new_paiement_terms')
                        <span class="error" x-show="!!olds">{{ $message }}</span>
                    @enderror
                    <textarea name="new_paiement_terms" x-text='olds?.paiement_terms || society_edition?.paiement_terms' required></textarea>
                </label>

                <div class="actions">
                    <button type="submit">Sauvegarder les modifications</button>
                    <a href="#" @click="show_form = '';olds = null">Annuler</a>
                </div>
            </form>
        </dialog>

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
                                action="{{ route('societies.destroy', [
                                    'society' => $society,
                                ]) }}"
                                x-ref="danger_form_{{ $society->id }}" method="post">
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
                                    $refs.danger_form_{{ $society->id }}.submit();
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
@endsection
