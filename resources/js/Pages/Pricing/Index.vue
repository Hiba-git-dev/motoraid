<template>
    <AuthenticatedLayout title="Abonnements MotoRaid">
        <div class="py-12 bg-gray-950 min-h-screen">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-extrabold text-white mb-4">Passez à la vitesse supérieure</h1>
                <p class="text-gray-400 mb-12">Débloquez la sécurité totale, le GPS avancé et les itinéraires illimités.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Pack Gratuit -->
                    <div class="bg-white/5 border border-white/10 rounded-3xl p-8 text-left">
                        <h3 class="text-2xl font-bold text-white mb-2">Rider (Gratuit)</h3>
                        <p class="text-gray-500 mb-6">Pour découvrir la communauté.</p>
                        <ul class="space-y-3 text-gray-400 text-sm mb-8">
                            <li>✅ Calcul d'itinéraire basique</li>
                            <li>✅ Réseau Social (1 groupe)</li>
                            <li>✅ Location de motos (Commission 15%)</li>
                            <li class="text-gray-700">❌ Météo sur le trajet</li>
                            <li class="text-gray-700">❌ Export GPX Illimité</li>
                            <li class="text-gray-700">❌ Lien Famille Live</li>
                        </ul>
                        <button disabled class="w-full bg-gray-800 text-gray-500 font-bold py-3 rounded-xl cursor-not-allowed">Plan Actuel</button>
                    </div>

                    <!-- Pack Premium -->
                    <div class="relative bg-gradient-to-b from-orange-600/10 to-transparent border border-orange-500/40 rounded-3xl p-8 text-left shadow-[0_0_30px_rgba(234,88,12,0.2)]">
                        <div class="absolute top-0 right-0 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl rounded-tr-3xl">POPULAIRE</div>
                        <h3 class="text-2xl font-bold text-orange-400 mb-2">Premium (5€/mois)</h3>
                        <p class="text-gray-400 mb-6">Pour le motard qui veut rouler en toute sécurité.</p>
                        <ul class="space-y-3 text-gray-300 text-sm mb-8">
                            <li>✅ Tout ce qui est dans Rider</li>
                            <li>✅ Commission réduite à 8%</li>
                            <li>✅ Météo & Itinéraires sinueux</li>
                            <li>✅ Export GPX Illimité</li>
                            <li>✅ Groupes illimités</li>
                            <li class="font-bold text-white">✅ Lien Famille Live (SOS Sécurité)</li>
                        </ul>
                        <form @submit.prevent="submitSubscribe">
                            <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-amber-700 text-white font-extrabold py-3 rounded-xl hover:shadow-lg transition-all">
                                Passer Premium
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({});

const submitSubscribe = () => {
    form.post(route('subscribe'), {
        preserveScroll: true,
    });
};
</script>