<section id="invoice-{{ $invoice->number() }}" class="invoice-renderer">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items->all() as $line)
                        <tr>
                            <td>
                                <h4>{{ $line->title }}</h4>
                                @isset($line->description)
                                    <p>{{ $line->description }}</p>
                                @endisset
                            </td>
                            <td>
                                {{ $line->unit_price }}€/{{ $line->unit }}
                            </td>
                            <td>
                                {{ $line->amount }}
                            </td>
                            <td>
                                {{ $line->tav() * 100 }}%
                            </td>
                            <td>
                                {{ $line->price_ttc() }}€
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Total HT</td>
                        <td>{{ $invoice->price_ht() }}€</td>
                    </tr>
                    <tr>
                        <td colspan="4">Total TTC</td>
                        <td>{{ $invoice->price_ttc() }}€</td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </div>
</section>
