<template>
    <AuthenticatedLayout title="Mes Réservations">
        <div class="py-12 bg-gray-950 min-h-screen">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-3xl font-extrabold text-white mb-10">Mes Réservations & Factures</h1>

                <div class="space-y-4">
                    <div v-for="rental in rentals" :key="rental.id" class="flex flex-col sm:flex-row gap-4 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-5 hover:border-orange-500/30 transition-all">
                        <div class="w-24 h-24 flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden flex items-center justify-center text-3xl">
                            <img v-if="rental.motorcycle.image_url" :src="rental.motorcycle.image_url" class="w-full h-full object-cover">
                            <span v-else>🏍️</span>
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ rental.motorcycle.brand }} {{ rental.motorcycle.model }}</h3>
                                    <p class="text-gray-400 text-sm mt-1">
                                        Du {{ new Date(rental.start_date).toLocaleDateString('fr-FR') }} au {{ new Date(rental.end_date).toLocaleDateString('fr-FR') }}
                                    </p>
                                </div>
                                <span class="text-orange-500 font-bold">{{ rental.total_price }}€</span>
                            </div>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <!-- BADGE DYNAMIQUE -->
                                <span class="text-xs font-semibold px-3 py-1 rounded-full border" 
                                      :class="{
                                          'bg-yellow-500/10 text-yellow-400 border-yellow-500/20': rental.status === 'pending',
                                          'bg-green-500/10 text-green-400 border-green-500/20': rental.status === 'active' || rental.status === 'completed',
                                          'bg-red-500/10 text-red-400 border-red-500/20': rental.status === 'canceled'
                                      }">
                                    {{ rental.status === 'pending' ? 'En attente' : (rental.status === 'active' || rental.status === 'completed' ? 'Payée & Confirmée' : 'Annulée') }}
                                </span>
                                
                                <button @click="downloadInvoice(rental)" class="text-sm text-orange-400 hover:text-orange-300 font-semibold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Facture
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="rentals.length === 0" class="text-center py-20 text-gray-500 border border-dashed border-white/10 rounded-2xl">
                        Vous n'avez encore réservé aucune moto.
                        <Link :href="route('rentals.index')" class="block mt-4 text-orange-500 font-semibold">Découvrir la flotte</Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODALE FACTURE -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100">
            <div v-if="showInvoice" class="fixed inset-0 z-[9999] bg-black/80 flex items-center justify-center p-4" @click.self="showInvoice = false">
                <div class="bg-white text-gray-900 rounded-2xl p-8 w-full max-w-2xl shadow-2xl relative max-h-[90vh] overflow-y-auto">
                    <button @click="showInvoice = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    
                    <div id="invoice-content" class="p-4">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <h2 class="text-3xl font-extrabold text-orange-600">MotoRaid</h2>
                                <p class="text-gray-500 text-sm">L'écosystème des motards au Maroc</p>
                            </div>
                            <div class="text-right">
                                <h3 class="text-xl font-bold uppercase">Facture</h3>
                                <p class="text-gray-500 text-sm">N° {{ activeInvoice.id }}</p>
                                <p class="text-gray-500 text-sm">{{ new Date(activeInvoice.created_at).toLocaleDateString() }}</p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <p class="text-gray-500 text-xs uppercase font-bold mb-1">Client</p>
                            <p class="font-semibold">{{ $page.props.auth.user.name }}</p>
                            <p class="text-gray-600 text-sm">{{ $page.props.auth.user.email }}</p>
                        </div>

                        <table class="w-full text-left border-collapse mb-8">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="py-2 text-xs uppercase text-gray-500">Description</th>
                                    <th class="py-2 text-xs uppercase text-gray-500 text-right">Prix Unit.</th>
                                    <th class="py-2 text-xs uppercase text-gray-500 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3">
                                        <p class="font-semibold text-gray-900">{{ activeInvoice.motorcycle.brand }} {{ activeInvoice.motorcycle.model }}</p>
                                        <p class="text-gray-500 text-xs">Du {{ new Date(activeInvoice.start_date).toLocaleDateString() }} au {{ new Date(activeInvoice.end_date).toLocaleDateString() }}</p>
                                        <p v-if="activeInvoice.accessories && activeInvoice.accessories.length" class="text-gray-500 text-xs">Accessoires: {{ activeInvoice.accessories.join(', ') }}</p>
                                    </td>
                                    <td class="py-3 text-right text-gray-700">{{ activeInvoice.motorcycle.daily_rate }}€ / jour</td>
                                    <td class="py-3 text-right font-bold text-gray-900">{{ activeInvoice.total_price }}€</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="flex justify-end">
                            <div class="w-1/3">
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Sous-total</span>
                                    <span class="font-semibold">{{ activeInvoice.total_price }}€</span>
                                </div>
                                <div class="flex justify-between py-2 border-t border-gray-200">
                                    <span class="text-xl font-bold text-gray-900">Total Payé</span>
                                    <span class="text-xl font-bold text-orange-600">{{ activeInvoice.total_price }}€</span>
                                </div>
                                <div class="mt-2 text-right text-xs font-bold text-green-600 bg-green-100 inline-block px-2 py-1 rounded">PAYÉ</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-8 pt-4 border-t border-gray-200 no-print">
                        <button @click="printInvoice" class="px-6 py-3 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-500 transition-colors">
                            Imprimer / Télécharger en PDF
                        </button>
                    </div>
                </div>
            </div>
        </transition>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
defineProps({ rentals: Array });

const showInvoice = ref(false);
const activeInvoice = ref({});

const downloadInvoice = (rental) => {
    activeInvoice.value = rental;
    showInvoice.value = true;
};

const printInvoice = () => {
    window.print();
};
</script>

<style>
/* Cache tout sauf la facture lors de l'impression */
@media print {
    body * {
        visibility: hidden;
    }
    #invoice-content, #invoice-content * {
        visibility: visible;
    }
    #invoice-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
}
</style>