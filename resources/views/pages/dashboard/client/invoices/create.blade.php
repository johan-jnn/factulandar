@extends('layouts.dashboard', [
    'page' => 'Nouvelle facture',
])

{{-- @dd(Auth::user()->societies()->get()) --}}

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
                <h2>Création de la facture</h2>
                <form action="{{ route('invoices.store', ['client' => $client]) }}"
                    x-data='{
                      calendar: {{ json_encode($calendar) }},
                      calendar_ranges: [null, null],
                      min_date: null,
                      max_date: null,
                      group: false,
                      query: "",
                      events: [],

                      init() {
                        this.calendar.events = this.calendar.events.map((data, i) => ({...data, id: i, exclude: false, grouped: false}));
                        this.apply();
                      },
                      apply() {
                        const {calendar, min_date, max_date, calendar_ranges, group, query} = this;

                        const events = group ? Object.values(this.calendar.events.reduce((grouped, item) => {
                          const group = grouped[item.summary];
                          if(group) {
                            group.totalHours += item.totalHours;
                            if(item.start < group.start) group.start = item.start;
                            if(item.end > group.end) group.end = item.end;
                            group.events.push(item);
                          } else grouped[item.summary] = {
                            ...item,
                            grouped: true,
                            events: [item]
                          };
                          return grouped;
                        }, {})) : this.calendar.events;

                        const searchQ = query.toLowerCase();
                        this.events = this.calendar.events.filter((event) => {
                          if(!calendar_ranges[0] || event.start < calendar_ranges[0]) calendar_ranges[0] = event.start;
                          if(!calendar_ranges[1] || event.end > calendar_ranges[1]) calendar_ranges[1] = event.end;

                          if(query && (
                            event.summary.toLowerCase().includes(searchQ)
                            || event.description.toLowerCase().includes(searchQ)
                          )) return false;


                          return true;
                        });

                        console.log(this.events);
                      }
                    }'
                    method="post" style="margin: 0 auto">
                    @csrf
                    <h3>Filtres</h3>
                    <menu type="toolbar" class="filterer" @change="apply()">
                        <div class="inline-label-group">
                            <label class="inline">
                                <span>Entre</span>
                                <input type="date" x-model="min_date" :min="calendar_ranges[0]" :max="max_date"
                                    title="Montrer les événements après cette date" name="min_date" />
                            </label>
                            <label class="inline">
                                <span>Et</span>
                                <input type="date" x-model="max_date" :min="min_date" :max="calendar_ranges[1]"
                                    title="Montrer les événements avant cette date" name="max_date" />
                            </label>
                        </div>
                        <label class="inline">
                            <span>Contient</span>
                            <input type="text" x-model="query" name="query"
                                placeholder="Inclure uniquement les événements contenant ce texte dans le titre et/ou description.">
                        </label>

                        {{-- <div class="inline-label-group">
                          <label class="inline">
                              <span>Trier par</span>
                              <select name="order_by">

                              </select>
                          </label>
                          <label class="inline">
                            <span></span>
                          </label>
                        </div> --}}

                        <label class="inline">
                            <span>Grouper les événements</span>
                            <input type="checkbox" x-model="group" name="group">
                        </label>
                    </menu>

                    <h3>Evénements</h3>
                    <ul class="events">
                        <template x-for="(event, _) in events">
                            <li class="event">
                                <template x-if="event.grouped" x-for="(e, _) in event.events">
                                  <input type="hidden" :name="`events[${event.id}].use`" :value="!event.exclude">
                                </template>
                                
                                <template x-if="!event.grouped">
                                    <input type="hidden" :name="`events[${event.id}].use`">
                                </template>

                                <label>
                                  <input type="checkbox" :value="!event.exclude">
                                  <span x-text="event.summary"></span>
                                </label>

                            </li>
                        </template>
                    </ul>

                    <label>
                        <span class="required">Société facturante</span>
                        <select name="society_id" required>
                            @foreach (Auth::user()->societies()->get() as $society)
                                <option @if ($client->prefered_society === $society->id) selected @endif value="{{ $society->id }}">
                                    {{ $society->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="actions">
                        <button type="submit">Générer la facture</button>
                    </div>
                </form>
            </section>
        </main>
    </main>
@endsection
