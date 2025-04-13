@extends('layouts.client', [
    'client' => $client,
])

@section('page')
    <h2>Statistiques de {{ $client->name }}</h2>
    <ul>
        <li>Factures: 0</li>
        <li>Evenements du calendrier: 0</li>
    </ul>
@endsection
