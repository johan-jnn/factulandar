@php
    /**
     * @var App\Models\User
     */
    $user = Auth::user();
@endphp

@extends('layouts.dashboard', [
    'page' => 'Clients',
])

@section('body')
    <main>
        <h1>Bienvenue, {{ $user->name }}</h1>

        <section id="societies">
            <h2>Vos sociétés</h2>
            <p>Sur <strong>Factulandar</strong>, vous pouvez posséder autant de sociétés que vous le souhaitez.</p>
            {{-- <p>Ces sociétés permettrons de </p> --}}
            <p>Vos sociétés sont accécibles sur <a href="{{ route('user.edit') }}">la page de votre compte</a>.</p>
        </section>
        <section id="clients">
            <h2>Vos clients</h2>
            <p>Vous pouvez retrouver la liste de vos clients sur la partie gauche de cette interface.</p>
            <p>En cliquant sur le bouton <button type="button" class="visual">➕</button>, vous pouvez ajouter un nouveau
                client.
            </p>
            {{-- <p>Vous pouvez également rechercher des clients en appuyant sur le bouton <button type="button"
                    class="visual">🔍️</button>.</p> --}}
            <hr>
            <p>Après avoir sélectionner un client, vous trouverez les informations sur celui-ci comme le nombre de facture
                faites, le calendrier associé, etc...</p>
            <p>Vous pouvez venir modifier le client dans le menu <i>Modifier le client</i> à gauche de la page client.</p>
        </section>
        <section id="invoices">
            <h2>Générer une facture</h2>
            <p>Dans la rubrique <i>Factures</i> de votre client, vous retrouverez la liste des factures associés à ce
                client.</p>
            <p>Vous pouvez cliquer sur un de ces factures pour retrouver la retrouver. Notez qu'une fois la facture validée,
                vous pourrez uniquement la supprimer ou la dupliquer, mais en aucun elle ne pourra être modifiée.</p>
            <p>Pour créer une nouvelle facture, vous pouvez soit dupliquer une facture existante, soit appuyer sur le bouton
                <button type="button" class="visual">Créer une facture</button> qui vous emmenera sur la page de création de
                factures.
            </p>
        </section>
        <section id="info">
            <h2>Informations</h2>
            <p>Ce projet a été conçu pour un devoir d'étudiant chez My Digital School (Lyon).</p>
            <p>Vous pouvez retrouver le code source de ce projet sur <a href="https://github.com/johan-jnn/factulandar"
                    target="_blank">le repo GitHub</a>, et ses autres projets sur <a href="https://johan-janin.com" target="_blank">son site web</a>.</p>
        </section>
    </main>
@endsection
