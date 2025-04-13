@extends('layouts.dashboard', [
    'page' => $page ?? $client->name,
])

@section('body')
    <main>
        <h1>{{ $client->name }}</h1>
        <x-tabs :tabs="[
            [
                'label' => 'Informations',
                'url' => route('client', [
                    'client' => $client,
                ]),
                'new_tab' => false,
            ],
            [
                'label' => 'Modifier',
                'url' => route('manage_client', [
                    'client' => $client,
                ]),
            ],
        ]">
            @yield('page')
        </x-tabs>
    </main>
@endsection
