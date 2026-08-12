<template>
    <AuthenticatedLayout title="Tracking & SOS">
        <div class="relative h-[calc(100vh-5rem)] bg-gray-950 p-6">
            <div class="relative h-full w-full rounded-3xl overflow-hidden border border-white/10">
                <div ref="mapContainer" class="absolute inset-0 z-0"></div>
                
                <div class="absolute top-6 left-6 z-[1000] bg-gray-900/70 backdrop-blur-xl border border-white/10 rounded-2xl p-4 shadow-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-white font-bold">Tracking Actif</span>
                    </div>
                    <p v-if="activeTrip" class="text-gray-400 text-xs">Trajet: {{ activeTrip.title }} ({{ activeTrip.distance_km }} km)</p>
                    <p v-else class="text-gray-400 text-xs">Mode libre (Aucun itinéraire chargé)</p>
                </div>

                <button 
                    @click="triggerSos"
                    class="absolute bottom-8 right-8 z-[1000] w-24 h-24 rounded-full bg-red-600 border-4 border-red-900/50 flex flex-col items-center justify-center text-white font-extrabold shadow-[0_0_30px_rgba(220,38,38,0.6)] hover:scale-110 transition-transform active:scale-95"
                >
                    <span class="text-2xl animate-pulse">SOS</span>
                    <span class="text-[10px] mt-1">URGENCE</span>
                </button>
            </div>

            <transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-10" enter-to-class="opacity-100 translate-y-0">
                <div v-if="sosSent" class="absolute top-6 right-6 z-[2000] bg-red-600/20 backdrop-blur-xl border border-red-500/50 rounded-2xl p-4 shadow-2xl">
                    <p class="text-white font-bold">Alerte SOS Envoyée</p>
                    <p class="text-red-200 text-xs">L'administrateur a été notifié.</p>
                </div>
            </transition>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    activeTrip: Object
});

const mapContainer = ref(null);
let map = null;
let myMarker = null;
const sosSent = ref(false);
let trackingInterval = null;

const myPosition = ref({
    lat: props.activeTrip?.route_data ? props.activeTrip.route_data[0][0] : 31.6295,
    lng: props.activeTrip?.route_data ? props.activeTrip.route_data[0][1] : -7.9811
});

onMounted(() => {
    map = L.map(mapContainer.value, { zoomControl: false }).setView([myPosition.value.lat, myPosition.value.lng], 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    if (props.activeTrip && props.activeTrip.route_data) {
        const routeData = props.activeTrip.route_data;
        
        L.polyline(routeData, { 
            color: '#ea580c', 
            weight: 6, 
            opacity: 0.8,
            dashArray: '10, 15',
            className: 'animated-route'
        }).addTo(map);

        let startIcon = L.divIcon({ html: `<div style="background-color: #22c55e; width: 20px; height: 20px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 15px #22c55e;"></div>`, iconSize: [20, 20], iconAnchor: [10, 10] });
        L.marker(routeData[0], { icon: startIcon }).addTo(map).bindPopup('Départ');

        let endIcon = L.divIcon({ html: `<div style="background-color: #ef4444; width: 20px; height: 20px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 15px #ef4444;"></div>`, iconSize: [20, 20], iconAnchor: [10, 10] });
        L.marker(routeData[routeData.length - 1], { icon: endIcon }).addTo(map).bindPopup('Arrivée');

        map.fitBounds(routeData, { padding: [50, 50] });
    }

    let riderIcon = L.divIcon({ 
        html: `<div style="background-color: #3b82f6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 15px #3b82f6;"></div>`, 
        iconSize: [20, 20], iconAnchor: [10, 10] 
    });
    myMarker = L.marker([myPosition.value.lat, myPosition.value.lng], { icon: riderIcon }).addTo(map);

    startTracking();
});

onUnmounted(() => clearInterval(trackingInterval));

const startTracking = () => {
    trackingInterval = setInterval(() => {
        myPosition.value.lat += (Math.random() - 0.5) * 0.001;
        myPosition.value.lng += (Math.random() - 0.5) * 0.001;
        
        myMarker.setLatLng([myPosition.value.lat, myPosition.value.lng]);

        fetch('/tracking/update', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 
                'Accept': 'application/json' 
            },
            body: JSON.stringify({ latitude: myPosition.value.lat, longitude: myPosition.value.lng, speed: 90 })
        });
    }, 5000);
};

const triggerSos = async () => {
    if (!confirm("Confirmer l'envoi d'une alerte SOS ?")) return;
    try {
        await fetch('/tracking/sos', { 
            method: 'POST', 
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 
                'Accept': 'application/json' 
            } 
        });
        sosSent.value = true;
        setTimeout(() => sosSent.value = false, 5000);
    } catch (e) { alert("Erreur SOS."); }
};
</script>

<style scoped>
.leaflet-container { background-color: #030712; }

:deep(.animated-route) {
    animation: dash 1.5s linear infinite;
}
@keyframes dash {
    to { stroke-dashoffset: -25; }
}
</style>