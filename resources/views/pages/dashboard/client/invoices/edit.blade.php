@extends('layouts.dashboard', [
    'page' => 'Nouvelle facture',
    'client' => $invoice->client,
    'clientClickable' => true,
])

@section('body')
    <main>
        <h1>Edition de facture</h1>
        <ul>
            <li>
                Client:
                <a
                    href="{{ route('clients.show', [
                        'client' => $invoice->client,
                    ]) }}">
                    {{ $invoice->client->name }}
                </a>
            </li>
            <li>
                Société:
                <a href="{{ route('societies.index')}}">
                    {{ $invoice->society->name }}
                </a>
            </li>
        </ul>
        <p>Vous pouvez imprimer ou exporter la facture en utilisant l'impression de page.</p>

        <x-invoice-renderer :invoice="$invoice" :editable="true" />
    </main>
@endsection
