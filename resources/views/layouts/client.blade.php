@extends('layouts.dashboard', [
    'page' => $page ?? $client->name,
])

@section('body')
    <main>
        <h1>{{ $client->name }}</h1>
        <x-tabs :tabs="[
            [
                'label' => 'Informations',
                'url' => route('clients.show', [
                    'client' => $client,
                ]),
                'new_tab' => false,
            ],
            [
                'label' => 'Modifier',
                'url' => route('clients.edit', [
                    'client' => $client,
                ]),
            ],
            [
                'label' => 'Factures',
                'url' => route('invoices.index', [
                    'client' => $client,
                ]),
            ],
        ]">
            @yield('page')
        </x-tabs>
    </main>
@endsection
