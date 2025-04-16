@extends('layouts.client', [
    'client' => $client,
])

@section('page')
    <h2>Factures de {{ $client->name }}</h2>
    <table>
        <thead>
            <tr>
                <th>Factures</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices->orderBy('created_at')->get() as $invoice)
                <tr>
                    <td>
                        @isset($invoice->name)
                            {{ $invoice->name }} (n° {{ $invoice->number() }})
                        @else
                            n° {{ $invoice->number() }}
                        @endisset
                    </td>
                    <td>
                        {{ $invoice->created_at->format('d/m/Y') }}
                    </td>
                    <td class="invoice-actions">
                        @if (!$invoice->validated)
                            <a
                                href="{{ route('invoices.edit', [
                                    'client' => $client,
                                    'invoice' => $invoice,
                                ]) }}">
                                <button type="button" title="Modifier la facture">
                                    📝
                                </button>
                            </a>
                        @endif
                        <a
                            href="{{ route('invoices.show', [
                                'client' => $client,
                                'invoice' => $invoice,
                            ]) }}">
                            <button type="button" title="Exporter la facture">
                                📤️
                            </button>
                        </a>
                        <form
                            action="{{ route('invoices.destroy', [
                                'client' => $client,
                                'invoice' => $invoice,
                            ]) }}">
                            @csrf
                            @method('delete')

                            <button style="width: 100%; margin-top: 0.5em;" type="submit" title="Supprimer la facture">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <a
                        href="{{ route('invoices.create', [
                            'client' => $client,
                        ]) }}">
                        <button type="button" style="width: 100%" title="Créer une nouvelle facture">📝</button>
                    </a>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
