@extends('layouts.client', [
    'client' => $client,
])

@section('page')
    <h2>Modification du client</h2>
    <form action="{{ route('clients.update', [
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
            <span class="required">Calendrier du client</span>
            @error('calendar_url')
                <span class="error">{{ $message }}</span>
            @enderror
            <input type="text" name="calendar_url" value="{{ old('calendar_url', $client->calendar_url) }}" required>
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

    <form action="{{ route('clients.destroy', [
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
