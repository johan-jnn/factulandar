# 🧾 Factulandar – Outil de Gestion de Facturation Basé sur un Calendrier

## 📌 Description

**Factulandar** est un outil destiné aux professionnels souhaitant automatiser la génération de leurs **factures mensuelles** à partir d’un **calendrier (.ical)**. Il analyse les événements, permet leur filtrage, puis génère des factures modifiables et prêtes à l'envoi.

---

## ⚙️ Fonctionnalités

- **📅 Importation de calendriers**

  - Support des liens au format `.ical` provenant de diverses plateformes (Google, Apple, etc.).

- **🔍 Filtrage des événements**
  - **Par date** (mois, jour, plage personnalisée).
  - **Par mot-clé** (via recherche dans les titres/descriptions).
- **🏢 Gestion des entités**

  - Création et gestion de **clients**.
  - Création et personnalisation d’une ou plusieurs **entreprises** émettrices.

- **🧾 Génération automatique des factures**

  - Analyse des événements filtrés.
  - Calcul des montants selon les règles définies.
  - Édition manuelle possible avant validation (ajout/suppression/modification de lignes).

- **📤 Exportation**
  - **PDF via impression navigateur** (fonctionnalité d'export direct en cours de développement).

---

## 🚀 Installation

1. **Clonez le dépôt GitHub :**

   ```bash
   git clone https://github.com/johan-jnn/factulandar
   cd factulandar
   ```

2. **Installez les dépendances :**

   ```bash
   composer install && bun i
   ```

3. **Configurez l’environnement :**

   - Copiez le fichier `.env.example` en `.env` :
     ```bash
     cp .env.example .env
     ```
   - Vérifiez que **SQLite** est installé et accessible.
   - Configurez le chemin de la base :
     ```env
     DB_CONNECTION=sqlite
     DB_DATABASE=/chemin/vers/votre/database.sqlite
     ```

4. **Lancez le projet :**
   ```bash
   composer dev
   ```

---

## 💡 Utilisation

1. **Renseignez** un lien `.ical` publique.
2. **Appliquez vos filtres** (dates ou mots-clés).
3. **Générez la facture** automatiquement.
4. **Modifiez-la si besoin**, ajoutez ou supprimez des lignes.
5. **Exportez-la** en PDF via le navigateur (Ctrl+P / Cmd+P).

---

## 🛠 Technologies

- **Framework** : Laravel
- **Base de données** : SQLite
- **Frontend** : Bun, Blade
- **Traitement calendrier** : Parsing `.ical`

---

## 📦 Contribution

Ce projet n'accepte pas de contributions externes pour le moment.

---

## 📄 Licence

Utilisable librement. Pas de licence spécifique définie.
