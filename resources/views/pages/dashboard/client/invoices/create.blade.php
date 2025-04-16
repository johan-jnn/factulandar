@extends('layouts.dashboard', [
    'page' => 'Nouvelle facture',
])

@php
    $maxCalendarTitleLen = 50;
@endphp

@section('body')
    <main>
        <header>
            <a href="{{ route('invoices.index', [
                'client' => $client,
            ]) }}">Annuler</a>
            <h1 style="margin: 0;">
                Nouvelle facture pour {{ $client->name }}
            </h1>
        </header>
        <main>
            <section>
                <h2>
                    Calendrier:
                    {{ substr($calendar['name'], 0, $maxCalendarTitleLen) }}
                    @if (strlen($calendar['name']) > $maxCalendarTitleLen)
                        ...
                    @endif
                </h2>
                <p style="margin-top: 1em;">{{ $calendar['description'] }}</p>
            </section>
            <section>
                <x-events-selector-form :action="route('invoices.store', ['client' => $client])" :calendar="$calendar">
                    <x-slot:begin_with>
                        <h2>Paramètre de la facture</h2>
                        <label>
                            <span>Nom de la facture</span>
                            @error('name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Nommez votre facture (optionnel)">
                        </label>
                        <label>
                            <span class="required">Société facturante</span>
                            @error('society_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <select name="society_id" required>
                                @foreach (Auth::user()->societies()->get() as $society)
                                    <option @if (old('society_id', $client->prefered_society) === $society->id) selected @endif value="{{ $society->id }}">
                                        {{ $society->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span class="required">Taux de TVA</span>
                            @error('tav_ratio')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            <input type="number" name="tav_ratio" step="0.01" value="0" min="0" required>
                        </label>
                        <label style="width: fit-content;" @change="apply()">
                            <span class="required">Periode de facturation</span>
                            <div class="inline-label-group">
                                <label class="inline">
                                    <span>Entre</span>
                                    <input type="date" x-model="min_date" :min="calendar_ranges[0]"
                                        :max="max_date" title="Période de facturation"
                                        name="period_start" required />
                                </label>
                                <label class="inline">
                                    <span>Et</span>
                                    <input type="date" x-model="max_date" :min="min_date"
                                        :max="calendar_ranges[1]" title="Période de facturation"
                                        name="period_end" required />
                                </label>
                            </div>
                        </label>
                        <hr>
                        <h2>Ajouts des événements</h2>
                    </x-slot:begin_with>
                    <x-slot:actions>
                        <button type="submit">Générer la facture</button>
                    </x-slot:actions>
                </x-events-selector-form>
            </section>
        </main>
    </main>
@endsection
