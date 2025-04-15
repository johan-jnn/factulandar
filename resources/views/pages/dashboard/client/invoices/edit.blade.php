@extends('layouts.dashboard', [
    'page' => 'Nouvelle facture',
    'client' => $invoice->client,
    'clientClickable' => true,
])

@section('body')
    <main>
        <h1>Facture {{ $invoice->number() }}</h1>
        <ul>
            <li>Client: {{ $invoice->client->name }}</li>
            <li>Société: {{ $invoice->society->name }}</li>
        </ul>
        <p>Vous pouvez imprimer ou exporter la facture en utilisant l'impression de page.</p>

        <x-invoice-renderer :invoice="$invoice" :editable="true" />
    </main>
@endsection
