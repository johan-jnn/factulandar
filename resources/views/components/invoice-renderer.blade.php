<section id="invoice-{{ $invoice->number() }}" class="invoice-renderer"
    x-data='{
    init() {
        $el.querySelectorAll("[contenteditable=true]").forEach((editable) => {
            const u = () => {
                editable.classList.toggle("force-empty", !editable.innerText.trim());
            }
            editable.addEventListener("input", u);
            u();
        })
    }
}'>
    <div class="page">
        <h2>Facture n°{{ $invoice->number() }}</h2>
        <hr>
        <section class="address">
            <div>
                <h3>{{ $invoice->society->name }}</h3>
                <p>{{ $invoice->society->address }}</p>
            </div>
            <div>
                <h3>{{ $invoice->client->name }}</h3>
                <p>{{ $invoice->client->address }}</p>
            </div>
        </section>

        <section>
            <table>
                <thead>
                    <tr>
                        <th>Prestation</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>TVA</th>
                        <th>Prix final (TTC)</th>
                        @if ($editable)
                            <th class="actions">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items->all() as $line)
                        <tr>
                            <td>
                                <h4 contenteditable="{{ $contenteditable }}"
                                    aria-placeholder="Titre de l'élément (requis)">{{ $line->title }}</h4>
                                <p contenteditable="{{ $contenteditable }}" aria-placeholder="Description de l'élément">
                                    {{ $line->description }}</p>
                            </td>
                            <td>
                                <div>
                                    <span contenteditable="{{ $contenteditable }}">
                                        {{ $line->unit_price }}
                                    </span>
                                    €/
                                    <span contenteditable="{{ $contenteditable }}">
                                        {{ $line->unit }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span contenteditable="{{ $contenteditable }}">
                                    {{ $line->amount }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <span contenteditable="{{ $contenteditable }}">
                                        {{ $line->tav() * 100 }}
                                    </span>
                                    %
                                </div>
                            </td>
                            <td>
                                {{ $line->price_ttc() }}€
                            </td>
                            @if ($editable)
                                <td class="actions">
                                    <form
                                        action="{{ route('items.destroy', [
                                            'item' => $line,
                                        ]) }}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit">🗑️</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    @if ($editable)
                        <tr class="adder">
                            <td colspan="6">
                                <ul>
                                    <li>
                                        <form
                                            action="{{ route('items.update', [
                                                'item' => $line,
                                            ]) }}"
                                            method="post">
                                            <button type="submit">Sauvegarder les modifications</button>
                                        </form>
                                    </li>
                                    <li>
                                        <hr data-vertical="">
                                    </li>
                                    <li>
                                        <form action="{{ route('items.blank') }}" method="post">
                                            @csrf
                                            @error('invoice_id')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                            <button type="submit">Ajouter une ligne</button>
                                        </form>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <button type="button"
                                                title="Importer des lignes depuis le calendrier du client">
                                                Importer une ligne
                                            </button>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Total HT</td>
                        <td>{{ $invoice->price_ht() }}€</td>
                        @if ($editable)
                            <td class="actions"></td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="4">Total TTC</td>
                        <td>{{ $invoice->price_ttc() }}€</td>
                        @if ($editable)
                            <td class="actions"></td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </section>
        <footer>
            <p>{{ $invoice->society->paiement_terms }}</p>
        </footer>
    </div>
</section>
