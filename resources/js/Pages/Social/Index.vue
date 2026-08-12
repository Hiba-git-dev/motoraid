<template>
    <AuthenticatedLayout title="Communauté MotoRaid">
        <div class="py-12 bg-gray-950 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-12 gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                            Communauté <span class="text-orange-500">Motard</span>
                        </h1>
                        <p class="mt-2 text-gray-400">Rejoignez un groupe ou créez le vôtre.</p>
                    </div>
                    <button v-if="$page.props.auth.user.role === 'user'" @click="openCreateModal" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold rounded-xl hover:-translate-y-1 transition-all">
                        + Créer un groupe
                    </button>
                </div>

                <div v-if="myGroups && myGroups.length > 0" class="mb-16">
                    <h2 class="text-2xl font-bold text-white mb-6">Mes Groupes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                         <Link v-for="group in myGroups" :key="group.id" :href="route('social.groups.show', group.id)" class="bg-gradient-to-br from-orange-600/10 to-transparent border border-orange-500/20 rounded-2xl p-5 hover:-translate-y-1 transition-all cursor-pointer">
                            <h4 class="text-white font-bold truncate">{{ group.name }}</h4>
                            <p class="text-gray-400 text-xs mt-1">{{ group.members_count }} membres</p>
                        </Link>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-white mb-6">Groupes disponibles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link v-for="group in groups" :key="group.id" :href="route('social.groups.show', group.id)" class="group relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 transition-all duration-500 hover:-translate-y-2 hover:border-orange-500/40 cursor-pointer">
                        <div class="w-16 h-16 mb-5 rounded-2xl bg-gradient-to-br from-orange-500/20 to-amber-700/20 border border-orange-500/20 flex items-center justify-center text-3xl">🏍️</div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ group.name }}</h3>
                        <p class="text-gray-400 text-sm mb-6 min-h-[60px]">{{ group.description || 'Aucune description.' }}</p>
                        <div class="flex items-center justify-between border-t border-white/5 pt-4 mb-4">
                            <span class="text-xs text-gray-500">{{ group.start_point || 'N/A' }} → {{ group.end_point || 'N/A' }}</span>
                            <span class="text-xs text-gray-400">{{ group.members_count }} / {{ group.max_members }}</span>
                        </div>
                        
                        <!-- BOUTON DYNAMIQUE SELON LE RÔLE -->
                        <div v-if="$page.props.auth.user.role === 'admin'" @click.stop="deleteGroup(group.id)" class="w-full text-center bg-red-600/10 border border-red-500/30 text-red-400 font-semibold py-2.5 rounded-xl hover:bg-red-600/20 transition-all duration-300">
                            Supprimer
                        </div>
                        <div v-else-if="!group.is_joined" @click.stop="joinGroup(group.id)" class="w-full text-center bg-white/5 border border-white/10 text-gray-300 font-semibold py-2.5 rounded-xl hover:bg-orange-600/20 hover:text-orange-400 hover:border-orange-500/30 transition-all duration-300">
                            Rejoindre
                        </div>
                        <div v-else class="w-full text-center bg-green-500/20 text-green-400 border border-green-500/30 font-semibold py-2.5 rounded-xl cursor-default">
                            Membre / En attente
                        </div>
                    </Link>
                </div>
            </div>

            <!-- MODALE CRÉATION -->
            <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100">
                <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" @click.self="closeCreateModal">
                    <div class="bg-gray-900/80 border border-white/10 rounded-3xl p-8 w-full max-w-md max-h-[90vh] overflow-y-auto">
                        <h3 class="text-2xl font-bold text-white mb-6">Créer un groupe</h3>
                        <form @submit.prevent="submitCreate" class="space-y-4">
                            <input v-model="createForm.name" type="text" placeholder="Nom du groupe" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white" required>
                            <textarea v-model="createForm.description" rows="3" placeholder="Description..." class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white"></textarea>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <input v-model="createForm.start_point" type="text" placeholder="Départ (Ex: Casa)" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white">
                                <input v-model="createForm.end_point" type="text" placeholder="Arrivée (Ex: Rabat)" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white">
                            </div>
                            <input v-model="createForm.ride_date" type="date" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white">
                            <input v-model="createForm.max_members" type="number" min="2" max="50" placeholder="Max membres" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white">

                            <div class="flex items-center gap-3 bg-gray-950/40 p-3 rounded-xl border border-white/5">
                                <input type="checkbox" v-model="createForm.is_approval_required" id="approval" class="w-4 h-4 accent-orange-600">
                                <label for="approval" class="text-sm text-gray-300">Approuver manuellement les membres</label>
                            </div>

                            <div class="flex gap-4 pt-4">
                                <button type="button" @click="closeCreateModal" class="flex-1 bg-gray-800 text-gray-300 py-3 rounded-xl">Annuler</button>
                                <button type="submit" class="flex-1 bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold py-3 rounded-xl">Créer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({ groups: Array, myGroups: Array });

const isModalOpen = ref(false);
const createForm = useForm({ name: '', description: '', start_point: '', end_point: '', ride_date: '', max_members: 10, is_approval_required: false });

const openCreateModal = () => { createForm.reset(); isModalOpen.value = true; };
const closeCreateModal = () => { isModalOpen.value = false; };
const submitCreate = () => { createForm.post(route('social.groups.store'), { preserveScroll: true, onSuccess: () => closeCreateModal() }); };

const joinGroup = (groupId) => {
    useForm({}).post(route('social.groups.join', groupId), { preserveScroll: true });
};

const deleteGroup = (groupId) => {
    if (confirm('Supprimer ce groupe définitivement ?')) {
        useForm({}).delete(route('social.groups.destroy', groupId), { preserveScroll: true });
    }
};
</script>