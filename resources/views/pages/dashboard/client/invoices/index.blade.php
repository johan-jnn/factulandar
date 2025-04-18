@extends('layouts.client', [
    'client' => $client,
])

@section('page')
    <h2>Factures de {{ $client->name }}</h2>
    <table>
        <thead>
            <tr>
                <th>Factures</th>
                <th>Société</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices->orderBy('created_at')->get() as $invoice)
                <tr>
                    <td>
                        {{ $invoice->number() }}
                    </td>
                    <td>
                        {{ $invoice->society->name }}
                    </td>
                    <td>
                        {{ $invoice->created_at->format('d/m/Y H\hm') }}
                    </td>
                    <td class="invoice-actions">
                        <a
                            href="{{ route('invoices.show', [
                                'client' => $client,
                                'invoice' => $invoice,
                            ]) }}">
                            <button type="button" title="Voir la facture">
                                📄
                            </button>
                        </a>
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
                        <form
                            action="{{ route('invoices.destroy', [
                                'client' => $client,
                                'invoice' => $invoice,
                            ]) }}"
                            method="post">
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
                <td colspan="4">
                    <a href="{{ route('invoices.create', [
                        'client' => $client,
                    ]) }}"
                        style="width: 100%;">
                        <button type="button" style="width: 100%" title="Créer une nouvelle facture">📝 Nouvelle facture</button>
                    </a>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection
