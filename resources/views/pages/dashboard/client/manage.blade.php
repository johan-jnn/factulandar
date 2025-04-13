@extends('layouts.client', [
    'client' => $client,
])

@section('page')
    <form action="{{ route('delete_client', [
        'client' => $client,
    ]) }}" method="post">
        @csrf
        @method('delete')

        <button type="submit">
            Supprimer le client
        </button>
    </form>
@endsection
