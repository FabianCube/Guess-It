<template>
    <div id="stats-container">
        <div class="stats ">
            <div class="head-stat">
                <div class="image-stat">
                    <img src="/storage/icons/stat-1.svg" alt="">
                </div>
                <h3>TOTAL PUNTOS OBTENIDOS</h3>
            </div>
            <h3>{{ totalPoints }} pts.</h3>
        </div>
        <div class="stats ">
            <div class="head-stat">
                <div class="image-stat">
                    <img src="/storage/icons/stat-2.svg" alt="">
                </div>
                <h3>TOTAL PARTIDAS GANADAS</h3>
            </div>
            <h3>{{ totalWonGames }}</h3>
        </div>
        <div class="stats ">
            <div class="head-stat">
                <div class="image-stat">
                    <img src="/storage/icons/stat-3.svg" alt="">
                </div>
                <h3>PUNTOS MEDIOS POR PATIDA</h3>
            </div>
            <h3>{{ averagePoints }} pts.</h3>
        </div>
        <div class="stats ">
            <div class="head-stat">
                <div class="image-stat">
                    <img src="/storage/icons/stat-4.svg" alt="">
                </div>
                <h3>TOTAL JUEGOS JUGADOS</h3>
            </div>
            <h3>{{ totalGamesPlayed }}</h3>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';

const props = defineProps([ 'user', 'historyData' ]);
const totalPoints = ref(0);
const totalWonGames = ref(0);
const averagePoints = ref(0);
const totalGamesPlayed = ref(0);

// al cargar el componente calculamos las stats de el user
onMounted(() => {
    calculateStats();
})

// calcular stats del user
const calculateStats = () => {
    // obtenemos los datos que nos llegan por props del padre.
    let data = props.historyData;
    let totalPositions;

    // calculamos los datos que nos interesan.
    for(let i = 0; i < data.length; i++)
    {
        totalPoints.value += data[i].user_points;
        totalPositions += data.user_position;

        if(data.user_position == 1)
        {
            totalWonGames.value++
        }
    }

    // asignamos los datos a las variables que usaremos en el template.
    averagePoints.value = totalPoints.value / data.length;
    totalGamesPlayed.value = data.length;
}

</script>

<style scoped>

@import './../style/accountStats.css';

</style>