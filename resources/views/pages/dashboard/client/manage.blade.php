@extends('layouts.client', [
    'client' => $client,
])

@section('page')
    <h2>Modification du client</h2>
    <form action="{{ route('update_client', [
        'client' => $client,
    ]) }}" method="post">
        @csrf
        @method('put')
        <label>
            <span class="required">Nom du client</span>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
            <input type="text" name="new_name" value="{{ old('name', $client->name) }}" required>
        </label>
        <label>
            <span class="required">Addresse postale</span>
            @error('address')
                <span class="error" x-show="!!olds">{{ $message }}</span>
            @enderror
            <textarea name="address" required>{{ old('address', $client->address) }}</textarea>
        </label>

        <div class="actions">
            <button type="submit">Sauvegarder les modifications</button>
        </div>
    </form>

    <form action="{{ route('delete_client', [
        'client' => $client,
    ]) }}" method="post">
        @csrf
        @method('delete')
        <div class="danger">
            <button type="submit">
                Supprimer le client
            </button>
        </div>
    </form>
@endsection
