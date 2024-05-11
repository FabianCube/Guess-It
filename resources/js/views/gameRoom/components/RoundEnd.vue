<template>
    <div v-if="status === 1" class="round-end">
        <img src="/storage/fin_de_ronda.svg" :key="timeLeft" />
    </div>
    <div v-else-if="status === 2" class="blur-background">
        <div class="round-end table-container">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <h3 class="m-0 word-played-color">Palabra Jugada</h3>
                <h2 class="m-0 word-played-color">{{ playedWord }}</h2>
            </div>
            <div class="d-flex flex-column table">
                <div class="d-flex justify-content-between">
                    <p class="players-font">Jugador</p>
                    <p class="players-font">Puntos</p>
                </div>
                <div class="d-flex justify-content-between align-items-center my-2 list-name etiqueta" v-for="(player, index) in sortedPlayers" :key="player.uuid">
                    <div class="d-flex align-items-center">
                        <p class="mb-0 list-position" :style="{ color: getColorByIndex(index) }">{{ index + 1 }}</p>
                        <div class="mx-3 avatar" :style="{ backgroundImage: 'url(' + player.avatar + ')' }">
                            <!-- <img :src="player.avatar" alt="avatar"> -->
                        </div>
                        <p class="mb-0" :style="{color:  player.color }">{{ player.nickname }}</p>
                    </div>
                    <p class="mb-0">{{ player.points }}</p>
                </div>
            </div>
        </div>
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

const getColorByIndex = (index) => {
    if (index === 0) return 'gold';
    if (index === 1) return 'silver';
    if (index === 2) return '#cd7f32';
    return 'grey';
};

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
    audio.volume = 0.05;
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

@import './../style/roundEnd.css';

</style>