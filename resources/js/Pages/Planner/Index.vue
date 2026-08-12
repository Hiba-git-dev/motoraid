<template>
    <AuthenticatedLayout title="Planificateur GPS">
        <div class="relative flex flex-col lg:flex-row h-[calc(100vh-5rem)] gap-6 p-6 bg-gray-950">
            
            <!-- PANNEAU DE CONTRÔLE -->
            <div class="lg:w-96 flex-shrink-0 bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-6 shadow-[0_8px_32px_rgba(0,0,0,0.5)] flex flex-col gap-6 z-10 overflow-y-auto">
                
                <div>
                    <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">Itinéraire Pro</h2>
                    <p class="text-gray-400 text-sm mt-1">Tracez votre aventure au Maroc.</p>
                </div>

                <form @submit.prevent="calculateRoute" class="flex flex-col gap-4">
                    <div class="space-y-4">
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-semibold uppercase tracking-wider text-gray-300 flex items-center gap-2">
                                <span class="w-3 h-3 bg-green-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.8)]"></span>
                                Départ
                            </label>
                            <input v-model="pointA" type="text" placeholder="Ex: Marrakech" class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/50">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-semibold uppercase tracking-wider text-gray-300 flex items-center gap-2">
                                <span class="w-3 h-3 bg-red-500 rounded-full shadow-[0_0_10px_rgba(239,68,68,0.8)]"></span>
                                Arrivée
                            </label>
                            <input v-model="pointB" type="text" placeholder="Ex: Ouarzazate" class="w-full bg-gray-900/60 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500/50">
                        </div>
                        <p class="text-xs text-gray-500 text-center">💡 Vous pouvez taper les villes ou cliquer directement sur la carte.</p>
                    </div>

                    <label class="flex items-center gap-3 bg-gray-900/40 p-3 rounded-xl border border-white/5 cursor-pointer">
                        <input type="checkbox" v-model="findAlternatives" class="w-4 h-4 accent-orange-600">
                        <span class="text-sm text-gray-300">🛣️ Proposer des routes alternatives</span>
                    </label>

                    <button type="submit" :disabled="isCalculating" class="w-full bg-gradient-to-r from-orange-600 to-amber-700 text-white font-bold py-3.5 rounded-xl hover:shadow-lg transition-all disabled:opacity-50 flex justify-center items-center gap-2">
                        <svg v-if="isCalculating" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        {{ isCalculating ? 'Calcul...' : 'Calculer l\'itinéraire' }}
                    </button>
                </form>

                <!-- STATS & ACTIONS -->
                <div v-if="distance" class="bg-gray-900/40 border border-white/5 rounded-2xl p-4 space-y-3 animate-fade-in-up">
                    <div class="flex justify-between"><span class="text-gray-400 text-sm">Distance</span><span class="text-white font-bold">{{ distance }} km</span></div>
                    <div class="flex justify-between"><span class="text-gray-400 text-sm">Durée</span><span class="text-white font-bold">{{ duration }}</span></div>
                    
                    <button @click="startRide" class="w-full mt-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-extrabold py-3 rounded-xl hover:shadow-[0_0_20px_rgba(16,185,129,0.4)] transition-all flex items-center justify-center gap-2 uppercase tracking-wide">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Démarrer le Ride
                    </button>

                    <div class="flex flex-col gap-2 pt-2 border-t border-white/5 mt-2">
                        <button @click="fetchWeather" class="w-full bg-blue-600/20 border border-blue-500/30 text-blue-400 font-semibold py-2 rounded-xl hover:bg-blue-600/40 transition-all text-sm flex items-center justify-center gap-2">
                            <svg v-if="isFetchingWeather" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ isFetchingWeather ? 'Récupération météo...' : '🌦️ Voir la Météo' }}
                        </button>

                        <!-- BOUTON TOGGLE HÉBERGEMENTS -->
                        <label class="flex items-center gap-3 bg-gray-900/40 p-3 rounded-xl border border-white/5 cursor-pointer mt-2">
                            <input type="checkbox" v-model="showAccommodations" class="w-4 h-4 accent-orange-600">
                            <span class="text-sm text-gray-300">🏨 Afficher les Hébergements (Hôtels, Campings)</span>
                        </label>

                        <div class="flex gap-2 mt-2">
                            <button @click="exportGpx" class="flex-1 border border-orange-500/30 text-orange-400 font-semibold py-2 rounded-xl hover:bg-orange-500/10 text-sm">GPX</button>
                            <button @click="openSaveModal" class="flex-1 bg-white/5 border border-white/10 text-white font-semibold py-2 rounded-xl hover:bg-white/10 text-sm">Sauvegarder</button>
                        </div>
                    </div>
                </div>

                <!-- MES TRAJETS SAUVEGARDÉS -->
                <div class="mt-auto">
                    <h3 class="text-sm font-bold text-gray-400 mb-3 uppercase tracking-wider">Mes Trajets</h3>
                    <div class="space-y-2">
                        <div v-for="trip in myTrips" :key="trip.id" @click="loadSavedTrip(trip)" class="bg-gray-900/40 border border-white/5 rounded-xl p-3 cursor-pointer hover:border-orange-500/30 transition-all flex justify-between items-center">
                            <div>
                                <p class="text-white text-sm font-medium">{{ trip.title }}</p>
                                <p class="text-gray-500 text-xs">{{ trip.distance_km }} km</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                        <p v-if="myTrips.length === 0" class="text-gray-600 text-xs text-center py-4">Aucun trajet sauvegardé.</p>
                    </div>
                </div>
            </div>

            <!-- CARTE -->
            <div class="flex-1 relative rounded-3xl overflow-hidden border border-white/10">
                <div ref="mapContainer" class="absolute inset-0 z-0 cursor-crosshair"></div>
            </div>
        </div>

        <!-- MODALE SAUVEGARDE -->
        <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100">
            <div v-if="isSaveModalOpen" class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4" @click.self="isSaveModalOpen = false">
                <div class="bg-gray-900/80 border border-white/10 rounded-3xl p-8 w-full max-w-md">
                    <h3 class="text-2xl font-bold text-white mb-6">Sauvegarder ce trajet</h3>
                    <form @submit.prevent="submitSave">
                        <input v-model="saveForm.title" type="text" placeholder="Nom du trajet (Ex: Balade Atlas)" class="w-full bg-gray-950/60 border border-white/10 rounded-xl px-4 py-3 text-white mb-4" required>
                        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-700 text-white font-bold py-3 rounded-xl">Sauvegarder</button>
                    </form>
                </div>
            </div>
        </transition>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps({ myTrips: Array });

const mapContainer = ref(null);
let map = null;
let currentRoute = null;
let currentRouteData = [];
let weatherLayer = L.layerGroup();
let markersLayer = L.layerGroup();

const pointA = ref(''); const pointB = ref('');
const distance = ref(null); const duration = ref(null);
const isCalculating = ref(false);
const isFetchingWeather = ref(false);
const findAlternatives = ref(false);
const isSaveModalOpen = ref(false);

let clickedStart = null;
let clickedEnd = null;

// Variables pour les Hébergements
let accommodationLayer = ref(L.layerGroup());
const showAccommodations = ref(false);
const accommodations = ref([]);

const saveForm = useForm({ title: '', distance_km: '', estimated_duration: '', route_data: [] });

watch(pointA, (val) => { if (!val.startsWith('Coord')) clickedStart = null; });
watch(pointB, (val) => { if (!val.startsWith('Coord')) clickedEnd = null; });

onMounted(() => {
    map = L.map(mapContainer.value, { zoomControl: false }).setView([31.6295, -7.9811], 6);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
    L.control.zoom({ position: 'bottomright' }).addTo(map);
    weatherLayer.addTo(map);
    markersLayer.addTo(map);
    accommodationLayer.value.addTo(map); // Ajout du calque hébergements

    map.on('click', (e) => {
        if (!clickedStart || (clickedStart && clickedEnd)) {
            clickedStart = e.latlng;
            clickedEnd = null;
            pointA.value = `Coord: ${e.latlng.lat.toFixed(4)}, ${e.latlng.lng.toFixed(4)}`;
            pointB.value = '';
        } else if (clickedStart && !clickedEnd) {
            clickedEnd = e.latlng;
            pointB.value = `Coord: ${e.latlng.lat.toFixed(4)}, ${e.latlng.lng.toFixed(4)}`;
        }
        updateMarkers();
    });

    // Charger les hébergements au démarrage
    fetchAccommodations();
});

// --- FONCTIONS HÉBERGEMENTS ---
const fetchAccommodations = async () => {
    try {
        const res = await fetch('/accommodations', { headers: { 'Accept': 'application/json' } });
        accommodations.value = await res.json();
        renderAccommodations();
    } catch (e) { console.error("Err Hébergements", e); }
};

const renderAccommodations = () => {
    accommodationLayer.value.clearLayers();
    if (!showAccommodations.value) return;

    const icons = { hotel: '🏨', riad: '🕌', camping: '⛺', auberge: '🏠' };

    accommodations.value.forEach(acc => {
        let emoji = icons[acc.type] || '📍';
        let borderColor = acc.is_sponsored ? '#fbbf24' : '#9ca3af'; // Or pour les sponsors, Gris pour les normaux
        
        let customIcon = L.divIcon({
            html: `<div style="font-size: 24px; background: rgba(0,0,0,0.7); border-radius: 50%; padding: 4px; border: 2px solid ${borderColor}; box-shadow: 0 0 8px ${borderColor};">${emoji}</div>`,
            iconSize: [32, 32], iconAnchor: [16, 16]
        });

        L.marker([acc.latitude, acc.longitude], { icon: customIcon })
          .bindPopup(`<b>${acc.name}</b><br>Type: ${acc.type}<br>Prix: ${acc.price_per_night || 'N/A'}<br><a href="#" style="color:#f97316;">Voir les détails</a>`)
          .addTo(accommodationLayer.value);
    });
};

// Watcher pour afficher/cacher les hébergements quand on coche la case
watch(showAccommodations, () => renderAccommodations());

const updateMarkers = () => {
    markersLayer.clearLayers();
    if (clickedStart) {
        let icon = L.divIcon({ html: `<div style="background-color: #22c55e; width: 20px; height: 20px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 15px #22c55e;"></div>`, iconSize: [20, 20], iconAnchor: [10, 10] });
        L.marker(clickedStart, { icon }).addTo(markersLayer).bindPopup('Départ');
    }
    if (clickedEnd) {
        let icon = L.divIcon({ html: `<div style="background-color: #ef4444; width: 20px; height: 20px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 15px #ef4444;"></div>`, iconSize: [20, 20], iconAnchor: [10, 10] });
        L.marker(clickedEnd, { icon }).addTo(markersLayer).bindPopup('Arrivée');
    }
};

const geocodeCity = async (name) => {
    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(name)}, Morocco`);
    const data = await res.json();
    return data[0] ? { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon) } : null;
};

const calculateRoute = async () => {
    if (isCalculating.value || !pointA.value || !pointB.value) return;

    isCalculating.value = true;
    weatherLayer.clearLayers();
    markersLayer.clearLayers();

    try {
        let start, end;

        if (pointA.value.startsWith('Coord')) {
            start = { lat: clickedStart.lat, lon: clickedStart.lng };
        } else {
            start = await geocodeCity(pointA.value);
        }

        if (pointB.value.startsWith('Coord')) {
            end = { lat: clickedEnd.lat, lon: clickedEnd.lng };
        } else {
            end = await geocodeCity(pointB.value);
        }

        if (!start || !end) { 
            alert("Ville introuvable. Vérifiez l'orthographe."); 
            isCalculating.value = false; 
            return; 
        }

        if (!pointA.value.startsWith('Coord')) clickedStart = { lat: start.lat, lng: start.lon };
        if (!pointB.value.startsWith('Coord')) clickedEnd = { lat: end.lat, lng: end.lon };
        updateMarkers();
        
        const altParam = findAlternatives.value ? '&alternatives=true' : '';
        const res = await fetch(`https://router.project-osrm.org/route/v1/driving/${start.lon},${start.lat};${end.lon},${end.lat}?overview=full&geometries=geojson${altParam}`);
        const data = await res.json();
        
        if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
            const r = data.routes[0];
            distance.value = (r.distance / 1000).toFixed(1);
            duration.value = `${Math.floor(r.duration / 3600)}h ${Math.floor((r.duration % 3600) / 60)}min`;
            
            currentRouteData = r.geometry.coordinates.map(c => [c[1], c[0]]);
            
            if (currentRoute) map.removeLayer(currentRoute);
            currentRoute = L.polyline(currentRouteData, { 
                color: '#ea580c', weight: 6, opacity: 0.8, dashArray: '10, 15', className: 'animated-route'
            }).addTo(map);
            
            map.fitBounds(currentRoute.getBounds(), { padding: [50, 50] });
        } else {
            const reason = data.message || "Code: " + data.code;
            alert("Échec du routage. Raison technique : " + reason);
        }
    } catch (e) {
        console.error("Erreur réseau ou routage:", e);
        alert("Une erreur réseau est survenue. Vérifiez votre connexion.");
    } finally {
        isCalculating.value = false;
    }
};

const startRide = () => {
    saveForm.title = pointA.value ? (pointA.value + " -> " + pointB.value) : "Trajet Carte";
    saveForm.distance_km = distance.value;
    saveForm.estimated_duration = parseInt(duration.value.match(/\d+h/)[0]) * 60 + parseInt(duration.value.match(/(\d+)min/)?.[1] || 0);
    saveForm.route_data = currentRouteData;

    saveForm.post(route('trips.start'), {
        preserveScroll: true
    });
};

const fetchWeather = async () => {
    if (!currentRouteData || currentRouteData.length === 0) return;
    
    isFetchingWeather.value = true;
    weatherLayer.clearLayers();

    const startCoord = currentRouteData[0];
    const midCoord = currentRouteData[Math.floor(currentRouteData.length / 2)];
    const endCoord = currentRouteData[currentRouteData.length - 1];
    const pointsToCheck = [startCoord, midCoord, endCoord];

    const weatherEmojis = {
        0: '☀️', 1: '🌤️', 2: '⛅', 3: '☁️', 
        45: '🌫️', 48: '🌫️', 
        51: '🌦️', 53: '🌦️', 55: '🌧️', 56: '🌧️', 57: '🌧️',
        61: '🌧️', 63: '🌧️', 65: '⛈️', 66: '🌨️', 67: '🌨️',
        71: '🌨️', 73: '❄️', 75: '❄️', 77: '❄️',
        80: '🌦️', 81: '🌧️', 82: '⛈️', 85: '🌨️', 86: '🌨️',
        95: '⛈️', 96: '⛈️', 99: '⛈️'
    };

    let successCount = 0;

    for (const point of pointsToCheck) {
        try {
            const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${point[0]}&longitude=${point[1]}&current_weather=true`);
            if (!res.ok) continue; 
            
            const data = await res.json();
            
            if (data && data.current_weather) {
                const temp = data.current_weather.temperature;
                const code = data.current_weather.weathercode;
                const emoji = weatherEmojis[code] || '🌡️';

                let customIcon = L.divIcon({
                    html: `<div style="font-size: 32px; background: rgba(0,0,0,0.6); border-radius: 50%; padding: 4px; border: 2px solid #3b82f6; box-shadow: 0 0 10px rgba(59,130,246,0.5);">${emoji}</div>`,
                    className: 'weather-marker',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });

                L.marker(point, { icon: customIcon })
                  .bindPopup(`<b>Météo sur le trajet</b><br>Température: ${temp}°C`)
                  .addTo(weatherLayer);
                  
                successCount++;
            }
        } catch (err) {
            console.error("Erreur sur un point météo:", err);
        }
    }

    if (successCount === 0) {
        alert("Aucune donnée météo n'a pu être récupérée. Vérifiez votre connexion internet.");
    }

    isFetchingWeather.value = false;
};

const openSaveModal = () => {
    saveForm.distance_km = distance.value;
    saveForm.estimated_duration = parseInt(duration.value.match(/\d+h/)[0]) * 60 + parseInt(duration.value.match(/(\d+)min/)?.[1] || 0);
    saveForm.route_data = currentRouteData;
    isSaveModalOpen.value = true;
};

const submitSave = () => {
    saveForm.post(route('trips.store'), {
        preserveScroll: true,
        onSuccess: () => { isSaveModalOpen.value = false; saveForm.reset('title'); }
    });
};

const loadSavedTrip = (trip) => {
    if (currentRoute) map.removeLayer(currentRoute);
    weatherLayer.clearLayers();
    markersLayer.clearLayers();
    
    currentRouteData = trip.route_data;
    clickedStart = currentRouteData[0];
    clickedEnd = currentRouteData[currentRouteData.length - 1];
    pointA.value = `Coord: ${clickedStart[0]}, ${clickedStart[1]}`;
    pointB.value = `Coord: ${clickedEnd[0]}, ${clickedEnd[1]}`;
    updateMarkers();

    currentRoute = L.polyline(currentRouteData, { color: '#ea580c', weight: 6, opacity: 0.8, dashArray: '10, 15', className: 'animated-route' }).addTo(map);
    map.fitBounds(currentRoute.getBounds(), { padding: [50, 50] });
    distance.value = trip.distance_km;
    duration.value = `${Math.floor(trip.estimated_duration / 60)}h ${trip.estimated_duration % 60}min`;
};

const exportGpx = () => {
    if (!currentRoute) return;
    let gpx = `<?xml version="1.0" encoding="UTF-8"?>\n<gpx version="1.1"><trk><name>MotoRaid</name><trkseg>\n`;
    currentRouteData.forEach(p => gpx += `<trkpt lat="${p[0]}" lon="${p[1]}"></trkpt>\n`);
    gpx += `</trkseg></trk></gpx>`;
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([gpx], { type: 'application/gpx+xml' }));
    a.download = 'motoraid-trip.gpx'; a.click();
};
</script>

<style scoped>
.leaflet-container { background-color: #030712; }

:deep(.animated-route) {
    animation: dash 1.5s linear infinite;
}
@keyframes dash {
    to {
        stroke-dashoffset: -25;
    }
}
</style>