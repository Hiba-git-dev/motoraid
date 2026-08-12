🏍️ MotoRaid - L'écosystème numérique des motards au Maroc
MotoRaid est une application SaaS Full-Stack (Laravel 11 + Vue 3 + Inertia.js) conçue pour centraliser l'expérience des motards au Maroc. Elle combine la location de motos, un planificateur GPS avancé, un réseau social communautaire et un système de sécurité en temps réel (SOS).

📄 Télécharger le Rapport de Stage (PDF) /docs/rapport*motoraid.pdf

✨ Fonctionnalités Principales
Multi-Rôles : Administrateur, Agence Partenaire, Utilisateur Normal, Utilisateur Premium.
Marketplace B2B2C : L'admin crée les agences et gère la flotte. Les motards réservent avec une gestion automatique du stock.
GPS Copil : Calcul d'itinéraires, export GPX, météo en direct et hébergements partenaires.
Sécurité & Tracking : Suivi de position en direct et bouton d'urgence SOS avec notification par email.
Monétisation : Système d'abonnement Premium intégré avec PayPal.
🛠️ Stack Technique
Backend : Laravel 11 (PHP 8.2+)
Frontend : Vue.js 3 (Composition API) avec Inertia.js
Base de données : SQLite (local)
Design : Tailwind CSS (Dark Mode, Glassmorphism)
Cartographie : Leaflet.js + OpenStreetMap + OSRM
🚀 Installation et Lancement
Cloner le dépôt
git clone https://github.com/votre-nom/motoraid.gitcd motoraid
Installer les dépendances
bash

composer install
npm install
Configuration de l'environnement
Copier le fichier .env.example en .env
Générer la clé : php artisan key:generate
Initialiser la base de données
bash

php artisan migrate:fresh --seed
php artisan storage:link
Lancer les serveurs
bash

php artisan serve
npm run dev

👤 Comptes de test (Générés par le Seeder)
Admin : admin@motoraid.ma / password
Motard Premium : premium@motoraid.ma / password
Motard Normal : user@motoraid.ma / password
<img width="1350" height="635" alt="a" src="https://github.com/user-attachments/assets/b33676cf-97ee-4755-9df6-e742d58bb077" />
