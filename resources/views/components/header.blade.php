<header>
    <a href="{{ $logoUrl }}" class="logo">
        <img src="/icon.svg" alt="Iconne d'une facture">
    </a>

    <h2>{{ $pageTitle }}</h2>

    <nav>
        <ul>
            @auth
                <li><a href="{{ route('app.index') }}"><button type="button">Accéder au panel</button></a></li>
            @else
                <li>
                    <a href="{{ route('user.login') }}">Se connecter</a>
                </li>
                <li><a href="{{ route('user.register') }}"><button type="button">Créer mon compte</button></a></li>
            @endauth
        </ul>
    </nav>
</header>
