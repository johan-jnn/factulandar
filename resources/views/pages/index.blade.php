@extends('layouts.app', [
    'title' => 'Bienvenue !',
])

@section('body')
    <main>
        <x-hero title="L'application n°1 de la génération automatique de factures"
            description="Créez votre compte et venez vous faciliter la vie !" />

        <section>
            <h2>Présentation</h2>
            <p>Grâce à Factulandar, générez vos factures pour tous vos clients :</p>

            <h3>Créez votre société</h3>
            <img src="/screenshots/societies.png" alt="Page des sociétés liées au compte">
        </section>
    </main>
@endsection
