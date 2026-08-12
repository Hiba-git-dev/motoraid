<?php

namespace App\Services;

class OpenAiService
{
    /**
     * Simule une réponse de l'IA basée sur l'analyse des mots-clés (Mode Démo Gratuit)
     */
    public function chat(string $userMessage, ?string $systemContext = null): string
    {
        // On convertit le message en minuscules pour analyser les mots-clés
        $message = strtolower($userMessage);

        // 1. Salutations
        if (str_contains($message, 'bonjour') || str_contains($message, 'salut') || str_contains($message, 'hello') || str_contains($message, 'salam')) {
            return "Salut cher motard ! 🏍️ Je suis l'assistant MotoRaid. Comment puis-je t'aider aujourd'hui ? Tu peux me demander des infos sur les routes du Maroc, la météo, la mécanique ou la sécurité.";
        }

        // 2. Itinéraires & Routes
        if (str_contains($message, 'route') || str_contains($message, 'itinéraire') || str_contains($message, 'trajet') || str_contains($message, 'aller') || str_contains($message, 'titchka') || str_contains($message, 'atlas')) {
            return "Pour une belle route au Maroc, je recommande le col du Tizi n'Tichka reliant Marrakech à Ouarzazate. C'est sinueux et les paysages sont à couper le souffle ! N'oublie pas d'activer le GPS MotoRaid pour tracer ton itinéraire, voir la météo et exporter en GPX.";
        }

        // 3. Météo
        if (str_contains($message, 'météo') || str_contains($message, 'pluie') || str_contains($message, 'temps') || str_contains($message, 'froid')) {
            return "Tu peux vérifier la météo en direct sur ton itinéraire en cliquant sur le bouton '🌦️ Voir la Météo' dans le Planificateur GPS. Prépare toujours une veste imperméable dans l'Atlas, le temps change vite en altitude !";
        }

        // 4. Mécanique & Panne
        if (str_contains($message, 'panne') || str_contains($message, 'mécanique') || str_contains($message, 'réparation') || str_contains($message, 'casse') || str_contains($message, 'essence')) {
            return "En cas de panne, vérifie d'abord ton niveau d'essence et l'état de la batterie. Si tu es bloqué en zone isolée, utilise le bouton SOS rouge de l'application : nous alerterons ton contact d'urgence avec ta position GPS exacte.";
        }

        // 5. Location de motos
        if (str_contains($message, 'louer') || str_contains($message, 'location') || str_contains($message, 'moto') || str_contains($message, 'prix')) {
            return "Tu peux trouver des motos d'exception dans la section 'Location' de MotoRaid. Filtre par ville ou par type de moto (Trail, Sportive, Custom) et réserve en quelques clics ! Les avis des autres motards t'aideront à choisir la meilleure machine.";
        }

        // 6. Réseau Social & Groupes
        if (str_contains($message, 'groupe') || str_contains($message, 'ami') || str_contains($message, 'social') || str_contains($message, 'balade')) {
            return "Le réseau social MotoRaid te permet de créer des 'Ride Hubs'. Tu peux inviter tes amis, valider leur participation, et discuter sur le chat de groupe pour organiser la balade. C'est l'outil parfait pour rouler en meute !";
        }

        // 7. Réponse par défaut
        return "Je suis l'assistant MotoRaid. 🤖 Je peux te parler des routes du Maroc, de la location de motos, de la météo, de la mécanique ou de la sécurité SOS. Que veux-tu savoir exactement ?";
    }
}