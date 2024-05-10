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

onMounted(() => {
    calculateStats();
})

const calculateStats = () => {
    console.log("Props in account status: " + props.historyData);

    let data = props.historyData;
    let totalPositions;

    for(let i = 0; i < data.length; i++)
    {
        totalPoints.value += data[i].user_points;
        totalPositions += data.user_position;

        if(data.user_position == 1)
        {
            totalWonGames.value++
        }
    }

    averagePoints.value = totalPoints.value / data.length;
    totalGamesPlayed.value = data.length;
}

</script>

<style scoped>
#stats-container
{
    width: 100%;
    margin-top: 20px;
}
h3
{
    font-size: 12px;
    color: white;
    line-height: 35px;
    margin: 0;
}

.stats
{
    height: 35px;
    border-radius: 6px;
    background-color: #FD6F5A;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-flow: row;
    padding-right: 20px;
}

.head-stat
{
    display: flex;
    width: 250px;
}

@media (max-width: 820px)
{
    .stats>h3
    {
        text-align: right;
        width: 50px;
        font-size: .7rem;
    }

    .head-stat>h3
    {
        font-size: .7rem;
    }
}

.stats:nth-child(1)
{
    background-color: #FD6F5A;
}
.stats:nth-child(2)
{
    background-color: #FF9447;
}
.stats:nth-child(3)
{
    background-color: #2993BA;
}
.stats:nth-child(4)
{
    background-color: #A05FD3;
}

.image-stat
{
    display: flex;
    width: 40px;
    justify-content: center;
    align-items: center;
}

ul
{
    list-style: none;
}
</style>