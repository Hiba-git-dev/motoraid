<template>
    <AuthenticatedLayout title="Supervision Live">
        <div class="relative h-[calc(100vh-5rem)] bg-gray-950 p-6">
            <div class="relative h-full w-full rounded-3xl overflow-hidden border border-white/10">
                <div ref="mapContainer" class="absolute inset-0 z-0"></div>
                
                <div class="absolute top-6 left-6 z-[1000] bg-gray-900/70 backdrop-blur-xl border border-white/10 rounded-2xl p-4 shadow-lg flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-white font-bold">Supervision Globale</span>
                    </div>
                    <div class="h-6 w-px bg-white/10"></div>
                    <span class="text-gray-400 text-sm">{{ activeRiders.length }} motard(s) en ride</span>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const mapContainer = ref(null);
let map = null;
let markersGroup = L.layerGroup();
const activeRiders = ref([]);
let pollingInterval = null;

onMounted(() => {
    map = L.map(mapContainer.value, { zoomControl: false }).setView([31.6295, -7.9811], 6);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
    L.control.zoom({ position: 'bottomright' }).addTo(map);
    markersGroup.addTo(map);

    fetchRiders();
    pollingInterval = setInterval(fetchRiders, 5000); // Met à jour la carte toutes les 5 secondes
});

onUnmounted(() => clearInterval(pollingInterval));

const fetchRiders = async () => {
    try {
        const res = await fetch('/tracking/riders', { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        activeRiders.value = data;
        
        markersGroup.clearLayers();

        data.forEach(rider => {
            let iconColor = rider.status === 'sos' ? '#dc2626' : '#22c55e'; // Rouge pour SOS, Vert pour actif
            let iconHtml = `<div style="background-color: ${iconColor}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.8); box-shadow: 0 0 15px ${iconColor};"></div>`;
            
            let icon = L.divIcon({
                html: iconHtml,
                className: 'custom-marker',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            L.marker([rider.latitude, rider.longitude], { icon })
                .bindPopup(`<b>${rider.user.name}</b><br>Statut: ${rider.status.toUpperCase()}<br>Vitesse: ${rider.speed} km/h`)
                .addTo(markersGroup);
        });
    } catch (error) {
        console.error("Erreur de récupération des positions:", error);
    }
};
</script>

<style scoped>
.leaflet-container { background-color: #030712; }
</style>