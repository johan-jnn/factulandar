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
          @foreach ($invoices->orderBy('created_at') as $invoice)
            <tr>
              <td>
                @isset($invoice->name)
                  {{ $invoice->name }} (n° {{ $invoice->number() }})
                @else
                  n° {{ $invoice->number() }}
                @endisset
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
