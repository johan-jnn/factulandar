<section id="invoice-{{ $invoice->number() }}" class="invoice-renderer"
    x-data='{
    lines: {},
    total(taxes = false) {
        return Object.values(this.lines).reduce((v, item) => {
            return (
                v 
                + item.unit_price * item.amount * (1 + (item.tav_ratio ?? {{ $invoice->tav_ratio }}) * taxes)
            )
        }, 0);
    },
    init() {
        ({{ $invoice->items->toJson() }}).forEach((item) => {
            const key = item.id.toString();
            delete item.id;
            delete item.created_at;
            delete item.updated_at;
            delete item.invoice_id;
            this.lines[key] = item;
        });


        $el.querySelectorAll("[contenteditable=true]").forEach((editable) => {
            const u = () => {
                editable.classList.toggle("force-empty", !editable.innerText.trim());
            }
            editable.addEventListener("input", u);
            u();
        });
    }
}'
    x-modelable='lines'>
    <div class="page">
        <h2>Facture {{ $invoice->number() }}</h2>
        <p>Du {{ $invoice->period_start->format('d/m/Y') }} au {{ $invoice->period_end->format('d/m/Y') }}</p>
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
                        <th>Prix final (HT)</th>
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
                                <h4 contenteditable="{{ $contenteditable }}" x-data='{value:""}'
                                    aria-placeholder="Titre de l'élément" x-modelable="value"
                                    x-model="lines['{{ $line->id }}'].title" @input='value=$el.innerText.trim()'>
                                    {{ $line->title }}
                                </h4>
                                <p contenteditable="{{ $contenteditable }}" x-data='{value:""}'
                                    aria-placeholder="Description de l'élément (optionelle)" x-modelable="value"
                                    x-model="lines['{{ $line->id }}'].description"
                                    @input='value=$el.innerText.trim()'>
                                    {{ $line->description }}</p>
                            </td>
                            <td>
                                <div>
                                    <span contenteditable="{{ $contenteditable }}" x-data='{value:0}'
                                        aria-placeholder="Prix unitaire" x-modelable="value"
                                        x-model="lines['{{ $line->id }}'].unit_price"
                                        @input='
                                            value = parseFloat($el.innerText);
                                            if(isNaN(value)) value = null;
                                        '>
                                        {{ $line->unit_price }}
                                    </span>
                                    €/
                                    <span contenteditable="{{ $contenteditable }}" x-data='{value:""}'
                                        aria-placeholder="Unité" x-modelable="value"
                                        x-model="lines['{{ $line->id }}'].unit"
                                        @input='value=$el.innerText.slice(0, 5)'>
                                        {{ $line->unit }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span contenteditable="{{ $contenteditable }}" x-data='{value:1}'
                                    aria-placeholder="Nombre vendu" x-modelable="value"
                                    x-model="lines['{{ $line->id }}'].amount"
                                    @input='
                                        value = parseFloat($el.innerText);
                                        if(isNaN(value)) value = null;
                                    '>
                                    {{ $line->amount }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <span contenteditable="{{ $contenteditable }}"
                                        aria-placeholder="{{ $invoice->tav_ratio * 100 }}"
                                        x-data='{value:0}'x-modelable="value"
                                        x-model="lines['{{ $line->id }}'].tav_ratio"
                                        @input='
                                            value = parseFloat($el.innerText)/100;
                                            if(isNaN(value)) value = null;
                                        '>
                                        @isset($line->tav_ratio)
                                            {{ $line->tav() * 100 }}
                                        @endisset
                                    </span>
                                    %
                                </div>
                            </td>
                            <td x-data="{ line: lines['{{ $line->id }}'] }">
                                @if ($editable)
                                    <span x-text='(line.unit_price * line.amount).toFixed(2)'>
                                    </span>
                                @else
                                    <span>{{ $line->price_ht() }}</span>
                                @endif
                                €
                            </td>
                            <td x-data="{ line: lines['{{ $line->id }}'] }">
                                @if ($editable)
                                    <span
                                        x-text='(line.unit_price * line.amount * (1 + (line.tav_ratio ?? {{ $invoice->tav_ratio }}))).toFixed(2)'>
                                    </span>
                                @else
                                    <span>{{ $line->price_ttc() }}</span>
                                @endif
                                €
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
                                        <a
                                            href="{{ route('invoices.show', [
                                                'client' => $invoice->client,
                                                'invoice' => $invoice,
                                            ]) }}">
                                            <button type="button">
                                                Afficher la facture finale
                                            </button>
                                        </a>
                                    </li>
                                    <li>
                                        <hr data-vertical="">
                                    </li>
                                    <li>
                                        <form action="{{ route('items.updateAll') }}" method="post">
                                            @csrf
                                            @method('put')
                                            <template x-for="(item, id) in lines">
                                                <template x-for="(data, key) in item">
                                                    <input type="hidden" :name="`items[${id}][${key}]`"
                                                        :value="data">
                                                </template>
                                            </template>
                                            <button type="submit">Sauvegarder les modifications</button>
                                        </form>
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
                        <td colspan="4">Total</td>
                        <td>
                            @if ($editable)
                                <span x-text="total(false).toFixed(2)"></span>
                            @else
                                {{ round($invoice->price_ht(), 2) }}
                            @endif
                            €
                        </td>
                        <td>
                            @if ($editable)
                                <span x-text="total(true).toFixed(2)"></span>
                            @else
                                {{ round($invoice->price_ttc(), 2) }}
                            @endif
                            €
                        </td>
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
