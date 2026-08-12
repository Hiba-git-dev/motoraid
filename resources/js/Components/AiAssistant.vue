<template>
    <div class="fixed bottom-6 right-6 z-[9999] flex flex-col items-end">
        
        <!-- FENÊTRE DE CHAT -->
        <transition 
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-8 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-8 scale-95"
        >
            <div v-if="isOpen" class="w-[360px] h-[520px] mb-4 bg-gray-900/60 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.6)] flex flex-col overflow-hidden">
                
                <!-- HEADER -->
                <div class="p-4 border-b border-white/10 bg-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-amber-700 flex items-center justify-center shadow-[0_0_15px_rgba(234,88,12,0.5)]">
                            <span class="text-xl">🏍️</span>
                        </div>
                        <div>
                            <h3 class="text-white font-bold leading-tight">MotoRaid AI</h3>
                            <p class="text-xs text-green-400 flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span> En ligne
                            </p>
                        </div>
                    </div>
                    <button @click="toggleChat" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- ZONE DES MESSAGES -->
                <div ref="messagesContainer" class="flex-1 p-4 overflow-y-auto space-y-4 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                    <div v-for="(msg, index) in messages" :key="index" class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                        <div 
                            class="max-w-[75%] px-4 py-2.5 rounded-2xl text-sm shadow-lg"
                            :class="msg.role === 'user' 
                                ? 'bg-gray-800 text-gray-200 border border-white/5 rounded-br-none' 
                                : 'bg-gradient-to-br from-orange-600 to-amber-800 text-white rounded-bl-none shadow-[0_4px_15px_rgba(234,88,12,0.3)]'"
                        >
                            {{ msg.text }}
                        </div>
                    </div>
                    
                    <!-- Indicateur de frappe (Loading) -->
                    <div v-if="isLoading" class="flex justify-start">
                        <div class="bg-gradient-to-br from-orange-600 to-amber-800 px-4 py-3 rounded-2xl rounded-bl-none flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>

                <!-- ZONE DE SAISIE -->
                <div class="p-4 border-t border-white/10 bg-gray-900/40">
                    <form @submit.prevent="sendMessage" class="flex items-center gap-2">
                        <input 
                            v-model="inputText" 
                            type="text" 
                            placeholder="Demandez une route, un conseil méca..."
                            class="flex-1 bg-gray-950/60 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:border-orange-500/50 focus:ring-1 focus:ring-orange-500/30 transition-all text-sm"
                            :disabled="isLoading"
                        >
                        <button 
                            type="submit" 
                            class="w-10 h-10 flex-shrink-0 bg-gradient-to-br from-orange-600 to-amber-700 rounded-xl flex items-center justify-center hover:shadow-[0_0_15px_rgba(234,88,12,0.5)] transition-all active:scale-95 disabled:opacity-50"
                            :disabled="isLoading || !inputText.trim()"
                        >
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </transition>

        <!-- BOUTON FLOTTANT D'OUVERTURE -->
        <button 
            v-if="!isOpen"
            @click="toggleChat"
            class="relative w-16 h-16 bg-gradient-to-br from-orange-500 to-amber-700 rounded-full flex items-center justify-center shadow-[0_8px_30px_rgba(234,88,12,0.4)] hover:scale-110 transition-transform duration-300 group"
        >
            <span class="absolute inset-0 rounded-full bg-orange-500 animate-ping opacity-30"></span>
            <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8M8 16h8m-8-4V8a4 4 0 014-4h0a4 4 0 014 4v4m-8 0H6a2 2 0 00-2 2v4a2 2 0 002 2h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2"></path>
            </svg>
        </button>
    </div>
</template>

<script setup>
import { ref, nextTick } from 'vue';
import axios from 'axios';

const isOpen = ref(false);
const isLoading = ref(false);
const inputText = ref('');
const messagesContainer = ref(null);

const messages = ref([
    { 
        role: 'ai', 
        text: 'Salut Motard ! Je suis ton assistant MotoRaid. Où veux-tu rouler aujourd\'hui ?' 
    }
]);

const toggleChat = () => {
    isOpen.value = !isOpen.value;
};

const scrollToBottom = async () => {
    await nextTick();
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
};

const sendMessage = async () => {
    if (!inputText.value.trim() || isLoading.value) return;

    const userMessage = inputText.value;
    messages.value.push({ role: 'user', text: userMessage });
    inputText.value = '';
    isLoading.value = true;
    scrollToBottom();

    try {
        // Utilisation d'Axios pour gérer automatiquement le CSRF et les headers Laravel
        const response = await axios.post('/ai/chat', {
            message: userMessage
        });
        
        const data = response.data;
        messages.value.push({ role: 'ai', text: data.reply || "Désolé, je n'ai pas compris." });
    } catch (error) {
        console.error('Erreur IA:', error);
        let errorMessage = "Oups, une erreur est survenue avec le moteur IA.";
        
        // Affiche le message d'erreur exact renvoyé par Laravel si disponible
        if (error.response && error.response.data && error.response.data.reply) {
            errorMessage = error.response.data.reply;
        } else if (error.response && error.response.status === 419) {
            errorMessage = "Votre session a expiré. Rafraîchissez la page et réessayez.";
        }
        
        messages.value.push({ role: 'ai', text: errorMessage });
    } finally {
        isLoading.value = false;
        scrollToBottom();
    }
};
</script>

<style scoped>
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.3);
    border-radius: 20px;
}
</style>