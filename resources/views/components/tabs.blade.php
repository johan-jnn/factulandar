<section class="tabs">
    <aside>
        <ul>
            @foreach ($tabs as $tab)
                <li @class([
                    'current' => $tab['url'] == Request::url(),
                ])>
                    <a href="{{ $tab['url'] }}" target="{{ $tab['new_tab'] ?? false ? '_blank' : '_self' }}">
                        {{ $tab['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>
    <main>
        {{ $slot }}
    </main>
</section>
