@extends('layouts.app', [
    'title' => 'Bienvenue !',
])

@section('body')
    <main class="static">
        <x-hero title="L'application n°1 de la génération automatique de factures"
            description="Créez votre compte et venez vous faciliter la vie !" />

        <section>
            <h2>Présentation</h2>
            <p>Grâce à Factulandar, générez vos factures pour tous vos clients :</p>

            <h3>Créez votre société</h3>
            <img src="/screenshots/societies.png" alt="Page des sociétés liées au compte">
            <h3>Vos clients, vos règles</h3>
            <h4>Création</h4>
            <img src="/screenshots/client_creation.png" alt="Formulaire pour créer un client">
            <h4>Statistiques</h4>
            <img src="/screenshots/client_stats.png" alt="Page de statistique de client">
            <h4>Factures</h4>
            <p>Retrouvez toutes les factures de vos clients, et créez-en une nouvelle en 1 clique.</p>
            <img src="/screenshots/client_invoice_btn.png" alt="Page des factures">

            <h3>Créez une facture</h3>
            <h4>Paramétrez votre facture</h4>
            <p>Donnez-lui un nom, un taux de TVA par défaut, la période de facturation...</p>
            <img src="/screenshots/new_invoice_settings.png" alt="Paramètre de la facture">
            <h4>Ajoutez les événements du calendrier du client vers votre facture</h4>
            <img src="/screenshots/new_invoice_events.png" alt="Ajout des événements à la facture">

            <h3>Gérez et valider votre facture</h3>
            <h4>Modifiez la facture</h4>
            <p>Après avoir créer votre facture, vous pouvez modifier les lignes et en ajouter de nouvelles.</p>
            <img src="/screenshots/invoice_edit.png" alt="Modification de la facture">
            <h4>Imprimer votre facture</h4>
            <img src="/screenshots/invoice_print.png" alt="Page d'impression de la facture">
            <p>Une fois votre facture terminé, vous pouvez la valider pour ne plus autoriser la modification</p>
            <img src="/screenshots/invoice_validate.png" alt="Validation de la facture">
        </section>
        <section>
            <h2>Alors, convaincu ?</h2>

            <a href="{{ route('app.index') }}" class="as-btn">Lancer l'application</a>
        </section>
    </main>
@endsection
