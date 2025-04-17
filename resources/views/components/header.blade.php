<header>
    <a href="{{ $logoUrl }}" class="logo">
        <img src="/icon.svg" alt="Iconne d'une facture">
    </a>

    <h2>{{ $pageTitle }}</h2>

    <nav>
        <ul>
            @auth
                <li><a class="as-btn" href="{{ route('app.index') }}">Accéder au panel</a></li>
            @else
                <li>
                    <a href="{{ route('user.login') }}">Se connecter</a>
                </li>
                <li><a class="as-btn" href="{{ route('user.register') }}">Créer mon compte</a></li>
            @endauth
        </ul>
    </nav>
</header>
