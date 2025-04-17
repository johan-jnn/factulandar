@extends('layouts.dashboard', [
    'page' => 'Factures',
    'client' => $invoice->client,
    'clientClickable' => true,
])

@section('body')
    <main>
        <h1>Edition de facture</h1>
        <ul>
            <li style="margin-bottom: 0.25em">
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
                <a href="{{ route('societies.index') }}">
                    {{ $invoice->society->name }}
                </a>
            </li>
        </ul>

        <x-invoice-renderer :invoice="$invoice" :editable="true" />
    </main>
@endsection
