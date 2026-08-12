<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

defineProps({
    upcomingRentals: Array,
    pastRentalsCount: Number,
    myGroups: Array,
    totalSpent: Number
});

const testimonialForm = useForm({ name: '', role: '', content: '', rating: 5 });

const submitTestimonial = () => {
    // On pré-remplit le nom avec celui de l'utilisateur connecté
    testimonialForm.name = page.props.auth.user.name;
    testimonialForm.post(route('testimonials.store'), {
        preserveScroll: true,
        onSuccess: () => testimonialForm.reset('content', 'role')
    });
};
</script>

<template>
    <Head title="Mon Espace" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Bonjour, {{ page.props.auth.user.name }} 👋</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                <!-- MES STATISTIQUES PERSO -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 hover:border-orange-500/30 transition-all">
                        <p class="text-gray-400 text-sm mb-1">Locations à venir</p>
                        <p class="text-3xl font-bold text-white">{{ upcomingRentals.length }}</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 hover:border-orange-500/30 transition-all">
                        <p class="text-gray-400 text-sm mb-1">Rides terminées</p>
                        <p class="text-3xl font-bold text-white">{{ pastRentalsCount }}</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 hover:border-orange-500/30 transition-all">
                        <p class="text-gray-400 text-sm mb-1">Total dépensé</p>
                        <p class="text-3xl font-bold text-white">{{ parseFloat(totalSpent).toFixed(2) }}€</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- MES LOCATIONS EN COURS -->
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-white">Mes locations</h3>
                            <Link :href="route('rentals.my')" class="text-sm text-orange-500 hover:text-orange-400">Voir l'historique</Link>
                        </div>
                        <div class="space-y-4">
                            <div v-for="rental in upcomingRentals" :key="rental.id" class="flex items-center gap-4 bg-gray-900/40 p-4 rounded-xl border border-white/5">
                                <div class="w-16 h-16 flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden">
                                    <img v-if="rental.motorcycle.image_url" :src="rental.motorcycle.image_url" class="w-full h-full object-cover">
                                    <div v-else class="w-full h-full flex items-center justify-center text-2xl">🏍️</div>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-white font-bold">{{ rental.motorcycle.brand }} {{ rental.motorcycle.model }}</p>
                                    <p class="text-gray-400 text-xs">Du {{ new Date(rental.start_date).toLocaleDateString() }} au {{ new Date(rental.end_date).toLocaleDateString() }}</p>
                                </div>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full" :class="{'bg-yellow-500/10 text-yellow-400': rental.status === 'pending', 'bg-green-500/10 text-green-400': rental.status === 'active'}">
                                    {{ rental.status === 'pending' ? 'En attente' : 'En cours' }}
                                </span>
                            </div>
                            <div v-if="upcomingRentals.length === 0" class="text-center py-10 text-gray-500">
                                <p>Aucune location en cours.</p>
                                <!-- LIEN CORRIGÉ ICI -->
                                <Link :href="route('rentals.index')" class="mt-4 inline-block text-orange-500 font-semibold">Louer une moto maintenant</Link>
                            </div>
                        </div>
                    </div>

                    <!-- MES GROUPES -->
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-white">Mes groupes</h3>
                            <Link :href="route('social.index')" class="text-sm text-orange-500 hover:text-orange-400">Explorer</Link>
                        </div>
                        <div class="space-y-3">
                            <div v-for="group in myGroups" :key="group.id" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500/20 to-amber-700/20 border border-orange-500/20 flex items-center justify-center text-lg">🏍️</div>
                                <div>
                                    <p class="text-white text-sm font-medium">{{ group.name }}</p>
                                    <p class="text-gray-500 text-xs">{{ group.members_count }} membres</p>
                                </div>
                            </div>
                            <div v-if="myGroups.length === 0" class="text-center py-8 text-gray-500 text-sm">
                                <p>Vous n'êtes dans aucun groupe.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACCÈS RAPIDE (BOUTONS CORRIGÉS) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- BOUTON LOUER UNE MOTO -->
                    <Link :href="route('rentals.index')" class="bg-gradient-to-r from-orange-600/20 to-amber-700/10 border border-orange-500/30 rounded-3xl p-6 hover:-translate-y-1 transition-all flex items-center justify-between">
                        <div>
                            <h4 class="text-xl font-bold text-white">Louer une moto</h4>
                            <p class="text-gray-400 text-sm mt-1">Parcourez la flotte MotoRaid</p>
                        </div>
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17h2l1-4h12l1 4h2M5 17v2a1 1 0 001 1h1a1 1 0 001-1v-2m8 0v2a1 1 0 001 1h1a1 1 0 001-1v-2M7 13l1.5-5h7L17 13M8 10.5h8"></path></svg>
                    </Link>

                    <Link :href="route('planner.index')" class="bg-gradient-to-r from-blue-600/20 to-amber-700/10 border border-blue-500/30 rounded-3xl p-6 hover:-translate-y-1 transition-all flex items-center justify-between">
                        <div>
                            <h4 class="text-xl font-bold text-white">Planifier un trajet</h4>
                            <p class="text-gray-400 text-sm mt-1">Tracez votre prochaine route</p>
                        </div>
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </Link>
                    
                    <Link :href="route('tracking.index')" class="bg-gradient-to-r from-red-600/20 to-amber-700/10 border border-red-500/30 rounded-3xl p-6 hover:-translate-y-1 transition-all flex items-center justify-between">
                        <div>
                            <h4 class="text-xl font-bold text-white">Activer le Tracking</h4>
                            <p class="text-gray-400 text-sm mt-1">Sécurisez votre ride en live</p>
                        </div>
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </Link>
                </div>

                <!-- TEMOIGNAGE SUR L'EXPERIENCE MOTO RAID -->
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 mt-8">
                    <h3 class="text-lg font-bold text-white mb-2">Partagez votre expérience MotoRaid</h3>
                    <p class="text-gray-400 text-sm mb-6">Votre avis sera affiché sur notre page d'accueil pour aider d'autres motards à nous rejoindre.</p>
                    
                    <form @submit.prevent="submitTestimonial" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input v-model="testimonialForm.role" type="text" placeholder="Votre profil (ex: Motard à Marrakech)" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50">
                            <div class="flex items-center gap-2 bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3">
                                <span class="text-sm text-gray-400">Note :</span>
                                <select v-model="testimonialForm.rating" class="bg-transparent text-white focus:outline-none">
                                    <option class="bg-gray-900" value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                    <option class="bg-gray-900" value="4">⭐⭐⭐⭐ (Très bien)</option>
                                    <option class="bg-gray-900" value="3">⭐⭐⭐ (Bien)</option>
                                </select>
                            </div>
                        </div>
                        <textarea v-model="testimonialForm.content" rows="3" placeholder="Racontez-nous votre expérience avec la plateforme MotoRaid..." class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50" required></textarea>
                        
                        <div class="flex justify-end">
                            <button type="submit" :disabled="testimonialForm.processing" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold rounded-xl hover:shadow-lg transition-all disabled:opacity-50">
                                Soumettre mon témoignage
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>