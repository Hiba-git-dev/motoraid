<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

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
                <h2 class="text-2xl font-bold text-white mb-6 text-center">Connexion</h2>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-400 text-center">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
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
                            autocomplete="current-password"
                            class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/20 transition-all"
                            placeholder="••••••••"
                        />
                        <p v-if="form.errors.password" class="mt-2 text-sm text-red-500">{{ form.errors.password }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                            <input type="checkbox" v-model="form.remember" class="rounded accent-orange-600 bg-gray-900 border-white/10 text-orange-600 focus:ring-orange-500/20">
                            Se souvenir de moi
                        </label>
                        <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-orange-500 hover:text-orange-400">
                            Mot de passe oublié ?
                        </Link>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold py-3.5 rounded-xl hover:shadow-[0_8px_20px_rgba(234,88,12,0.4)] hover:-translate-y-0.5 transition-all duration-300 active:translate-y-0 disabled:opacity-50"
                    >
                        Se connecter
                    </button>
                </form>
            </div>

            <p class="text-center text-gray-500 text-sm mt-6">
                Pas encore de compte ?
                <Link :href="route('register')" class="text-orange-500 font-semibold hover:text-orange-400">Créer un compte</Link>
            </p>
        </div>
    </div>
</template>