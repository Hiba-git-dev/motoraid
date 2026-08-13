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

La représentation visuelle de MOTORAID

<img width="1350" height="635" alt="a" src="https://github.com/user-attachments/assets/b33676cf-97ee-4755-9df6-e742d58bb077" />
<img width="1353" height="564" alt="image" src="https://github.com/user-attachments/assets/d4d5bd37-2388-4ba1-a30f-59cfaa490975" />
<img width="1350" height="631" alt="image" src="https://github.com/user-attachments/assets/58aa2f35-e25b-4603-9994-854f71e92382" />
<img width="1351" height="630" alt="image" src="https://github.com/user-attachments/assets/8aaf5643-7bf2-4e9c-9b33-2a89be2aa228" />
<img width="1352" height="628" alt="image" src="https://github.com/user-attachments/assets/e429f531-e8da-456b-b7ce-ed96a5e9b35d" />
<img width="1347" height="629" alt="image" src="https://github.com/user-attachments/assets/062c51b6-d11e-4114-b5fd-274b9fb64e9e" />
<img width="1352" height="636" alt="image" src="https://github.com/user-attachments/assets/9ab18395-ec55-4332-adb9-a422cc4f0e3d" />

 ADMIN DASHBOARD
<img width="1365" height="630" alt="image" src="https://github.com/user-attachments/assets/6412a240-93f5-4ef7-90ea-90557dffec70" />
<img width="1353" height="631" alt="image" src="https://github.com/user-attachments/assets/43bcc272-4d18-4926-9719-23cc711e6b1d" />
<img width="1365" height="633" alt="image" src="https://github.com/user-attachments/assets/2d2628f8-7cad-46ac-b569-7c6be8260fc9" />
<img width="1365" height="635" alt="image" src="https://github.com/user-attachments/assets/b697b318-2a5e-4338-9dee-7ceff9cb99ba" />
<img width="1352" height="631" alt="image" src="https://github.com/user-attachments/assets/e4f66200-be9e-49f5-a55f-b384e3751301" />
<img width="1364" height="631" alt="image" src="https://github.com/user-attachments/assets/bde7e55d-9a5d-40c5-8d64-b80ae0eaaa02" />

USER DASHBOARD
<img width="1348" height="633" alt="image" src="https://github.com/user-attachments/assets/38aafa6a-6908-40f0-a34f-34a7ffba73c9" />
<img width="1348" height="634" alt="image" src="https://github.com/user-attachments/assets/e9260dac-d801-4503-b173-45372e334219" />
<img width="1351" height="627" alt="image" src="https://github.com/user-attachments/assets/f0a566d4-a4ae-4e17-b017-7131f257c26f" />
<img width="1365" height="633" alt="image" src="https://github.com/user-attachments/assets/d4200a0b-cf46-47ce-8bc1-0cf81b5fbab6" />
<img width="1349" height="628" alt="image" src="https://github.com/user-attachments/assets/51f4af5f-db0e-49dc-93ef-ded915cf0595" />
<img width="1351" height="631" alt="image" src="https://github.com/user-attachments/assets/8b673328-889f-4978-9a0d-7ab7cb66313a" />
<img width="1365" height="631" alt="image" src="https://github.com/user-attachments/assets/48b889db-1115-46bc-b80e-409b22d322ea" />
<img width="1350" height="630" alt="image" src="https://github.com/user-attachments/assets/24ba6a7d-b4eb-4c47-9889-aa38ce536f17" />
<img width="1351" height="376" alt="image" src="https://github.com/user-attachments/assets/1d2577a2-1ac9-41dc-a12a-be088e45fe18" />


