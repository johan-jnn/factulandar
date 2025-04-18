<section id="invoice-{{ $invoice->number() }}" class="invoice-renderer"
    x-data='{
    lines: {},
    total(taxes = false) {
        return Object.values(this.lines).reduce((v, item) => {
            return (
                v 
                + item.unit_price * item.amount * (1 + (item.tav_ratio ?? {{ $invoice->tav_ratio }}) * taxes / 100)
            )
        }, 0);
    },
    edited: false,
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
            editable.addEventListener("input", () => {
                this.edited = true;
                u();
            });
            u();
        });
    }
}'
    x-modelable='lines'>
    <div class="page">
        <h2>Facture {{ $invoice->number() }}</h2>
        <p>Du {{ $invoice->period_start->format('d/m/Y') }} au {{ $invoice->period_end->format('d/m/Y') }}</p>
        <hr>
        @if ($editable)
            <menu type="toolbar">
                <ul>
                    <li x-show="!edited">
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
                        <button type="button" @click="window.print()">Impression rapide</button>
                    </li>
                    <li>
                        <hr data-vertical="">
                    </li>
                    <li x-show="edited" x-transition>
                        <button @click="location.reload()">Annuler les modifications</button>
                    </li>
                    <li x-show="edited" x-transition>
                        <form
                            action="{{ route('items.updateAll', [
                                'invoice' => $invoice,
                            ]) }}"
                            method="post">
                            @csrf
                            @method('put')
                            <template x-for="(item, id) in lines">
                                <template x-for="(data, key) in item">
                                    <input type="hidden" :name="`items[${id}][${key}]`" :value="data">
                                </template>
                            </template>
                            <button type="submit">Sauvegarder les modifications</button>
                        </form>
                    </li>
                    <li x-show="edited">
                        <hr data-vertical="">
                    </li>
                    <li>
                        <form
                            action="{{ route('items.blank', [
                                'invoice' => $invoice,
                            ]) }}"
                            method="post">
                            @csrf
                            @error('invoice_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <button type="submit">Ajouter une ligne</button>
                        </form>
                    </li>
                    {{-- <li>
                                        <a href="#">
                                            <button type="button"
                                                title="Importer des lignes depuis le calendrier du client">
                                                Importer une ligne
                                            </button>
                                        </a>
                                    </li> --}}
                </ul>
            </menu>
        @endif

        <section class="address">
            <div>
                <h3>
                    <span>Emis par:</span>
                    {{ $invoice->society->name }}
                </h3>
                <p>{{ $invoice->society->address }}</p>
            </div>
            <div>
                <h3>
                    <span>A l'attention de:</span>
                    {{ $invoice->client->name }}
                </h3>
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
                    @foreach ($invoice->items->sortByDesc('created_at') as $line)
                        @continue(!($editable || !!$line->title))
                        <tr x-data="{ line: lines['{{ $line->id }}'] }">
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
                                        {{ number_format($line->unit_price, 2) }}
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
                                <div>
                                    <span contenteditable="{{ $contenteditable }}" x-data='{value:1}'
                                        aria-placeholder="Nombre vendu" x-modelable="value"
                                        x-model="lines['{{ $line->id }}'].amount"
                                        @input='
                                            value = parseFloat($el.innerText);
                                            if(isNaN(value)) value = null;
                                        '>
                                        {{ $line->amount }}
                                    </span>
                                    @if ($editable)
                                        <span x-text="line.unit"></span>
                                    @else
                                        <span>{{ $line->unit }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span contenteditable="{{ $contenteditable }}"
                                        aria-placeholder="{{ $invoice->tav_ratio }}"
                                        x-data='{value:0}'x-modelable="value"
                                        x-model="lines['{{ $line->id }}'].tav_ratio"
                                        @input='
                                            value = parseFloat($el.innerText);
                                            if(isNaN(value)) value = null;
                                        '>
                                        @isset($line->tav_ratio)
                                            {{ $line->tav() }}
                                        @elseif(!$editable)
                                            {{ $invoice->tav_ratio }}
                                        @endisset
                                    </span>
                                    %
                                </div>
                            </td>
                            <td>
                                @if ($editable)
                                    <span x-text='(line.unit_price * line.amount).toFixed(2)'>
                                    </span>
                                @else
                                    <span>{{ number_format($line->price_ht(), 2) }}</span>
                                @endif
                                €
                            </td>
                            <td>
                                @if ($editable)
                                    <span
                                        x-text='(line.unit_price * line.amount * (1 + (line.tav_ratio ?? {{ $invoice->tav_ratio }}) / 100)).toFixed(2)'>
                                    </span>
                                @else
                                    <span>{{ number_format($line->price_ttc(), 2) }}</span>
                                @endif
                                €
                            </td>
                            @if ($editable)
                                <td class="actions">
                                    <form
                                        action="{{ route('items.destroy', [
                                            'item' => $line,
                                            'invoice' => $invoice,
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
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Total</td>
                        <td>
                            @if ($editable)
                                <span x-text="total(false).toFixed(2)"></span>
                            @else
                                {{ number_format($invoice->price_ht(), 2) }}
                            @endif
                            €
                        </td>
                        <td>
                            @if ($editable)
                                <span x-text="total(true).toFixed(2)"></span>
                            @else
                                {{ number_format($invoice->price_ttc(), 2) }}
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
