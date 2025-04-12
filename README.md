# Outil de Gestion de Facturation Basé sur un Calendrier

## Description

Cet outil permet aux professionels de générer automatiquement des factures mensuelles à partir des événements d'un calendrier (.ical). Il analyse le calendrier, trie et filtre les événements pertinents, puis génère une facture personnalisable avant son envoi.

## Fonctionnalités

-   **Importation de calendriers** : Supporte tous les fichiers .ical provenant de diverses sources.
-   **Filtrage avancé des événements** :
    -   Tri par classe, date, type d'événement...
-   **Génération automatique de factures** :
    -   Extraction des données du calendrier.
    -   Calcul des montants en fonction des paramètres définis.
    -   Mise en page optimisée pour l'impression et l'envoi.
-   **Modification manuelle** :
    -   Ajout, suppression ou modification d'éléments avant validation.
-   **Exportation et gestion des factures** :
    -   Génération en PDF ou en format compatible avec les logiciels de comptabilité.

## Installation

1. **Clonez le repo GitHub** :
    ```sh
    git clone https://github.com/johan-jnn/factulandar
    cd factulandar
    ```
2. **Installez les dépendances** :
    ```sh
    composer install && bun i
    ```
3. **Configurez la base de données** :
    - Assurez-vous que SQLite est installé.
    - Créez le fichier `.env` à partir de `.env.example` et configurez la base de données :
        ```env
        DB_CONNECTION=sqlite
        DB_DATABASE=/chemin/vers/votre/database.sqlite
        ```
4. **Démarrez le projet** :
    ```sh
    composer dev
    ```

## Utilisation

1. Importez un fichier .ical.
2. Configurez les filtres pour sélectionner les événements pertinents.
3. Générez automatiquement la facture.
4. Modifiez si nécessaire.
5. Exportez et envoyez la facture.

## Technologies utilisées

- **Framework** : Laravel
- **Base de données** : SQLite
- **Gestion des calendriers** : Parsing .ical

## Contribuer

Les contributions ne sont pas acceptées.

## Licence

Utilisable par tous.
