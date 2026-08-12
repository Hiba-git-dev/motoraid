<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';

const chartCanvas = ref(null);
const activeTab = ref('overview');
const showPartnerModal = ref(false);
const showMotoModal = ref(false);

const props = defineProps({
    stats: Object, recentRentals: Array, chartData: Object,
    users: Array, fleet: Array, partners: Array,
    groups: Array, sosAlerts: Array
});

onMounted(() => {
    if (chartCanvas.value && props.chartData) {
        const ctx = chartCanvas.value.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(234, 88, 12, 0.5)');
        gradient.addColorStop(1, 'rgba(234, 88, 12, 0.0)');
        new Chart(ctx, {
            type: 'line',
            data: { labels: props.chartData.labels, datasets: [{ label: 'Réservations', data: props.chartData.data, borderColor: '#ea580c', backgroundColor: gradient, fill: true, tension: 0.4, pointBackgroundColor: '#f97316', pointBorderColor: '#fff', pointHoverRadius: 6, borderWidth: 3 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { color: '#6b7280' }, grid: { color: 'rgba(255,255,255,0.05)' } }, x: { ticks: { color: '#6b7280' }, grid: { display: false } } } }
        });
    }
});

const partnerForm = useForm({ name: '', email: '', password: '' });
const motoForm = useForm({ user_id: '', brand: '', model: '', year: 2024, serial_number: '', plate_number: '', daily_rate: '', deposit: '', quantity: 1, description: '', image: null });

const submitPartner = () => partnerForm.post(route('admin.partners.store'), { preserveScroll: true, onSuccess: () => showPartnerModal.value = false });
const submitMoto = () => motoForm.post(route('admin.motorcycles.store'), { preserveScroll: true, onSuccess: () => showMotoModal.value = false });

const deleteUser = (id) => { if(confirm('Supprimer cet utilisateur ?')) useForm({}).delete(route('admin.users.destroy', id), { preserveScroll: true }); };
const deleteMoto = (id) => { if(confirm('Retirer cette moto ?')) useForm({}).delete(route('admin.motorcycles.destroy', id), { preserveScroll: true }); };
const deleteGroup = (id) => { if(confirm('Supprimer ce groupe ?')) useForm({}).delete(route('admin.groups.destroy', id), { preserveScroll: true }); };
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Cockpit d'Administration</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="flex flex-wrap gap-2 mb-8 bg-white/5 p-2 rounded-2xl border border-white/10 w-fit">
                    <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-orange-600 text-white' : 'text-gray-400 hover:text-white'" class="px-5 py-2 rounded-xl font-semibold text-sm transition-all">Vue d'ensemble</button>
                    <button @click="activeTab = 'users'" :class="activeTab === 'users' ? 'bg-orange-600 text-white' : 'text-gray-400 hover:text-white'" class="px-5 py-2 rounded-xl font-semibold text-sm transition-all">Utilisateurs</button>
                    <button @click="activeTab = 'fleet'" :class="activeTab === 'fleet' ? 'bg-orange-600 text-white' : 'text-gray-400 hover:text-white'" class="px-5 py-2 rounded-xl font-semibold text-sm transition-all">Flotte</button>
                    <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'bg-orange-600 text-white' : 'text-gray-400 hover:text-white'" class="px-5 py-2 rounded-xl font-semibold text-sm transition-all">Modération</button>
                    <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'bg-red-600 text-white' : 'text-gray-400 hover:text-white'" class="px-5 py-2 rounded-xl font-semibold text-sm transition-all">Sécurité (SOS)</button>
                </div>

                <!-- VUE D'ENSEMBLE -->
                <div v-if="activeTab === 'overview'" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6"><p class="text-gray-400 text-sm mb-1">Utilisateurs</p><p class="text-3xl font-bold text-white">{{ stats.total_users }}</p></div>
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6"><p class="text-gray-400 text-sm mb-1">Flotte</p><p class="text-3xl font-bold text-white">{{ stats.total_motorcycles }}</p></div>
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6"><p class="text-gray-400 text-sm mb-1">Revenus</p><p class="text-3xl font-bold text-white">{{ parseFloat(stats.total_revenue).toFixed(2) }}€</p></div>
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6"><p class="text-gray-400 text-sm mb-1">Locations Actives</p><p class="text-3xl font-bold text-white text-green-500">{{ stats.pending_rentals }}</p></div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6"><h3 class="text-lg font-bold text-white mb-6">Activité (6 mois)</h3><div class="h-64"><canvas ref="chartCanvas"></canvas></div></div>
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 overflow-hidden">
                            <h3 class="text-lg font-bold text-white mb-6">Dernières activités</h3>
                            <div class="space-y-4">
                                <div v-for="rental in recentRentals" :key="rental.id" class="flex items-center justify-between border-b border-white/5 pb-3 last:border-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold">{{ rental.renter.name.charAt(0) }}</div>
                                        <div><p class="text-white text-sm">{{ rental.renter.name }}</p><p class="text-gray-500 text-xs">{{ rental.motorcycle.brand }}</p></div>
                                    </div>
                                    <span class="text-xs text-green-400 font-bold">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UTILISATEURS -->
                <div v-if="activeTab === 'users'" class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6">Gestion des Utilisateurs</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead><tr class="border-b border-white/10 text-gray-400 text-sm"><th class="py-3 px-4">Nom</th><th class="py-3 px-4">Email</th><th class="py-3 px-4">Rôle</th><th class="py-3 px-4 text-right">Action</th></tr></thead>
                            <tbody>
                                <tr v-for="user in users" :key="user.id" class="border-b border-white/5 hover:bg-white/5">
                                    <td class="py-4 px-4 text-white font-medium">{{ user.name }}</td>
                                    <td class="py-4 px-4 text-gray-400">{{ user.email }}</td>
                                    <td class="py-4 px-4"><span class="text-xs px-2 py-1 rounded-full" :class="user.role === 'admin' || user.role === 'partner' ? 'bg-orange-500/10 text-orange-400' : 'bg-gray-700 text-gray-400'">{{ user.role }}</span></td>
                                    <td class="py-4 px-4 text-right"><button v-if="user.role !== 'admin'" @click="deleteUser(user.id)" class="text-red-400 hover:text-red-300 text-sm">Supprimer</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FLOTTE -->
                <div v-if="activeTab === 'fleet'" class="space-y-6">
                    <div class="flex justify-end gap-4">
                        <button @click="showPartnerModal = true" class="px-4 py-2 bg-white/5 border border-white/10 text-white rounded-xl hover:bg-white/10">+ Ajouter une Agence</button>
                        <button @click="showMotoModal = true" class="px-4 py-2 bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold rounded-xl">+ Ajouter une Moto</button>
                    </div>
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                        <h3 class="text-xl font-bold text-white mb-6">Flotte MotoRaid</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead><tr class="border-b border-white/10 text-gray-400 text-sm"><th class="py-3 px-4">Moto</th><th class="py-3 px-4">Agence</th><th class="py-3 px-4">Qté</th><th class="py-3 px-4">Prix</th><th class="py-3 px-4 text-right">Action</th></tr></thead>
                                <tbody>
                                    <tr v-for="moto in fleet" :key="moto.id" class="border-b border-white/5 hover:bg-white/5">
                                        <td class="py-4 px-4 text-white flex items-center gap-3"><img v-if="moto.image_url" :src="moto.image_url" class="w-10 h-10 rounded-lg object-cover"><span>{{ moto.brand }} {{ moto.model }}</span></td>
                                        <td class="py-4 px-4 text-gray-400">{{ moto.owner.name }}</td>
                                        <td class="py-4 px-4 text-gray-300">{{ moto.quantity }}</td>
                                        <td class="py-4 px-4 text-orange-500 font-bold">{{ moto.daily_rate }}€</td>
                                        <td class="py-4 px-4 text-right"><button @click="deleteMoto(moto.id)" class="text-red-400 hover:text-red-300 text-sm">Retirer</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- MODÉRATION -->
                <div v-if="activeTab === 'social'" class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6">Gestion des Groupes</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead><tr class="border-b border-white/10 text-gray-400 text-sm"><th class="py-3 px-4">Nom du Groupe</th><th class="py-3 px-4">Créateur</th><th class="py-3 px-4">Membres</th><th class="py-3 px-4 text-right">Action</th></tr></thead>
                            <tbody>
                                <tr v-for="group in groups" :key="group.id" class="border-b border-white/5 hover:bg-white/5">
                                    <td class="py-4 px-4 text-white font-medium">{{ group.name }}</td>
                                    <td class="py-4 px-4 text-gray-400">{{ group.owner.name }}</td>
                                    <td class="py-4 px-4 text-gray-300">{{ group.members_count }}</td>
                                    <td class="py-4 px-4 text-right"><button @click="deleteGroup(group.id)" class="text-red-400 hover:text-red-300 text-sm">Supprimer</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SÉCURITÉ -->
                <div v-if="activeTab === 'security'" class="space-y-6">
                    <div v-if="sosAlerts.length > 0" class="bg-red-600/10 border border-red-500/40 rounded-3xl p-6">
                        <h3 class="text-xl font-bold text-red-400 mb-6 flex items-center gap-2"><svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Alertes SOS Actives</h3>
                        <div class="space-y-4">
                            <div v-for="sos in sosAlerts" :key="sos.id" class="flex items-center justify-between bg-gray-900/50 p-4 rounded-xl">
                                <div><p class="text-white font-bold">{{ sos.user.name }}</p><p class="text-gray-400 text-xs">Position: {{ sos.latitude }}, {{ sos.longitude }}</p></div>
                                <a :href="`https://www.google.com/maps?q=${sos.latitude},${sos.longitude}`" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-red-500">Localiser sur Maps</a>
                            </div>
                        </div>
                    </div>
                    <div v-else class="bg-green-600/10 border border-green-500/30 rounded-3xl p-6 text-center"><p class="text-green-400 font-bold">Aucune alerte SOS active. Tout va bien sur les routes.</p></div>
                </div>

            </div>
        </div>

        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100">
            <div v-if="showPartnerModal" class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4" @click.self="showPartnerModal = false">
                <div class="bg-gray-900/80 border border-white/10 rounded-3xl p-8 w-full max-w-md">
                    <h3 class="text-2xl font-bold text-white mb-6">Nouvelle Agence Partenaire</h3>
                    <form @submit.prevent="submitPartner" class="space-y-4">
                        <input v-model="partnerForm.name" type="text" placeholder="Nom de l'agence" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required>
                        <input v-model="partnerForm.email" type="email" placeholder="Email de connexion" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required>
                        <input v-model="partnerForm.password" type="password" placeholder="Mot de passe" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required>
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold py-3 rounded-xl">Créer l'agence</button>
                    </form>
                </div>
            </div>
        </transition>

        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100">
            <div v-if="showMotoModal" class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4 overflow-y-auto" @click.self="showMotoModal = false">
                <div class="bg-gray-900/80 border border-white/10 rounded-3xl p-8 w-full max-w-2xl my-8">
                    <h3 class="text-2xl font-bold text-white mb-6">Ajouter une Moto au Catalogue</h3>
                    <form @submit.prevent="submitMoto" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2"><label class="text-xs text-gray-400">Agence Partenaire</label><select v-model="motoForm.user_id" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required><option v-for="p in partners" :key="p.id" :value="p.id">{{ p.name }}</option></select></div>
                        <div><label class="text-xs text-gray-400">Marque</label><input v-model="motoForm.brand" type="text" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required></div>
                        <div><label class="text-xs text-gray-400">Modèle</label><input v-model="motoForm.model" type="text" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required></div>
                        <div><label class="text-xs text-gray-400">Année</label><input v-model="motoForm.year" type="number" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required></div>
                        <div><label class="text-xs text-gray-400">Numéro de Série (VIN)</label><input v-model="motoForm.serial_number" type="text" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white"></div>
                        <div><label class="text-xs text-gray-400">Prix par jour (€)</label><input v-model="motoForm.daily_rate" type="number" step="0.01" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required></div>
                        <div><label class="text-xs text-gray-400">Caution (€)</label><input v-model="motoForm.deposit" type="number" step="0.01" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required></div>
                        <div><label class="text-xs text-gray-400">Quantité</label><input v-model="motoForm.quantity" type="number" min="1" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required></div>
                        <div><label class="text-xs text-gray-400">Plaque</label><input v-model="motoForm.plate_number" type="text" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white"></div>
                        <div class="md:col-span-2"><label class="text-xs text-gray-400">Description</label><textarea v-model="motoForm.description" rows="3" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white"></textarea></div>
                        <div class="md:col-span-2"><label class="text-xs text-gray-400">Photo de la moto</label><input @input="motoForm.image = $event.target.files[0]" type="file" accept="image/*" class="w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-white file:bg-orange-600 hover:file:bg-orange-700"></div>
                        <div class="md:col-span-2 flex gap-4 mt-2"><button type="button" @click="showMotoModal = false" class="flex-1 bg-gray-800 text-gray-300 py-3 rounded-xl">Annuler</button><button type="submit" class="flex-1 bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold py-3 rounded-xl">Ajouter au catalogue</button></div>
                    </form>
                </div>
            </div>
        </transition>
    </AuthenticatedLayout>
</template>