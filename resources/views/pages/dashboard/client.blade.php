@extends('layouts.dashboard', [
    'page' => "{$client->name}",
])

@section('body')
    <h1>{{ $client->name }}</h1>
    <form action="{{ route('delete_client', [
        'client' => $client,
    ]) }}" method="post">
        @csrf
        @method('delete')

        <button type="submit">
            Supprimer le client
        </button>
    </form>

    <section>

    </section>
@endsection
