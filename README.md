MotoRaid - L'écosystème numérique des motards au Maroc
MotoRaid est une application SaaS Full-Stack (Laravel 11 + Vue 3 + Inertia.js) conçue pour centraliser l'expérience des motards au Maroc. Elle combine la location de motos, un planificateur GPS avancé, un réseau social communautaire et un système de sécurité en temps réel (SOS).

📄 Télécharger le Rapport de Stage (PDF)

✨ Fonctionnalités Principales
Multi-Rôles : Administrateur, Agence Partenaire, Utilisateur Normal, Utilisateur Premium.
Marketplace B2B2C : L'admin crée les agences et gère la flotte (stock, numéros de série, photos). Les motards réservent avec une gestion automatique des dates pour éviter les surréservations.
GPS Copil : Calcul d'itinéraires (OSRM), évitement des autoroutes, export GPX, météo en direct et hébergements partenaires sur la carte.
Réseau Social (Ride Hubs) : Création de groupes, validation des membres par le leader, et chat de groupe en direct.
Sécurité & Tracking : Suivi de position en direct sur la carte et bouton d'urgence SOS qui notifie l'administrateur et envoie un email au contact d'urgence.
Monétisation : Système d'abonnement Premium intégré avec PayPal (mode Sandbox).
Assistant IA : Chatbot flottant intégré pour conseiller les motards.
📸 Aperçu du Projet
(Placez vos captures d'écran dans un dossier screenshots à la racine du projet pour les afficher ici)

1. Landing Page
Landing Page

2. Dashboard Administrateur
Dashboard Admin

3. Location de Motos (Marketplace)
Location de Motos

4. Planificateur GPS Avancé
Planificateur GPS

🛠️ Stack Technique
Backend : Laravel 11 (PHP 8.2+)
Frontend : Vue.js 3 (Composition API) avec Inertia.js
Base de données : SQLite (local)
Design : Tailwind CSS (Dark Mode, Glassmorphism)
Cartographie : Leaflet.js + OpenStreetMap + OSRM
Paiement : PayPal API (Sandbox)
🚀 Installation et Lancement
Note : Les commandes ci-dessous permettent de faire tourner le projet en local.

Cloner le dépôt
git clone https://github.com/votre-nom/motoraid.gitcd motoraid
Installer les dépendances
bash

composer install
npm install
Configuration de l'environnement
Copier le fichier .env.example en .env
Générer la clé d'application : php artisan key:generate
Configurer la base de données SQLite dans le fichier .env.
Initialiser la base de données
bash

php artisan migrate:fresh --seed
php artisan storage:link
Lancer les serveurs
Backend : php artisan serve
Frontend (Vite) : npm run dev
👤 Comptes de test (Générés par le Seeder)
Admin : admin@motoraid.ma / password
Agence : partner@motoraid.ma / password
Motard Premium : premium@motoraid.ma / password
Motard Normal : user@motoraid.ma / password