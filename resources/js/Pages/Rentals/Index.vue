<template>
    <AuthenticatedLayout title="Location de Motos">
        <div class="py-12 bg-gray-950 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="mb-12 text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">
                        Flotte MotoRaid
                    </h1>
                    <p class="mt-4 text-gray-400 text-lg">Trouvez la machine parfaite pour votre prochaine aventure.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- COLONNE GAUCHE : CATALOGUE DES MOTOS -->
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div v-for="moto in motorcycles" :key="moto.id" class="group relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.5)] transition-all duration-500 hover:-translate-y-2 hover:border-orange-500/30 hover:shadow-[0_20px_50px_rgba(234,88,12,0.15)]">
                                <div class="h-44 w-full relative overflow-hidden bg-gradient-to-br from-gray-800 to-gray-900">
                                    <img v-if="moto.image_url" :src="moto.image_url" :alt="moto.brand + ' ' + moto.model" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div v-else class="h-full w-full flex items-center justify-center text-gray-700">
                                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 17h2l1-4h12l1 4h2M5 17v2a1 1 0 001 1h1a1 1 0 001-1v-2m8 0v2a1 1 0 001 1h1a1 1 0 001-1v-2M7 13l1.5-5h7L17 13M8 10.5h8"></path></svg>
                                    </div>
                                    <div class="absolute top-3 right-3 bg-green-500/20 text-green-400 text-xs font-bold px-3 py-1 rounded-full border border-green-500/30 backdrop-blur-sm">
                                        {{ moto.quantity }} en stock
                                    </div>
                                </div>

                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 class="text-lg font-bold text-white">{{ moto.brand }} {{ moto.model }}</h3>
                                            <p class="text-xs text-gray-500">{{ moto.year }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-bold text-orange-500">{{ moto.daily_rate }}€</p>
                                            <p class="text-[10px] text-gray-500">/ jour</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 mt-4">
                                        <Link :href="route('rentals.show', moto.id)" class="flex-1 text-center text-sm border border-white/10 text-gray-300 font-semibold py-2.5 rounded-xl hover:bg-white/5 transition-all">Détails</Link>
                                        <button @click="openBookingModal(moto)" class="flex-1 text-sm bg-gradient-to-r from-orange-600 to-amber-700 text-white font-semibold py-2.5 rounded-xl transition-all duration-300 group-hover:shadow-[0_8px_20px_rgba(234,88,12,0.4)] active:scale-95">Réserver</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COLONNE DROITE : MES RÉSERVATIONS -->
                    <div class="lg:col-span-1">
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sticky top-24">
                            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Mes Réservations
                            </h3>
                            
                            <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                                <div v-for="rental in myRentals" :key="rental.id" class="bg-gray-900/50 border border-white/5 rounded-xl p-4 flex items-center gap-3">
                                    <div class="w-14 h-14 flex-shrink-0 bg-gray-800 rounded-lg overflow-hidden flex items-center justify-center text-xl">
                                        <img v-if="rental.motorcycle.image_url" :src="rental.motorcycle.image_url" class="w-full h-full object-cover">
                                        <span v-else>🏍️</span>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-bold text-white truncate">{{ rental.motorcycle.brand }} {{ rental.motorcycle.model }}</p>
                                        <p class="text-xs text-gray-400">Du {{ new Date(rental.start_date).toLocaleDateString('fr-FR') }} au {{ new Date(rental.end_date).toLocaleDateString('fr-FR') }}</p>
                                        <div class="flex justify-between items-center mt-1">
                                            <span class="text-xs text-orange-500 font-bold">{{ rental.total_price }}€</span>
                                            <Link :href="route('rentals.my')" class="text-[10px] text-gray-400 hover:text-white underline">Voir facture</Link>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="myRentals.length === 0" class="text-center py-10 text-gray-500">
                                    <p class="text-sm">Aucune réservation pour le moment.</p>
                                    <p class="text-xs mt-2">Choisissez une moto à gauche pour commencer !</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- MODALE DE RÉSERVATION -->
            <transition enter-active-class="transition-all duration-300 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-all duration-200 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="closeBookingModal">
                    <div class="bg-gray-900/80 border border-white/10 rounded-3xl p-8 w-full max-w-md shadow-[0_20px_50px_rgba(0,0,0,0.7)] max-h-[90vh] overflow-y-auto">
                        <h3 class="text-2xl font-bold text-white mb-2">Réserver : {{ selectedMoto?.brand }}</h3>
                        <p class="text-gray-400 mb-6">{{ selectedMoto?.daily_rate }}€ / jour</p>

                        <!-- AFFICHAGE DES ERREURS DE STOCK / DATES -->
                        <div v-if="errorMessage" class="mb-4 bg-red-500/10 border border-red-500/30 text-red-400 p-3 rounded-xl text-sm text-center">
                            {{ errorMessage }}
                        </div>

                        <form @submit.prevent="submitBooking" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2 block">Départ</label>
                                    <input v-model="bookingForm.start_date" type="date" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/50" required>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2 block">Retour</label>
                                    <input v-model="bookingForm.end_date" type="date" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/50" required>
                                </div>
                            </div>

                            <!-- ACCESSOIRES -->
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2 block">Accessoires</label>
                                <div class="grid grid-cols-2 gap-2 bg-gray-950/40 p-3 rounded-xl border border-white/5">
                                    <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer">
                                        <input type="checkbox" value="Casque" v-model="bookingForm.accessories" class="w-4 h-4 accent-orange-600"> Casque
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer">
                                        <input type="checkbox" value="Top-Case" v-model="bookingForm.accessories" class="w-4 h-4 accent-orange-600"> Top-Case
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer">
                                        <input type="checkbox" value="Gants" v-model="bookingForm.accessories" class="w-4 h-4 accent-orange-600"> Gants
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-gray-300 cursor-pointer">
                                        <input type="checkbox" value="Blouson" v-model="bookingForm.accessories" class="w-4 h-4 accent-orange-600"> Blouson
                                    </label>
                                </div>
                            </div>

                            <!-- NOTES -->
                            <div>
                                <label class="text-xs font-semibold uppercase tracking-wider text-gray-300 mb-2 block">Notes / Demandes spéciales</label>
                                <textarea v-model="bookingForm.notes" rows="3" placeholder="Ex: Je suis grand, j'ai besoin d'une taille L..." class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50"></textarea>
                            </div>

                            <!-- CALCUL DU PRIX EN DIRECT -->
                            <div v-if="computedPrice > 0" class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-4 flex justify-between items-center">
                                <span class="text-gray-300 text-sm">Total à payer ({{ computedDays }} jour(s))</span>
                                <span class="text-2xl font-bold text-orange-500">{{ computedPrice }}€</span>
                            </div>
                            
                            <div class="flex gap-4 pt-4 sticky bottom-0 bg-gray-900/80 backdrop-blur-md -mx-8 px-8 py-4 border-t border-white/10">
                                <button type="button" @click="closeBookingModal" class="flex-1 bg-gray-800 text-gray-300 font-semibold py-3 rounded-xl hover:bg-gray-700 transition-colors">Annuler</button>
                                <button type="submit" :disabled="bookingForm.processing" class="flex-1 bg-[#0070ba] text-white font-bold py-3 rounded-xl hover:bg-[#005ea6] transition-all active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7.07 16.97h9.9l1.41-5.66h-12.72l1.41 5.66zm-1.41-7.97h12.72l-1.41-5.66h-9.9l-1.41 5.66zm14.34 2.83v3.3c0 .55-.45 1-1 1h-1.59c-.55 0-1-.45-1-1v-3.3h-10.82v3.3c0 .55-.45 1-1 1h-1.59c-.55 0-1-.45-1-1v-3.3h-1.59v-2.83h1.59v-3.3c0-.55.45-1 1-1h1.59c.55 0 1 .45 1 1v3.3h10.82v-3.3c0-.55.45-1 1-1h1.59c.55 0 1 .45 1 1v3.3h1.59v2.83h-1.59z"/></svg>
                                    Payer avec PayPal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();

defineProps({ 
    motorcycles: Array,
    myRentals: Array
});

const isModalOpen = ref(false);
const selectedMoto = ref(null);
const errorMessage = ref('');

const bookingForm = useForm({
    motorcycle_id: null,
    start_date: '',
    end_date: '',
    accessories: [],
    notes: ''
});

const computedDays = computed(() => {
    if (!bookingForm.start_date || !bookingForm.end_date || !selectedMoto.value) return 0;
    const start = new Date(bookingForm.start_date);
    const end = new Date(bookingForm.end_date);
    const diffTime = Math.abs(end - start);
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; 
});

const computedPrice = computed(() => {
    if (computedDays.value <= 0 || !selectedMoto.value) return 0;
    return (computedDays.value * selectedMoto.value.daily_rate).toFixed(2);
});

const openBookingModal = (moto) => {
    selectedMoto.value = moto;
    bookingForm.motorcycle_id = moto.id;
    bookingForm.start_date = '';
    bookingForm.end_date = '';
    bookingForm.accessories = [];
    bookingForm.notes = '';
    errorMessage.value = ''; // Reset error
    isModalOpen.value = true;
};

const closeBookingModal = () => {
    isModalOpen.value = false;
    selectedMoto.value = null;
};

const submitBooking = () => {
    errorMessage.value = ''; // Clear error before sending
    bookingForm.post(route('rentals.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isModalOpen.value = false;
        },
        onError: (errors) => {
            // Affiche l'erreur de validation si les dates sont mauvaises
            errorMessage.value = errors.start_date || errors.end_date || "Une erreur est survenue. Vérifiez vos dates.";
        }
    });
};

// Surveille les messages flash d'erreur (ex: stock épuisé)
import { watch } from 'vue';
watch(() => page.props.flash, (flash) => {
    if (flash.error) {
        errorMessage.value = flash.error;
    }
}, { deep: true });
</script>