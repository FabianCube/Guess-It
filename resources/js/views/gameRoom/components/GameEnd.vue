<template>
    <div class="d-flex flex-column align-items-center justify-content-start vh-100">
        <img id="logo" src="/storage/guess-it-logo.svg" class="logo mt-5"></img>
        <div class="round-end table-container">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <h2 class="m-0 word-played-color">FINAL DE PARTIDA</h2>
            </div>
            <div class="d-flex flex-column table">
                <div class="d-flex justify-content-between">
                    <p class="players-font">POSICIÓN</p>
                    <p class="players-font">PUNTOS</p>
                </div>
                <div class="d-flex justify-content-between align-items-center my-2 list-name etiqueta"
                    v-for="(player, index) in sortedPlayers" :key="player.uuid" :class="{'is-user': isUser(player)}">
                    <div class="d-flex align-items-center">
                        <p class="mb-0 list-position" :style="{ color: getColorByIndex(index) }">{{ index + 1 }}</p>
                        <div class="mx-3 avatar" :style="{ backgroundImage: 'url(' + player.avatar + ')' }">
                            <!-- <img :src="player.avatar" alt="avatar"> -->
                        </div>
                        <p class="mb-0" :style="{ color: player.color }">{{ player.nickname }}</p>
                    </div>
                    <p class="mb-0">{{ player.points }}</p>
                </div>
            </div>
        </div>
        <router-link :to="{ name: 'home' }" class="px-5 btn-default volver">
            VOLVER
        </router-link>
    </div>
</template>

<script setup>
import { ref, computed, defineProps } from 'vue';

const props = defineProps(['players' , 'user']);

const status = ref(0);

const sortedPlayers = computed(() => {
    return [...props.players].sort((a, b) => b.points - a.points);
});

const isUser = (player) => {
    return props.user.uuid == player.uuid;
}

const getColorByIndex = (index) => {
    if (index === 0) return 'gold';
    if (index === 1) return 'silver';
    if (index === 2) return '#cd7f32';
    return 'grey';
};
</script>
<style scoped>
.table-container {
    font-family: 'Lilita One', sans-serif;
    width: 50%;
    height: auto;
    background: white;
    padding: 25px 40px 25px 40px;
    border-radius: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 100px;
}

.etiqueta {
    border-radius: 10px;
    padding-left: 0.5rem;
    padding-right: 1rem;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
    border: 2px solid #B2B2B2;
}

.avatar {
    height: 4rem;
    width: 4rem;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    background-position: center;
    background-size: cover;
}

.avatar img {
    height: 100%;
    width: auto;
}

.list-name {
    font-size: 24px;
}

.list-position {
    font-size: 32px;
}

.word-played-color {
    color: #494949;
}

.volver{
    width: auto;
    margin-top: 50px;
}

.is-user{
    box-shadow: 0px 4px 4px 0px #2993BA;
}
</style>