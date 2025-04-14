<form action="{{ $action }}"
    x-data='{
      calendar: {{ json_encode($calendar) }},
      calendar_ranges: [null, null],
      min_date: null,
      max_date: null,
      group: false,
      query: "",
      events: [],

      init() {
        this.calendar.events = this.calendar.events.map((data, i) => ({...data, id: i, include: true, grouped: false}));
        this.apply();
      },
      apply() {
        let {calendar, min_date, max_date, calendar_ranges, group, query} = this;
        query = query.toLowerCase();
        if(min_date) min_date += "T00:00";
        if(max_date) max_date += "T23:59";

        function pass_filters(event) {
          if(query && !(
            event.summary.toLowerCase().includes(query)
            || event.description?.toLowerCase().includes(query)
          )) return false;

          if(min_date && event.start < min_date) return false;
          if(max_date && event.end > max_date) return false;

          return true;
        }
        
        const events = group ? Object.values(this.calendar.events.reduce((grouped, item) => {
          if(!pass_filters(item)) return grouped;

          const group = grouped[item.summary];
          if(group) {
            group.totalHours += item.totalHours;
            if(item.start < group.start) group.start = item.start;
            if(item.end > group.end) group.end = item.end;
            group.events.push(item);
          } else {
            grouped[item.summary] = {
              ...item,
              grouped: true,
              events: [item]
            };
            delete grouped[item.summary].id;
          }
          return grouped;
        }, {})) : this.calendar.events.filter(pass_filters);

        events.forEach((event) => {
          if(!calendar_ranges[0] || event.start < calendar_ranges[0]) calendar_ranges[0] = event.start;
          if(!calendar_ranges[1] || event.end > calendar_ranges[1]) calendar_ranges[1] = event.end;
        });

        this.events = events;
      }
    }'
    method="post" class="invoice-creator">
    @csrf
    @isset($begin_with)
        {{ $begin_with }}
    @endisset

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
            <input type="text" x-model="query" name="query" @input="apply()"
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
    @error('events')
        <span class="error">{{ $message }}</span>
    @enderror
    <ul class="events">
        <template x-for="(event, _) in events">
            <li>
                <template x-if="event.include">
                    <template x-for="(e, _) in event.events || [event]">
                        <input type="hidden" :name="`events[${e.id}]`" :value="JSON.stringify(event)">
                    </template>
                </template>
                <template x-if="event.grouped">
                    <input type="hidden" name="groups[]" :value="JSON.stringify(event)">
                </template>

                <label class="event-item">
                    <input type="checkbox" x-model="event.include" class="force-default">
                    <div class="summary">
                        <h4 x-text="event.summary"></h4>
                        <p x-text="event.description"></p>
                    </div>
                    <div class="footer">
                        <ul>
                            <li>Heures: <span x-text="event.totalHours"></span>h.</li>
                            <li>Du <span x-text="new Date(event.start).toLocaleString('fr-FR')"></span> au
                                <span x-text="new Date(event.end).toLocaleString('fr-FR')"></span>
                            </li>
                        </ul>
                    </div>
                </label>
            </li>
        </template>
    </ul>

    {{ $slot }}

    <div class="actions">
        @isset($actions)
            {{ $actions }}
        @else
            <button type="submit">Valider</button>
        @endisset
    </div>
</form>
