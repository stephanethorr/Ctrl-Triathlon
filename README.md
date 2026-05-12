# 🚴‍♂️ Ctrl Triathlon - Gestion des Contrôles Anti-Dopage

**Ctrl Triathlon** est une application web développée avec Laravel permettant la gestion complète et sécurisée des contrôles anti-dopage lors de compétitions de triathlon. 
Le système met en relation deux acteurs principaux : les **Infirmiers** (qui réalisent les prélèvements) et les **Laboratoires** (qui analysent les échantillons et transmettent les résultats).

---

## 🛠️ Technologies Utilisées

* **Backend :** PHP, Framework Laravel
* **Authentification :** Laravel Breeze
* **Base de données :** SQL Server (sqlsrv)
* **Frontend :** Blade, Tailwind CSS (avec mode sombre natif)
* **Outils :** Vite.js (pour la compilation des assets)

---

## ✨ Fonctionnalités Principales

* 🔒 **Système d'authentification et Rôles :** Accès sécurisé et différencié selon le rôle de l'utilisateur (Infirmier ou Laboratoire).
* 🏥 **Gestion des Laboratoires :** Ajout, modification et suppression des laboratoires partenaires.
* 💊 **Gestion des Produits Dopants :** Définition des substances interdites et de leurs seuils de tolérance légaux.
* 🩸 **Module de Prélèvements :** * Création manuelle d'un dossier d'analyse pour un athlète.
  * Génération automatique et aléatoire (tirage au sort selon un pourcentage d'inscrits).
* 🧪 **Portail Laboratoire (Échanges JSON) :** * Téléchargement des lots à analyser.
  * Module de simulation : génération de résultats aléatoires (positifs ou négatifs) avec mise à jour en temps réel des alertes couleurs (Rouge/Vert) côté infirmier.

---

## ⚙️ Prérequis

Avant de commencer, assurez-vous d'avoir installé sur votre machine :
* [PHP](https://www.php.net/) (version 8.1 ou supérieure)
* [Composer](https://getcomposer.org/)
* [Node.js et npm](https://nodejs.org/) (pour Laravel Breeze et Tailwind CSS)
* Un serveur **SQL Server** configuré et accessible.
* L'extension PHP `pdo_sqlsrv` activée.

---

## 🚀 Installation du projet

Copiez et collez le bloc suivant dans votre terminal pour installer l'ensemble du projet. 
*(⚠️ Attention : L'exécution s'arrêtera après la copie du fichier `.env`. Vous devrez le configurer avec vos identifiants SQL Server avant de lancer les commandes de migration et de démarrage du serveur).*

```bash
# 1. Cloner le dépôt et entrer dans le dossier
git clone [https://github.com/votre-nom/ctrl-triathlon.git](https://github.com/votre-nom/ctrl-triathlon.git)
cd ctrl-triathlon

# 2. Installer les dépendances PHP
composer install

# 3. Installer et compiler les dépendances Front-end (Breeze / Tailwind)
npm install
npm run build

# 4. Créer le fichier d'environnement
cp .env.example .env

# 5. Générer la clé d'application
php artisan key:generate

#6 base de données
** rentrer les infos de la base de donner
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=nom_de_votre_base
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe

# 6. Migrer et peupler la base de données (création des tables et des comptes de test)
php artisan migrate:fresh --seed

# 7. Lancer le serveur local
php artisan serve