<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        Factulandar - {{ $page }}
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="shortcut icon" href="/icon.svg" type="image/svg">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <x-use-toasts />

    @if (session('message'))
        <x-toast text="{{ session('message') }}" />
    @endif
    @error('message')
        <x-toast text="{{ $message }}" type="error" />
    @enderror

    @vite(['resources/scss/app.scss', 'resources/scss/dashboard/_index.scss', 'resources/js/app.js'])
    @yield('head')
</head>

<body>
    @php
        /**
         *
         * @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Client, \App\Models\User>
         */
        $clients = Auth::user()->clients()->orderByDesc('updated_at')->get();

        /**
         * @var \App\Models\Client|null
         */
        $selected_client = $client ?? null;
    @endphp
    <header>
        <a href="{{ route('app.index') }}" class="logo">
            <img src="/icon.svg" alt="Iconne d'une facture">
        </a>

        <h2>Dashboard - {{ $page }}</h2>

        <nav>
            <ul>
                <li><a href="/">Quitter l'application</a></li>
                <li>
                    <a class="as-btn" href="{{ route('user.edit') }}">
                        👤 {{ Auth::user()->name }}
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <main x-data="{
        hide_all() {
                this.searchbar = false;
                this.client_creation = false;
            },
            reset_search() {
                this.search_for = '';
            },
            searchbar: false,
            client_creation: {{ old('_form') == 'client_creation' ?: 'false' }},
            search_for: '',
    }" @keyup.escape="hide_all()">
        <dialog :open="searchbar" id="searchclient">
            <form method="dialog">
                @csrf
                <label>
                    <span>Recherche</span>
                    <input type="search" name="search" id="search"
                        placeholder="Recherchez parmis {{ $clients->count() }} clients..." x-model="search_for">
                </label>
            </form>

            <ul class="result">

            </ul>
        </dialog>

        <dialog :open="client_creation" id="createclient">
            <h2>Créer un nouveau client</h2>
            <form action="{{ route('clients.store') }}" method="post">
                @csrf
                <input type="hidden" name="_form" value="client_creation">
                <label>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <span class="required">Nom du client</span>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>
                    <span class="required">Taux horaire par défaut (€/h)</span>
                    @error('prefered_hours_price')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <input type="number" step="0.01" name="prefered_hours_price"
                        value="{{ old('prefered_hours_price') }}" required>
                </label>
                <label>
                    <span class="required">URL du calendrier</span>
                    @error('calendar_url')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <input type="url" name="calendar_url" value="{{ old('calendar_url') }}" required>
                </label>
                <label>
                    <span class="required">Addresse postale</span>
                    @error('address')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <textarea name="address" required>{{ old('address') }}</textarea>
                </label>

                <div class="actions">
                    <button type="submit">Créer</button>
                    <a href="#" @click.prevent="client_creation=false">Annuler</a>
                </div>
            </form>
        </dialog>

        <aside>
            <ul>
                @foreach ($clients as $client)
                    <li @class([
                        'selected' => $client->id == $selected_client?->id,
                        'disable-click' => isset($clientClickable) ? !$clientClickable : true,
                    ]) data-caption="{{ $client->name }}">
                        <a class="as-btn"
                            href="{{ route('clients.show', [
                                'client' => $client,
                            ]) }}">
                            {{ substr($client->name, 0, 3) }}
                        </a>
                    </li>
                @endforeach
                <li data-caption="Ajouter un client">
                    <button type="button" @click="hide_all(),(client_creation = true)">
                        ➕
                    </button>
                </li>

                {{-- todo --}}
                {{-- <li data-caption="Rechercher un clients">
                    <button type="button" @click="reset_search(),hide_all(),(searchbar= true)">
                        🔍️
                    </button>
                </li> --}}
            </ul>
        </aside>

        <div id="app">
            @yield('body')
        </div>
    </main>
</body>

</html>
