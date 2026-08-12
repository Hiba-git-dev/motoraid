<template>
    <AuthenticatedLayout :title="group.name">
        <div class="py-12 bg-gray-950 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- COLONNE GAUCHE -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                        <Link :href="route('social.index')" class="text-sm text-gray-400 hover:text-orange-500 mb-4 inline-flex items-center gap-1">&larr; Retour</Link>
                        <h1 class="text-2xl font-bold text-white">{{ group.name }}</h1>
                        <p class="text-gray-400 text-sm mt-2">{{ group.description }}</p>
                        
                        <div class="mt-6 space-y-3 text-sm">
                            <div class="flex justify-between border-t border-white/5 pt-3">
                                <span class="text-gray-500">Leader</span>
                                <span class="text-white font-medium">{{ group.owner.name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Membres</span>
                                <span class="text-white font-medium">{{ group.members.length }} / {{ group.max_members }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Date du Ride</span>
                                <span class="text-white font-medium">{{ group.ride_date ? new Date(group.ride_date).toLocaleDateString() : 'Non définie' }}</span>
                            </div>
                        </div>

                        <!-- BOUTON SUPPRIMER (Visible par le créateur OU l'admin) -->
                        <div v-if="isOwner || isAdmin" class="mt-8 border-t border-red-500/20 pt-6">
                            <button @click="deleteGroup" class="w-full bg-red-600/10 border border-red-500/30 text-red-400 font-semibold py-2.5 rounded-xl hover:bg-red-600/20 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Supprimer ce groupe
                            </button>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4">L'Itinéraire</h3>
                        <div class="flex items-center justify-center gap-4 bg-gray-900/40 p-4 rounded-xl">
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Départ</p>
                                <p class="text-orange-400 font-bold">{{ group.start_point || 'N/A' }}</p>
                            </div>
                            <svg class="w-8 h-8 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            <div class="text-center">
                                <p class="text-xs text-gray-500">Arrivée</p>
                                <p class="text-green-400 font-bold">{{ group.end_point || 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Motards Inscrits</h3>
                        <div class="space-y-3">
                            <div v-for="member in group.members" :key="member.id" class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold">{{ member.name.charAt(0) }}</div>
                                <span class="text-gray-300 text-sm">{{ member.name }}</span>
                                <span v-if="member.id === group.owner_id" class="text-xs bg-orange-500/10 text-orange-400 px-2 py-0.5 rounded-full">Leader</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLONNE DROITE -->
                <div class="lg:col-span-2 flex flex-col gap-6">
                    
                    <div v-if="(isOwner || isAdmin) && pendingMembers.length > 0" class="bg-yellow-500/10 border border-yellow-500/30 rounded-3xl p-6">
                        <h3 class="text-lg font-bold text-yellow-400 mb-4">Demandes d'adhésion</h3>
                        <div class="space-y-3">
                            <div v-for="pending in pendingMembers" :key="pending.id" class="flex items-center justify-between">
                                <span class="text-white">{{ pending.name }}</span>
                                <button @click="approve(pending.id)" class="bg-green-600 text-white px-4 py-1 rounded-lg text-sm">Approuver</button>
                            </div>
                        </div>
                    </div>

                    <!-- CHAT (Lisible par le membre, le créateur et l'admin) -->
                    <div v-if="isMember || isOwner || isAdmin" class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 flex flex-col h-[600px]">
                        <h3 class="text-lg font-bold text-white mb-4">Discussion du Groupe</h3>
                        
                        <div class="flex-1 overflow-y-auto space-y-4 pr-2 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                            <div v-for="msg in messages" :key="msg.id" class="flex gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-700 flex-shrink-0 flex items-center justify-center text-xs font-bold">{{ msg.user.name.charAt(0) }}</div>
                                <div class="bg-gray-900/60 rounded-xl p-3 max-w-[80%]">
                                    <p class="text-xs text-orange-400 mb-1">{{ msg.user.name }}</p>
                                    <p class="text-gray-200 text-sm">{{ msg.message }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- ZONE DE SAISIE (Seulement pour les membres et le créateur, PAS l'admin) -->
                        <form v-if="!isAdmin" @submit.prevent="sendMessage" class="mt-4 flex gap-2">
                            <input v-model="chatForm.message" type="text" placeholder="Écrire un message..." class="flex-1 bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-orange-500/50">
                            <button type="submit" class="bg-orange-600 text-white px-6 rounded-xl font-bold hover:bg-orange-500">Envoyer</button>
                        </form>
                        
                        <!-- MESSAGE POUR L'ADMIN -->
                        <div v-else class="mt-4 text-center text-xs text-gray-500 italic bg-gray-900/40 py-3 rounded-xl border border-white/5">
                            🔒 Mode Supervision : Vous êtes connecté en tant qu'Administrateur. Vous pouvez lire mais pas participer.
                        </div>
                    </div>

                    <!-- SI PAS MEMBRE ET PAS ADMIN -->
                    <div v-else class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 text-center flex items-center justify-center h-[400px]">
                        <div>
                            <p class="text-gray-400 mb-6">Rejoignez ce groupe pour participer au chat et voir les détails complets de l'itinéraire.</p>
                            <button @click="joinGroup" class="px-8 py-3 bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                                Rejoindre le Groupe
                            </button>
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
    group: Object,
    isMember: Boolean,
    isOwner: Boolean,
    isAdmin: Boolean,
    messages: Array,
    pendingMembers: Array
});

const chatForm = useForm({ message: '' });

const sendMessage = () => {
    chatForm.post(route('social.groups.messages', props.group.id), {
        preserveScroll: true,
        onSuccess: () => chatForm.reset()
    });
};

const joinGroup = () => useForm({}).post(route('social.groups.join', props.group.id));
const approve = (userId) => useForm({}).put(route('social.groups.approve', [props.group.id, userId]), { preserveScroll: true });

const deleteGroup = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce groupe définitivement ? Cette action est irréversible.')) {
        useForm({}).delete(route('social.groups.destroy', props.group.id));
    }
};
</script>