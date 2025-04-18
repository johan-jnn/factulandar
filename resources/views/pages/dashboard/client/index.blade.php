@extends('layouts.client', [
    'client' => $client,
])

@section('page')
    <h2>Statistiques de {{ $client->name }}</h2>
    <ul class="stats">
        <li>Factures: {{ $client->invoices->count() }}</li>
        @if ($calendar)
            <li>Evenements du calendrier: {{ count($calendar['events']) }}</li>
        @endif
    </ul>
@endsection
