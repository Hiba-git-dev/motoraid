<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AiAssistant from '@/Components/AiAssistant.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    testimonials: Array
});

const mouseX = ref(0);
const mouseY = ref(0);
const dashboardStyle = ref({});

const handleMouseMove = (e) => {
    mouseX.value = (e.clientX / window.innerWidth - 0.5) * 20;
    mouseY.value = (e.clientY / window.innerHeight - 0.5) * 20;
    dashboardStyle.value = {
        transform: `perspective(1000px) rotateX(${15 - mouseY.value}deg) rotateY(${-15 + mouseX.value}deg) translateZ(0)`
    };
};

// Logique pour le Scrollspy (surlignage du menu au défilement)
const activeSection = ref('home');
let observer = null;

// Logique pour la FAQ
const openFaq = ref(1); // Ouvre la première question par défaut
const faqs = [
    { id: 1, q: "L'assurance est-elle incluse dans la location ?", a: "Oui, toutes les motos proposées par nos agences partenaires incluent une assurance responsabilité civile de base. Une assurance tous risques (vol, dommages) peut être souscrite directement à l'agence lors de la récupération de la moto." },
    { id: 2, q: "Que se passe-t-il en cas de panne ou d'urgence ?", a: "Chaque motard a accès au bouton SOS dans l'application. En cas de clic, votre position GPS exacte est envoyée à votre contact d'urgence et à l'administrateur de la plateforme. Pour les pannes mécaniques, l'agence de location vous fournira une assistance." },
    { id: 3, q: "Comment fonctionnent les annulations ?", a: "Vous pouvez annuler votre réservation gratuitement jusqu'à 48h avant la date de départ. Au-delà, des frais peuvent s'appliquer selon les conditions de l'agence partenaire. Le remboursement s'effectue via PayPal." },
    { id: 4, q: "L'abonnement Premium est-il sans engagement ?", a: "Oui, l'abonnement MotoRaid Premium à 5€/mois est 100% sans engagement. Vous pouvez l'annuler à tout moment depuis votre profil, et vous conserverez vos avantages jusqu'à la fin du mois payé." }
];

const toggleFaq = (id) => {
    openFaq.value = openFaq.value === id ? null : id;
};

onMounted(() => {
    window.addEventListener('mousemove', handleMouseMove);
    
    const sections = document.querySelectorAll('section[id]');
    observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                activeSection.value = entry.target.id;
            }
        });
    }, { rootMargin: '-30% 0px -60% 0px' });

    sections.forEach(sec => observer.observe(sec));
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
    if (observer) observer.disconnect();
});
</script>

<template>
    <Head title="MotoRaid - L'écosystème ultime pour les motards" />

    <div class="relative min-h-screen bg-gray-950 text-white overflow-hidden font-sans selection:bg-orange-500/30">
        
        <!-- FOND DÉCORATIF -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <div class="absolute inset-0 opacity-30 bg-[radial-gradient(#1a1a1a_1px,transparent_1px)] [background-size:16px_16px] [mask-image:radial-gradient(ellipse_at_center,black_20%,transparent_75%)]"></div>
            <div class="absolute top-[-10%] left-[-10%] w-[50rem] h-[50rem] bg-orange-600/20 rounded-full blur-[120px] animate-blob"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[50rem] h-[50rem] bg-amber-700/20 rounded-full blur-[120px] animate-blob" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/4 left-0 w-full h-px bg-gradient-to-r from-transparent via-orange-500/50 to-transparent animate-light-sweep"></div>
        </div>

        <!-- HEADER FLOTTANT REDESIGN AVEC SCROLLSPY -->
        <nav class="fixed top-0 left-0 right-0 z-30 px-4 md:px-8 pt-4">
            <div class="max-w-7xl mx-auto bg-gray-950/50 backdrop-blur-xl border border-white/10 rounded-2xl shadow-[0_8px_32px_rgba(0,0,0,0.5)]">
                <div class="flex items-center justify-between px-6 py-3">
                    <Link :href="route('home')" class="flex items-center gap-2 group">
                        <div class="relative w-9 h-9 bg-gradient-to-br from-orange-500 to-amber-700 rounded-xl flex items-center justify-center shadow-[0_0_15px_rgba(234,88,12,0.5)] group-hover:rotate-12 transition-transform duration-500">
                            <span class="text-lg">🏍️</span>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">MotoRaid</span>
                    </Link>
                    
                    <!-- LIENS DU MENU AVEC SCROLLSPY -->
                    <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                        <a href="#location" class="relative py-2 transition-colors" :class="activeSection === 'location' ? 'text-white' : 'text-gray-400 hover:text-white'">
                            Location
                            <span class="absolute -bottom-0.5 left-0 h-0.5 bg-orange-500 transition-all duration-300" :class="activeSection === 'location' ? 'w-full' : 'w-0 group-hover:w-full'"></span>
                        </a>
                        <a href="#features" class="relative py-2 transition-colors" :class="activeSection === 'features' ? 'text-white' : 'text-gray-400 hover:text-white'">
                            Fonctionnalités
                            <span class="absolute -bottom-0.5 left-0 h-0.5 bg-orange-500 transition-all duration-300" :class="activeSection === 'features' ? 'w-full' : 'w-0 group-hover:w-full'"></span>
                        </a>
                        <a href="#testimonials" class="relative py-2 transition-colors" :class="activeSection === 'testimonials' ? 'text-white' : 'text-gray-400 hover:text-white'">
                            Avis
                            <span class="absolute -bottom-0.5 left-0 h-0.5 bg-orange-500 transition-all duration-300" :class="activeSection === 'testimonials' ? 'w-full' : 'w-0 group-hover:w-full'"></span>
                        </a>
                        <a href="#pricing" class="relative py-2 transition-colors" :class="activeSection === 'pricing' ? 'text-white' : 'text-gray-400 hover:text-white'">
                            Tarifs
                            <span class="absolute -bottom-0.5 left-0 h-0.5 bg-orange-500 transition-all duration-300" :class="activeSection === 'pricing' ? 'w-full' : 'w-0 group-hover:w-full'"></span>
                        </a>
                        <a href="#faq" class="relative py-2 transition-colors" :class="activeSection === 'faq' ? 'text-white' : 'text-gray-400 hover:text-white'">
                            FAQ
                            <span class="absolute -bottom-0.5 left-0 h-0.5 bg-orange-500 transition-all duration-300" :class="activeSection === 'faq' ? 'w-full' : 'w-0 group-hover:w-full'"></span>
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="px-5 py-2 bg-white/5 border border-white/10 text-white text-sm font-semibold rounded-xl hover:bg-white/10 transition-all">Dashboard</Link>
                        <template v-else>
                            <Link :href="route('login')" class="hidden sm:block px-4 py-2 text-gray-300 hover:text-white transition-colors text-sm font-medium">Log in</Link>
                            <Link v-if="canRegister" :href="route('register')" class="relative px-5 py-2 bg-gradient-to-r from-orange-600 to-amber-700 text-white text-sm font-semibold rounded-xl overflow-hidden group">
                                <span class="relative z-10">S'inscrire</span>
                                <span class="absolute inset-0 bg-white/20 translate-x-[-150%] group-hover:translate-x-[150%] transition-transform duration-700 skew-x-12"></span>
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- HERO SECTION -->
        <section id="home" class="relative z-10 min-h-screen flex flex-col items-center justify-center text-center px-6 pt-24">
            <div class="max-w-5xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 mb-8 bg-white/5 backdrop-blur-md border border-white/10 rounded-full text-sm text-orange-400 shadow-lg animate-fade-in-down">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                    </span>
                    Nouveau : Assistant IA & Tracking Live
                </div>
                <h1 class="text-5xl md:text-8xl font-extrabold tracking-tighter mb-6 leading-none animate-fade-in-up">
                    <span class="block text-white drop-shadow-[0_5px_15px_rgba(0,0,0,0.5)]">Le Maroc, à moto.</span>
                    <span class="block text-transparent bg-clip-text bg-[linear-gradient(to_right,#f97316,#fbbf24,#f97316,#ea580c)] bg-[length:200%_auto] animate-shine">Autrement.</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto mb-10 font-light animate-fade-in-up" style="animation-delay: 0.2s;">Location premium, routage avancé, et alerte SOS instantanée. Rejoignez le premier écosystème 100% dédié à la passion de la moto et à votre sécurité sur la route.</p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <Link v-if="canRegister" :href="route('register')" class="group relative w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold rounded-2xl shadow-[0_8px_30px_rgba(234,88,12,0.4)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <span class="relative z-10">Commencer l'aventure</span>
                        <span class="absolute inset-0 bg-white/20 translate-x-[-150%] group-hover:translate-x-[150%] transition-transform duration-700 skew-x-12"></span>
                    </Link>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-white/5 backdrop-blur-md border border-white/10 text-gray-300 font-semibold rounded-2xl hover:bg-white/10 hover:text-white hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                        Découvrir les fonctionnalités
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </a>
                </div>

                <div class="mt-12 flex items-center justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.6s;">
                    <div class="flex -space-x-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-500 to-red-500 border-2 border-gray-950 flex items-center justify-center text-xs font-bold">M</div>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 border-2 border-gray-950 flex items-center justify-center text-xs font-bold">A</div>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-teal-500 border-2 border-gray-950 flex items-center justify-center text-xs font-bold">S</div>
                    </div>
                    <div class="text-left">
                        <div class="flex gap-0.5 mb-1">
                            <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <p class="text-xs text-gray-500">Approuvé par +10,000 motards au Maroc</p>
                    </div>
                </div>
            </div>

            <!-- VITRINE 3D (FAUX DASHBOARD) -->
            <div class="relative mt-24 w-full max-w-5xl mx-auto animate-fade-in-up" style="animation-delay: 0.8s;">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-transparent to-transparent z-20 pointer-events-none"></div>
                
                <div class="relative bg-gray-900/50 backdrop-blur-2xl rounded-3xl p-2 shadow-[0_50px_100px_rgba(0,0,0,0.8)] transition-transform duration-300 ease-out" :style="dashboardStyle">
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-b from-orange-500/20 via-transparent to-transparent pointer-events-none"></div>
                    
                    <div class="relative bg-gray-950/80 rounded-2xl h-[400px] flex flex-col gap-4 p-6 overflow-hidden">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-40 bg-orange-500/10 blur-3xl rounded-full pointer-events-none"></div>

                        <svg class="absolute bottom-0 left-0 w-full h-2/3 opacity-10 pointer-events-none" viewBox="0 0 400 200" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#f97316;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#f97316;stop-opacity:0" />
                                </linearGradient>
                            </defs>
                            <path d="M0,150 C50,100 100,180 150,120 C200,60 250,140 300,80 C350,40 400,100 400,100 L400,200 L0,200 Z" fill="url(#grad1)" />
                            <path d="M0,150 C50,100 100,180 150,120 C200,60 250,140 300,80 C350,40 400,100 400,100" fill="none" stroke="#f97316" stroke-width="2" />
                        </svg>

                        <div class="relative flex justify-between items-center mb-2 z-10">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500/50"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500/50"></div>
                            </div>
                            <div class="flex items-center gap-2 bg-white/5 px-3 py-1 rounded-lg border border-white/5">
                                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-xs text-gray-400">Live Tracking Actif</span>
                            </div>
                        </div>

                        <div class="relative grid grid-cols-3 gap-4 flex-1 z-10">
                            <div class="flex flex-col gap-4">
                                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/5">
                                    <div class="h-2 bg-orange-500/50 w-1/2 rounded-full mb-2"></div>
                                    <div class="text-2xl font-bold text-white">125 km/h</div>
                                    <div class="w-full bg-gray-800 h-1 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-gradient-to-r from-orange-600 to-amber-500 h-1 rounded-full w-3/4 animate-pulse"></div>
                                    </div>
                                </div>
                                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/5 flex-grow space-y-3">
                                    <div class="flex items-center gap-2"><div class="w-6 h-6 rounded-full bg-gradient-to-br from-orange-500 to-amber-700 border-2 border-gray-950"></div><div class="h-2 bg-white/10 rounded-full w-3/4"></div></div>
                                    <div class="flex items-center gap-2"><div class="w-6 h-6 rounded-full bg-gray-700 border-2 border-gray-950"></div><div class="h-2 bg-white/10 rounded-full w-1/2"></div></div>
                                    <div class="flex items-center gap-2"><div class="w-6 h-6 rounded-full bg-gray-700 border-2 border-gray-950"></div><div class="h-2 bg-white/10 rounded-full w-2/3"></div></div>
                                </div>
                            </div>
                            
                            <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/5 col-span-2 relative overflow-hidden">
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#1a1a1a_1px,transparent_1px)] [background-size:16px_16px]"></div>
                                <svg class="absolute inset-0 w-full h-full opacity-10" viewBox="0 0 200 100" preserveAspectRatio="none">
                                    <path d="M-10,30 Q20,10 40,30 T80,30 T110,30" fill="none" stroke="#ffffff" stroke-width="0.5" />
                                    <path d="M-10,50 Q20,30 40,50 T80,50 T110,50" fill="none" stroke="#ffffff" stroke-width="0.5" />
                                    <path d="M-10,70 Q20,50 40,70 T80,70 T110,70" fill="none" stroke="#ffffff" stroke-width="0.5" />
                                </svg>
                                <svg class="absolute inset-0 w-full h-full" viewBox="0 0 200 100" preserveAspectRatio="none">
                                    <path d="M0,80 Q50,80 50,50 T100,20 T200,40" fill="none" stroke="#ea580c" stroke-width="2" stroke-dasharray="4" />
                                </svg>
                                <div class="absolute top-[20%] left-[50%] w-3 h-3 bg-orange-500 rounded-full shadow-[0_0_15px_rgba(234,88,12,1)] animate-ping-slow"></div>
                                <div class="absolute top-[20%] left-[50%] w-3 h-3 bg-orange-500 rounded-full border-2 border-white"></div>
                            </div>
                        </div>

                        <div class="absolute bottom-4 right-4 bg-gray-900/90 backdrop-blur-md border border-white/10 rounded-xl p-3 flex items-center gap-3 shadow-lg animate-slide-in z-20">
                            <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center text-green-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs text-white font-bold">Réservation Confirmée</div>
                                <div class="h-2 bg-white/10 rounded-full w-24 mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION STATISTIQUES -->
        <section class="relative z-10 py-16 px-6 md:px-12">
            <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-8">
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">500+</p>
                    <p class="text-gray-400 text-sm mt-2">Motos disponibles</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">10k+</p>
                    <p class="text-gray-400 text-sm mt-2">Motards actifs</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">50+</p>
                    <p class="text-gray-400 text-sm mt-2">Agences partenaires</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">100%</p>
                    <p class="text-gray-400 text-sm mt-2">Routes sécurisées</p>
                </div>
            </div>
        </section>

        <!-- SECTION LOCATION -->
        <section id="location" class="relative z-10 py-24 px-6 md:px-12">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="text-4xl md:text-5xl font-bold text-white tracking-tight mb-6">Louez la machine parfaite <br> <span class="text-orange-500">en 3 clics.</span></h2>
                        <p class="text-gray-400 mb-10 text-lg">Du Trail pour l'Atlas au Roadster pour la ville, trouvez la moto idéale auprès de nos agences partenaires vérifiées. Réservation instantanée, paiement sécurisé, et facture générée automatiquement.</p>
                        
                        <div class="space-y-8">
                            <div class="flex items-start gap-6 group">
                                <div class="w-12 h-12 flex-shrink-0 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-xl font-bold text-orange-500 group-hover:bg-orange-600/20 group-hover:border-orange-500/40 transition-all">1</div>
                                <div>
                                    <h4 class="text-xl font-bold text-white mb-1">Choisissez votre moto</h4>
                                    <p class="text-gray-400 text-sm">Filtrez par ville, type de moto et dates. Comparez les avis des autres motards pour faire le meilleur choix.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-6 group">
                                <div class="w-12 h-12 flex-shrink-0 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-xl font-bold text-orange-500 group-hover:bg-orange-600/20 group-hover:border-orange-500/40 transition-all">2</div>
                                <div>
                                    <h4 class="text-xl font-bold text-white mb-1">Réservez & Payez en ligne</h4>
                                    <p class="text-gray-400 text-sm">Ajoutez vos accessoires (casque, top-case) et validez. Le prix est calculé en direct et le paiement est 100% sécurisé.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-6 group">
                                <div class="w-12 h-12 flex-shrink-0 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-xl font-bold text-orange-500 group-hover:bg-orange-600/20 group-hover:border-orange-500/40 transition-all">3</div>
                                <div>
                                    <h4 class="text-xl font-bold text-white mb-1">Récupérez & Roulez</h4>
                                    <p class="text-gray-400 text-sm">Présentez votre facture digitale à l'agence, enfilez votre casque, et partez explorer le Maroc l'esprit tranquille.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-t from-orange-600/20 to-transparent blur-3xl rounded-full"></div>
                        <div class="relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-[0_20px_50px_rgba(0,0,0,0.5)] hover:-translate-y-2 transition-all duration-500">
                            <div class="h-56 w-full rounded-2xl bg-gradient-to-br from-gray-800 to-gray-900 overflow-hidden relative mb-6">
                                <div class="absolute inset-0 flex items-center justify-center text-gray-700">
                                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 17h2l1-4h12l1 4h2M5 17v2a1 1 0 001 1h1a1 1 0 001-1v-2m8 0v2a1 1 0 001 1h1a1 1 0 001-1v-2M7 13l1.5-5h7L17 13M8 10.5h8"></path></svg>
                                </div>
                                <div class="absolute top-4 right-4 bg-green-500/20 text-green-400 text-xs font-bold px-3 py-1 rounded-full border border-green-500/30 backdrop-blur-sm">Disponible</div>
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-white">BMW R 1250 GS</h3>
                                    <p class="text-sm text-gray-500">2023 · Marrakech</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-orange-500">45€</p>
                                    <p class="text-xs text-gray-500">/ jour</p>
                                </div>
                            </div>
                            <div class="flex gap-1 mb-6">
                                <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span class="text-sm text-gray-400 ml-1">4.9/5 (24 avis)</span>
                            </div>
                            <button class="w-full bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold py-3 rounded-xl hover:shadow-[0_0_20px_rgba(234,88,12,0.4)] transition-all">Réserver cette moto</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION FONCTIONNALITÉS -->
        <section id="features" class="relative z-10 py-32 px-6 md:px-12 bg-gradient-to-b from-transparent to-gray-950/30">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-bold text-center mb-6 text-white tracking-tight">Un écosystème complet <br> <span class="text-gray-500">pour les motards.</span></h2>
                <p class="text-gray-400 text-center max-w-2xl mx-auto mb-20">Oubliez les multiples applications. MotoRaid centralise tout ce dont vous avez besoin pour profiter de la route.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-fr">
                    <div class="md:col-span-2 group relative bg-gradient-to-br from-orange-600/10 to-transparent border border-white/10 rounded-3xl p-6 overflow-hidden transition-all duration-500 hover:border-orange-500/40 flex flex-col justify-between min-h-[220px]">
                        <div class="relative z-10">
                            <div class="w-12 h-12 mb-4 bg-white/5 rounded-xl flex items-center justify-center border border-white/10 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Planificateur GPS Avancé</h3>
                            <p class="text-gray-400 max-w-md leading-relaxed text-sm">Tracez vos itinéraires de A à B, évitez les autoroutes pour privilégier les routes sinueuses, consultez la météo en direct et exportez en GPX vers votre GPS de guidon.</p>
                        </div>
                        <svg class="absolute -bottom-6 -right-6 w-40 h-40 text-orange-500/5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17h2l1-4h12l1 4h2M5 17v2a1 1 0 001 1h1a1 1 0 001-1v-2m8 0v2a1 1 0 001 1h1a1 1 0 001-1v-2M7 13l1.5-5h7L17 13M8 10.5h8"></path></svg>
                    </div>

                    <div class="group relative bg-white/5 border border-white/10 rounded-3xl p-6 overflow-hidden transition-all duration-500 hover:border-red-500/40 flex flex-col justify-between min-h-[220px]">
                        <div class="relative z-10">
                            <div class="w-12 h-12 mb-4 bg-red-500/10 rounded-xl flex items-center justify-center border border-red-500/20 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Sécurité SOS</h3>
                            <p class="text-gray-400 leading-relaxed text-sm">Alerte instantanée et envoi de votre position GPS à votre contact d'urgence en cas de problème.</p>
                        </div>
                    </div>

                    <div class="group relative bg-white/5 border border-white/10 rounded-3xl p-6 overflow-hidden transition-all duration-500 hover:border-orange-500/40 flex flex-col justify-between min-h-[220px]">
                        <div class="relative z-10">
                            <div class="w-12 h-12 mb-4 bg-white/5 rounded-xl flex items-center justify-center border border-white/10 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Ride Hubs</h3>
                            <p class="text-gray-400 leading-relaxed text-sm">Créez des groupes, discutez sur le chat intégré, et organisez des balades en meute.</p>
                        </div>
                    </div>

                    <div class="md:col-span-2 group relative bg-gradient-to-br from-blue-600/10 to-transparent border border-white/10 rounded-3xl p-6 overflow-hidden transition-all duration-500 hover:border-blue-500/40 flex flex-col justify-between min-h-[220px]">
                        <div class="relative z-10">
                            <div class="w-12 h-12 mb-4 bg-white/5 rounded-xl flex items-center justify-center border border-white/10 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Tracking Live</h3>
                            <p class="text-gray-400 max-w-md leading-relaxed text-sm">Partagez votre position en temps réel avec votre famille ou votre groupe. L'administrateur de la plateforme dispose d'une carte globale pour superviser tous les motards en ride.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION TARIFS -->
        <section id="pricing" class="relative z-10 py-32 px-6 md:px-12">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white tracking-tight mb-4">Un écosystème accessible à tous</h2>
                    <p class="text-gray-400 max-w-2xl mx-auto">Commencez gratuitement, puis passez à la vitesse supérieure pour débloquer la sécurité totale et des outils de pro.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-8 text-left">
                        <h3 class="text-2xl font-bold text-white mb-2">Rider (Gratuit)</h3>
                        <p class="text-gray-500 mb-6">Pour découvrir la communauté.</p>
                        <ul class="space-y-3 text-gray-400 text-sm mb-8">
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Calcul d'itinéraire basique</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Réseau Social (1 groupe)</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Location de motos (Commission 15%)</li>
                            <li class="flex items-center gap-2 text-gray-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Météo sur le trajet</li>
                            <li class="flex items-center gap-2 text-gray-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Export GPX Illimité</li>
                        </ul>
                        <Link v-if="canRegister" :href="route('register')" class="block w-full text-center bg-gray-800 text-white font-bold py-3 rounded-xl hover:bg-gray-700 transition-all">Créer un compte gratuit</Link>
                    </div>

                    <div class="relative bg-gradient-to-b from-orange-600/10 to-transparent border border-orange-500/40 rounded-3xl p-8 text-left shadow-[0_0_30px_rgba(234,88,12,0.2)]">
                        <div class="absolute top-0 right-0 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl rounded-tr-3xl">POPULAIRE</div>
                        <h3 class="text-2xl font-bold text-orange-400 mb-2">Premium (5€/mois)</h3>
                        <p class="text-gray-400 mb-6">Pour le motard qui veut rouler en toute sécurité.</p>
                        <ul class="space-y-3 text-gray-300 text-sm mb-8">
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tout ce qui est dans Rider</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Commission réduite à 8%</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Météo & Itinéraires sinueux</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Export GPX Illimité</li>
                            <li class="flex items-center gap-2 font-bold text-white"><svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lien Famille Live (SOS Sécurité)</li>
                        </ul>
                        <Link v-if="canRegister" :href="route('register')" class="block w-full text-center bg-gradient-to-r from-orange-600 to-amber-700 text-white font-extrabold py-3 rounded-xl hover:shadow-lg transition-all">Passer Premium</Link>
                    </div>
                </div>

                <div class="mt-12 text-center bg-white/5 border border-white/10 rounded-2xl p-6 max-w-2xl mx-auto">
                    <p class="text-gray-400 text-sm">🏍️ <span class="text-white font-semibold">Vous êtes une agence de location ?</span> Gérez votre flotte et touchez des centaines de motards. (Pack Pro à 49€/mois, commission 5%).</p>
                </div>
            </div>
        </section>

        <!-- SECTION TÉMOIGNAGES -->
        <section id="testimonials" class="relative z-10 py-24 px-6 md:px-12">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-bold text-center mb-16 text-white tracking-tight">Ils vivent la route <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">avec MotoRaid</span></h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="t in testimonials" :key="t.id" class="group relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-8 transition-all duration-500 hover:border-orange-500/30 hover:bg-white/[0.07] hover:shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-orange-500/5 rounded-bl-[3rem] rounded-tr-3xl group-hover:bg-orange-500/10 transition-colors"></div>
                        <div class="flex gap-1 mb-6 relative z-10">
                            <svg v-for="n in 5" :key="n" class="w-5 h-5" :class="n <= t.rating ? 'text-orange-500 drop-shadow-[0_0_5px_rgba(234,88,12,0.5)]' : 'text-gray-700'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <p class="text-gray-300 leading-relaxed mb-8 text-lg relative z-10">"{{ t.content }}"</p>
                        <div class="flex items-center gap-4 relative z-10 border-t border-white/5 pt-6">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500/30 to-amber-700/30 border border-white/10 flex items-center justify-center text-xl font-bold text-orange-400">{{ t.name.charAt(0) }}</div>
                            <div>
                                <p class="font-bold text-white">{{ t.name }}</p>
                                <p class="text-sm text-gray-500">{{ t.role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- NOUVELLE SECTION : FAQ -->
        <section id="faq" class="relative z-10 py-24 px-6 md:px-12 bg-gradient-to-b from-transparent to-gray-950/30">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-bold text-center mb-16 text-white tracking-tight">Questions <span class="text-orange-500">Fréquentes</span></h2>
                
                <div class="space-y-4">
                    <div v-for="faq in faqs" :key="faq.id" class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden transition-all">
                        <button @click="toggleFaq(faq.id)" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="font-bold text-white text-lg">{{ faq.q }}</span>
                            <svg class="w-6 h-6 text-orange-500 transition-transform duration-300 flex-shrink-0 ml-4" :class="{ 'rotate-45': openFaq === faq.id }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                        <transition 
                            enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="max-h-0 opacity-0"
                            enter-to-class="max-h-40 opacity-100"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="max-h-40 opacity-100"
                            leave-to-class="max-h-0 opacity-0"
                        >
                            <div v-show="openFaq === faq.id" class="overflow-hidden">
                                <p class="px-6 pb-6 text-gray-400 leading-relaxed">{{ faq.a }}</p>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </section>

        <!-- NOUVELLE SECTION : CTA FINAL (MOBILE & COMMUNAUTÉ) -->
        <section class="relative z-10 py-24 px-6 md:px-12">
            <div class="max-w-5xl mx-auto bg-gradient-to-br from-orange-600 to-amber-700 rounded-3xl p-12 md:p-16 text-center shadow-[0_20px_50px_rgba(234,88,12,0.3)] relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.2),_transparent_60%)]"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-4">L'aventure n'attend pas.</h2>
                    <p class="text-orange-50 text-lg mb-8 max-w-xl mx-auto">Rejoignez des milliers de motards au Maroc. Roulez malin, roulez en sécurité, vivez l'expérience MotoRaid.</p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link v-if="canRegister" :href="route('register')" class="group relative w-full sm:w-auto px-8 py-4 bg-white text-orange-600 font-extrabold rounded-2xl shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                            <span class="relative z-10">Créer un compte gratuit</span>
                        </Link>
                        <div class="flex gap-3">
                            <div class="flex items-center gap-2 px-5 py-4 bg-black/20 backdrop-blur-md border border-white/20 text-white font-semibold rounded-2xl cursor-not-allowed">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"></path></svg>
                                <div class="text-left text-xs">
                                    <p class="opacity-70">Bientôt sur</p>
                                    <p class="font-bold text-sm">App Store</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 px-5 py-4 bg-black/20 backdrop-blur-md border border-white/20 text-white font-semibold rounded-2xl cursor-not-allowed">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 01-.61-.92V2.734a1 1 0 01.609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.198l2.807 1.626a1 1 0 010 1.73l-2.808 1.626L15.392 12l2.306-2.491zM5.864 2.658L16.802 8.99l-2.302 2.303-8.636-8.635z"></path></svg>
                                <div class="text-left text-xs">
                                    <p class="opacity-70">Bientôt sur</p>
                                    <p class="font-bold text-sm">Google Play</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER IMMERSIF STYLISH -->
        <footer class="relative z-10 pt-20 overflow-hidden border-t border-white/5">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-orange-500/50 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 flex justify-center pointer-events-none">
                <h1 class="text-[12rem] md:text-[20rem] font-extrabold text-white/[0.02] tracking-tighter leading-none">MOTO RAID</h1>
            </div>

            <div class="relative max-w-7xl mx-auto px-6 md:px-12 pb-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-16">
                    <div class="md:col-span-1">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-700 rounded-xl flex items-center justify-center shadow-[0_0_20px_rgba(234,88,12,0.4)]">
                                <span class="text-xl">🏍️</span>
                            </div>
                            <span class="text-2xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">MotoRaid</span>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed">L'écosystème ultime pour les motards au Maroc. Roulez, explorez et sécurisez vos aventures.</p>
                        <div class="flex gap-3 mt-6">
                            <a href="#" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-orange-600/20 hover:border-orange-500/40 hover:-translate-y-1 transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path></svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-orange-600/20 hover:border-orange-500/40 hover:-translate-y-1 transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-wider">Plateforme</h4>
                        <ul class="space-y-4 text-gray-400 text-sm">
                            <li><a href="#location" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Location de motos</a></li>
                            <li><a href="#features" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Planificateur GPS</a></li>
                            <li><a href="#features" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Réseau Social</a></li>
                            <li><a href="#features" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Tracking & SOS</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-wider">Ressources</h4>
                        <ul class="space-y-4 text-gray-400 text-sm">
                            <li><a href="#faq" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">FAQ (Aide)</a></li>
                            <li><a href="#" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Devenir Partenaire</a></li>
                            <li><a href="#pricing" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Tarifs & Abonnements</a></li>
                            <li><a href="#" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Blog Moto</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-6 text-sm uppercase tracking-wider">Légal</h4>
                        <ul class="space-y-4 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Conditions d'utilisation</a></li>
                            <li><a href="#" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Politique de confidentialité</a></li>
                            <li><a href="#" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Mentions légales</a></li>
                            <li><a href="#" class="hover:text-orange-400 hover:translate-x-1 inline-block transition-all">Cookies</a></li>
                        </ul>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm">
                    <p>© 2024 MotoRaid. Tous droits réservés.</p>
                    <p class="mt-4 md:mt-0">Conçu avec ❤️ au Maroc pour les motards.</p>
                </div>
            </div>
        </footer>

        <!-- Assistant IA Flottant -->
        <AiAssistant />

    </div>
</template>

<!-- CSS Global pour le scroll fluide -->
<style>
html {
  scroll-behavior: smooth;
}
</style>

<style scoped>
@keyframes blob { 0%, 100% { transform: translate(0, 0) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } }
.animate-blob { animation: blob 12s infinite ease-in-out; }

@keyframes shine { to { background-position: 200% center; } }
.animate-shine { animation: shine 4s linear infinite; }

@keyframes lightSweep { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
.animate-light-sweep { animation: lightSweep 5s infinite linear; }

@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in-up { opacity: 0; animation: fadeInUp 1s ease-out forwards; }

@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in-down { opacity: 0; animation: fadeInDown 0.8s ease-out forwards; }

@keyframes pingSlow { 0% { transform: scale(1); opacity: 1; } 75%, 100% { transform: scale(3); opacity: 0; } }
.animate-ping-slow { animation: pingSlow 2s cubic-bezier(0, 0, 0.2, 1) infinite; }

@keyframes slideIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.animate-slide-in { animation: slideIn 0.5s ease-out 1s forwards; opacity: 0; }
</style>