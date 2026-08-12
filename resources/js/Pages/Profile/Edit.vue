<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const form = useForm({
    name: page.props.auth.user.name,
    email: page.props.auth.user.email,
    emergency_name: page.props.auth.user.emergency_name,
    emergency_phone: page.props.auth.user.emergency_phone,
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Mon Profil & Sécurité</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Carte Infos Personnelles -->
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-lg">
                    <h3 class="text-lg font-bold text-white mb-6">Informations personnelles</h3>

                    <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6 max-w-xl">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nom</label>
                            <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name" class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/50" />
                            <p v-if="form.errors.name" class="mt-2 text-sm text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                            <input id="email" type="email" v-model="form.email" required autocomplete="username" class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/50" />
                            <p v-if="form.errors.email" class="mt-2 text-sm text-red-500">{{ form.errors.email }}</p>
                        </div>

                        <div v-if="mustVerifyEmail && page.props.auth.user.email_verified_at === null">
                            <p class="text-sm mt-2 text-gray-400">
                                Votre adresse email n'est pas vérifiée.
                                <Link :href="route('verification.send')" method="post" as="button" class="underline text-orange-500 hover:text-orange-400 rounded-md focus:outline-none">Renvoyer l'email de vérification</Link>
                            </p>
                            <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-400">Un nouveau lien de vérification a été envoyé.</div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold rounded-xl hover:shadow-[0_8px_20px_rgba(234,88,12,0.4)] hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-50">Sauvegarder</button>
                            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-to-class="opacity-0">
                                <p v-if="form.recentlySuccessful" class="text-sm text-green-400">Sauvegardé.</p>
                            </Transition>
                        </div>
                    </form>
                </div>

                <!-- NOUVELLE CARTE : SÉCURITÉ & URGENCE -->
                <div class="bg-red-500/5 backdrop-blur-xl border border-red-500/20 rounded-3xl p-6 sm:p-8 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-400">Contact d'Urgence (SOS)</h3>
                            <p class="text-gray-400 text-sm">Sera notifié automatiquement si vous déclenchez le bouton SOS sur la carte.</p>
                        </div>
                    </div>

                    <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6 max-w-xl">
                        <div>
                            <label for="emergency_name" class="block text-sm font-medium text-gray-300 mb-2">Nom du contact (ex: Épouse, Père...)</label>
                            <input id="emergency_name" type="text" v-model="form.emergency_name" placeholder="Ahmed B." class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-red-500/50" />
                        </div>

                        <div>
                            <label for="emergency_phone" class="block text-sm font-medium text-gray-300 mb-2">Numéro de téléphone</label>
                            <input id="emergency_phone" type="tel" v-model="form.emergency_phone" placeholder="+212 6 12 34 56 78" class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-red-500/50" />
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" :disabled="form.processing" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-500 transition-all duration-300 disabled:opacity-50">Mettre à jour le contact</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>