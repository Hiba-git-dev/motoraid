<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Toast from '@/Components/Toast.vue';
import AiAssistant from '@/Components/AiAssistant.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const showingNavigationDropdown = ref(false);

// Logique des Notifications
const notifications = ref([]);
const showNotifDropdown = ref(false);
let notifInterval = null;

const fetchNotifications = async () => {
    try {
        const res = await axios.get(route('notifications.index'));
        notifications.value = res.data;
    } catch (e) {
        console.error("Erreur fetch notifs", e);
    }
};

const markAsRead = async () => {
    try {
        await axios.post(route('notifications.read'));
        notifications.value = [];
        showNotifDropdown.value = false;
    } catch (e) {
        console.error("Erreur mark as read", e);
    }
};

onMounted(() => {
    fetchNotifications();
    notifInterval = setInterval(fetchNotifications, 30000);
});

onUnmounted(() => {
    if (notifInterval) clearInterval(notifInterval);
});
</script>

<template>
    <div class="min-h-screen bg-gray-950 text-white">
        <!-- Navigation Principale -->
        <nav class="sticky top-0 z-50 border-b border-white/10 bg-gray-950/80 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('dashboard')">
                                <span class="text-2xl font-bold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">MotoRaid</span>
                            </Link>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
                            
                            <!-- LIENS RÉSERVÉS AUX UTILISATEURS NORMAUX -->
                            <template v-if="$page.props.auth.user.role === 'user'">
                                <NavLink :href="route('rentals.index')" :active="route().current('rentals.index')">Location</NavLink>
                                <NavLink :href="route('planner.index')" :active="route().current('planner.index')">Planificateur</NavLink>
                            </template>

                            <NavLink :href="route('social.index')" :active="route().current('social.index')">Social</NavLink>
                            <NavLink :href="route('tracking.index')" :active="route().current('tracking.index')">Tracking</NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:ms-6 sm:flex sm:items-center gap-4">
                        
                        <!-- BOUTON PASSER PREMIUM (BRILLANT ET VISIBLE) -->
                        <Link v-if="$page.props.auth.user.role === 'user'" :href="route('pricing')" class="group relative flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-600 to-amber-500 text-white text-sm font-bold rounded-xl shadow-[0_0_15px_rgba(234,88,12,0.4)] hover:shadow-[0_0_25px_rgba(234,88,12,0.6)] hover:-translate-y-0.5 transition-all overflow-hidden">
                            <span class="absolute inset-0 bg-white/20 translate-x-[-150%] group-hover:translate-x-[150%] transition-transform duration-700 skew-x-12"></span>
                            <svg class="w-4 h-4 relative z-10" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"></path></svg>
                            <span class="relative z-10">Passer Premium</span>
                        </Link>

                        <!-- CLOCHE NOTIFICATIONS -->
                        <div class="relative">
                            <button @click="showNotifDropdown = !showNotifDropdown" class="relative p-2 text-gray-300 hover:text-white transition-colors rounded-md hover:bg-white/10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                <span v-if="notifications.length > 0" class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse">{{ notifications.length }}</span>
                            </button>
                            
                            <div v-if="showNotifDropdown" class="absolute right-0 mt-2 w-80 bg-gray-900/90 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden">
                                <div class="p-4 border-b border-white/10 flex justify-between items-center">
                                    <h3 class="text-sm font-bold text-white">Notifications</h3>
                                    <button v-if="notifications.length > 0" @click="markAsRead" class="text-xs text-orange-500 hover:text-orange-400">Tout marquer comme lu</button>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a v-for="notif in notifications" :key="notif.id" :href="notif.data.url" target="_blank" class="block p-4 border-b border-white/5 hover:bg-white/5 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <span class="text-2xl">{{ notif.data.icon }}</span>
                                            <div>
                                                <p class="text-sm font-semibold text-white">{{ notif.data.title }}</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ notif.data.message }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    <p v-if="notifications.length === 0" class="p-8 text-center text-gray-500 text-sm">Aucune nouvelle notification.</p>
                                </div>
                            </div>
                        </div>

                        <div class="relative ms-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center rounded-md border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium text-gray-300 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:outline-none">
                                            {{ $page.props.auth.user.name }}
                                            <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink v-if="$page.props.auth.user.role === 'user'" :href="route('rentals.my')" :active="route().current('rentals.my')">Mes Réservations</DropdownLink>
                                    <DropdownLink :href="route('pricing')">Passer Premium</DropdownLink>
                                    <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div class="space-y-1 pb-3 pt-2 bg-gray-950/95 backdrop-blur-xl">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
                    <template v-if="$page.props.auth.user.role === 'user'">
                        <ResponsiveNavLink :href="route('rentals.index')" :active="route().current('rentals.index')">Location</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('planner.index')" :active="route().current('planner.index')">Planificateur</ResponsiveNavLink>
                    </template>
                    <ResponsiveNavLink :href="route('social.index')" :active="route().current('social.index')">Social</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('tracking.index')" :active="route().current('tracking.index')">Tracking</ResponsiveNavLink>
                    
                    <!-- Bouton Premium Mobile -->
                    <ResponsiveNavLink v-if="$page.props.auth.user.role === 'user'" :href="route('pricing')" class="block">
                        <span class="flex items-center gap-2 text-orange-400 font-bold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"></path></svg>
                            Passer Premium
                        </span>
                    </ResponsiveNavLink>

                    <!-- Notifications Mobile -->
                    <ResponsiveNavLink v-if="notifications.length > 0" :href="notifications[0].data.url">
                        <span class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span></span>
                            {{ notifications.length }} Nouvelle(s) Notification(s)
                        </span>
                    </ResponsiveNavLink>
                </div>
                <div class="border-t border-white/10 pb-1 pt-4">
                    <div class="px-4">
                        <div class="text-base font-medium text-white">{{ $page.props.auth.user.name }}</div>
                        <div class="text-sm font-medium text-gray-400">{{ $page.props.auth.user.email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink v-if="$page.props.auth.user.role === 'user'" :href="route('rentals.my')" :active="route().current('rentals.my')">Mes Réservations</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-transparent" v-if="$slots.header">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main>
            <slot />
        </main>

        <Toast />
        <AiAssistant />
    </div>
</template>