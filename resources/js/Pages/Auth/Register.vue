<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-950 overflow-hidden">
        <!-- Fond décoratif -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40rem] h-[40rem] bg-orange-600/20 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[40rem] h-[40rem] bg-amber-700/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-4">
            <div class="text-center mb-8">
                <Link href="/">
                    <span class="text-3xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">MotoRaid</span>
                </Link>
            </div>

            <div class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                <h2 class="text-2xl font-bold text-white mb-6 text-center">Créer un compte</h2>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nom complet</label>
                        <input
                            id="name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/20 transition-all"
                            placeholder="Jean Motard"
                        />
                        <p v-if="form.errors.name" class="mt-2 text-sm text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/20 transition-all"
                            placeholder="exemple@motoraid.ma"
                        />
                        <p v-if="form.errors.email" class="mt-2 text-sm text-red-500">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Mot de passe</label>
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/20 transition-all"
                            placeholder="••••••••"
                        />
                        <p v-if="form.errors.password" class="mt-2 text-sm text-red-500">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirmer le mot de passe</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/20 transition-all"
                            placeholder="••••••••"
                        />
                        <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-500">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold py-3.5 rounded-xl hover:shadow-[0_8px_20px_rgba(234,88,12,0.4)] hover:-translate-y-0.5 transition-all duration-300 active:translate-y-0 disabled:opacity-50"
                    >
                        S'inscrire
                    </button>
                </form>
            </div>

            <p class="text-center text-gray-500 text-sm mt-6">
                Déjà inscrit ?
                <Link :href="route('login')" class="text-orange-500 font-semibold hover:text-orange-400">Se connecter</Link>
            </p>
        </div>
    </div>
</template>