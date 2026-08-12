<template>
    <AuthenticatedLayout title="Détail de la moto">
        <div class="py-12 bg-gray-950 min-h-screen">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Lien retour -->
                <Link :href="route('rentals.index')" class="inline-flex items-center gap-2 text-gray-400 hover:text-orange-500 mb-6 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Retour à la flotte
                </Link>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Partie Gauche : Image & Infos -->
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                        <div class="h-72 w-full rounded-2xl bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center text-6xl mb-6">
                            🏍️
                        </div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-3xl font-bold text-white">{{ moto.brand }} {{ moto.model }}</h1>
                                <p class="text-gray-500">{{ moto.year }} - {{ moto.plate_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-orange-500">{{ moto.daily_rate }}€</p>
                                <p class="text-xs text-gray-500">/ jour</p>
                            </div>
                        </div>
                        <p class="text-gray-400 mt-4">{{ moto.description || 'Une machine d\'exception.' }}</p>
                        
                        <!-- Note Moyenne -->
                        <div class="mt-6 flex items-center gap-3 bg-gray-900/40 p-4 rounded-xl">
                            <div class="flex">
                                <svg v-for="n in 5" :key="n" class="w-6 h-6" :class="n <= Math.round(averageRating) ? 'text-orange-500' : 'text-gray-700'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </div>
                            <span class="text-white font-bold text-lg">{{ averageRating || 'Nouveau' }} / 5</span>
                            <span class="text-gray-500 text-sm">({{ moto.reviews.length }} avis)</span>
                        </div>
                    </div>

                    <!-- Partie Droite : Avis & Formulaire -->
                    <div class="flex flex-col gap-6">
                        
                        <!-- Laisser un avis -->
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                            <h3 class="text-xl font-bold text-white mb-4">Laisser un avis</h3>
                            <form @submit.prevent="submitReview" class="space-y-4">
                                <div class="flex gap-2">
                                    <button 
                                        v-for="star in 5" 
                                        :key="star" 
                                        type="button" 
                                        @click="reviewForm.rating = star"
                                        class="focus:outline-none"
                                    >
                                        <svg class="w-8 h-8 transition-colors" :class="star <= reviewForm.rating ? 'text-orange-500' : 'text-gray-700'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    </button>
                                </div>
                                <textarea 
                                    v-model="reviewForm.comment" 
                                    rows="3" 
                                    placeholder="Partagez votre expérience avec cette moto..."
                                    class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50"
                                ></textarea>
                                <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold py-3 rounded-xl hover:shadow-lg transition-all">
                                    Publier l'avis
                                </button>
                            </form>
                        </div>

                        <!-- Liste des avis -->
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 flex-grow">
                            <h3 class="text-xl font-bold text-white mb-4">Avis des motards</h3>
                            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                                <div v-for="review in moto.reviews" :key="review.id" class="border-b border-white/5 pb-4 last:border-0">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold text-white text-sm">{{ review.user.name }}</span>
                                        <div class="flex">
                                            <svg v-for="n in review.rating" :key="n" class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        </div>
                                    </div>
                                    <p class="text-gray-400 text-sm">{{ review.comment || 'Aucun commentaire écrit.' }}</p>
                                </div>
                                <div v-if="moto.reviews.length === 0" class="text-center py-8 text-gray-500 text-sm">
                                    Soyez le premier à laisser un avis !
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    moto: Object,
    averageRating: [Number, String]
});

const reviewForm = useForm({
    motorcycle_id: props.moto.id,
    rating: 0,
    comment: '',
});

const submitReview = () => {
    if (reviewForm.rating === 0) {
        alert('Veuillez sélectionner une note en étoiles.');
        return;
    }
    reviewForm.post(route('reviews.store'), {
        preserveScroll: true,
        onSuccess: () => {
            reviewForm.reset('rating', 'comment');
        }
    });
};
</script>