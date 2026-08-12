<template>
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-x-10"
        enter-to-class="opacity-100 translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-x-0"
        leave-to-class="opacity-0 translate-x-10"
    >
        <div v-if="show" class="fixed top-6 right-6 z-[9999] min-w-[300px]">
            <div 
                class="backdrop-blur-2xl border rounded-2xl p-4 shadow-2xl flex items-center gap-3"
                :class="{
                    'bg-green-500/10 border-green-500/30': type === 'success',
                    'bg-red-500/10 border-red-500/30': type === 'error'
                }"
            >
                <div 
                    class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                    :class="{
                        'bg-green-500/20': type === 'success',
                        'bg-red-500/20': type === 'error'
                    }"
                >
                    <svg v-if="type === 'success'" class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <svg v-if="type === 'error'" class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-white">{{ title }}</p>
                    <p class="text-sm text-gray-300">{{ message }}</p>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const show = ref(false);
const type = ref('success');
const title = ref('');
const message = ref('');

const page = usePage();

watch(() => page.props.flash, (flash) => {
    // Sécurité : on vérifie que flash existe avant de lire ses propriétés
    if (!flash) return;

    if (flash.success) {
        type.value = 'success';
        title.value = 'Succès !';
        message.value = flash.success;
        show.value = true;
        resetTimer();
    }
    if (flash.error) {
        type.value = 'error';
        title.value = 'Erreur';
        message.value = flash.error;
        show.value = true;
        resetTimer();
    }
}, { deep: true, immediate: true });

let timer = null;
const resetTimer = () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        show.value = false;
    }, 4000);
};
</script>