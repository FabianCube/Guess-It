<template>
    <div v-if="status === 1" class="round-end">
        <img src="/storage/fin_de_ronda.svg" :key="timeLeft" />
    </div>
    <div v-else-if="status === 2" class="round-end">
        <table>
            <thead>
                <tr>
                    <th colspan="2">Palabra Jugada: {{ playedWord }}</th>
                </tr>
                <tr>
                    <th>Jugador</th>
                    <th>Puntos</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="player in sortedPlayers" :key="player.uuid">
                    <td>{{ player.nickname }}</td>
                    <td>{{ player.points }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-else-if="status === 3" class="round-end">
        <img src="/storage/preparate.svg" :key="timeLeft" />
    </div>
</template>

<script setup>
import { ref, computed, defineProps, watch } from 'vue';

const props = defineProps(['roundEnd', 'players', 'playedWord']);

const status = ref(0);
const roundDurations = { end: 2000, table: 5000, prepare: 2000 };

const sortedPlayers = computed(() => {
    return [...props.players].sort((a, b) => b.points - a.points);
});

const changeStatus = (newStatus, duration) => {
    status.value = newStatus;
    setTimeout(() => {
        if (newStatus === 1) {
            changeStatus(2, roundDurations.table);
        } else if (newStatus === 2) {
            changeStatus(3, roundDurations.prepare);
        } else {
            status.value = 0;
        }
    }, duration);
};

const playSound = (soundPath) => {
    const audio = new Audio(soundPath);
    audio.volume = 0.1;
    audio.play();
};

watch(() => props.roundEnd, (newValue) => {
    if (newValue) {
        playSound('/storage/sounds/final_ronda.mp3');
        changeStatus(1, roundDurations.end);
    }
});




</script>
<style scoped>
.round-end {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    font-size: 4em;
    padding: 20px;
    border-radius: 10px;
}
</style>