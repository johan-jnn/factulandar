@extends('layouts.app', [
    'title' => $invoice->name ?? "Facture n°{$invoice->number()}",
    'indexURL' => route('invoices.index', [
        'client' => $invoice->client,
        'invoice' => $invoice,
    ]),
])

@section('body')
    <main class="invoice-viewer">
        <header>
            <button type="button">📥️ Télécharger la facture</button>
            @if (!$invoice->validated)
                <form
                    action="{{ route('invoices.update', [
                        'client' => $invoice->client,
                        'invoice' => $invoice,
                    ]) }}"
                    method="post">
                    @csrf
                    @method('put')

                    <input type="hidden" name="validated" value="1">
                    <button type="submit" title="En validant la facture, vous ne pourrez plus la modifier">
                        🔒️ Valider la facture
                    </button>
                </form>
            @endif
        </header>
        <x-invoice-renderer :editable="false" :invoice="$invoice" />
    </main>
@endsection
