<!-- * Component for info players -->

<template>
    <div class="p-2 flex flex-column justify-content-start align-items-center players-container">
        <div v-for="(player, index) in sortedPlayers" :key="index" class="p-2 flex flex-column align-items-center player"
            :style="{borderColor: player.color}">
            <div class="p-0 m-0 avatar">
                <img :src="player.avatar" alt="avatar">
            </div>
            <p class="name-player-info">{{ player.nickname}}</p>
            <p class="points-player-info">{{ player.points }} Pts.</p>
        </div>
    </div>
</template>

<script setup>
import { defineProps, watch , computed } from 'vue';

const props = defineProps(['players']);

watch(() => props.players, (playersChange) => {
  players.value = playersChange;
});

// Crear un array ordenado usando una propiedad computada
const sortedPlayers = computed(() => {
  // Copiar el array original para no modificar `props.players`
  const playersCopy = [...props.players];
  // Ordenar por puntos en orden descendente
  return playersCopy.sort((a, b) => b.points - a.points);
});

console.log(props.players);
</script>

<style scoped>

.name-player-info
{
    font-family: 'Lilita One', sans-serif;
    font-size: 12px;
    margin: 0;
    padding: 0;
}

.points-player-info
{
    font-family: 'Lilita One', sans-serif;
    margin: 0;
    padding: 0;
}

.players-container {
    width: 100%;
    height: 100%;
    background-color: #FFFDFD;
    border-radius: 22px;
}

.player
{
    height: 95px;
    width: 100%;
    border: 3px solid;
    border-radius: 22px;
    margin-bottom: 6px;
    box-shadow: 0 4px 4px rgba(0,0,0,.25);
}

.avatar {
    border-radius: 50%;
    overflow: hidden;
    height: 3rem;
    width: 3rem;
    flex-shrink: 0;
}

.avatar img {
    height: 100%;
    width: auto;
}
</style>