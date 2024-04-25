<!-- * Component for information in game -->

<template>
    <div class="container flex flex-row justify-content-between align-items-center px-5 bar-container">
        <div style="font-size:2rem">JUGADOR</div>
        <div id="word-container" style="font-size:2rem">
            <h2>{{ playingWord }}</h2>
        </div>
        <div id="clock-container">

            <div class="clock-icon">
                <div class="clock-time">
                    <h3>{{ props.timeRound }}</h3>
                </div>
                <img src="/storage/clock.svg" alt="">
            </div>
        </div>
    </div>
</template>

<script setup>
import { watch, ref, onMounted, computed } from 'vue';
import axios from 'axios';

const props = defineProps(['timeRound', 'difficulty']);
const words = ([]);
const playingWord = ref('');
const difficulty = ref('');

onMounted(async () => {
    
    console.log("dificultad: " + props.difficulty)

    switch(props.difficulty)
    {
        case 'Fácil':
            difficulty.value = 'easy';
            break;
        case 'Medio':
            difficulty.value = 'medium';
            break;
        case 'Difícil':
            difficulty.value = 'hard';
            break;
    }

    await axios.get(`/api/get-word/${difficulty.value}`)
        .then(response => {
            words.value = response.data;

            selectRandomWord();
            
        });
    
})

const selectRandomWord = () => {
    // length de las palabras
    let length = computed(() => words.value.length);
    // indice aleatorio
    let index = Math.floor(Math.random() * length.value);
    playingWord.value = words.value[index].toUpperCase();
}


</script>

<style scoped>
.bar-container {
    position: absolute;
    background-color: #FD6F5A;
    width: 100%;
    height: 100px;
    border-radius: 22px 22px 0 0;
    z-index: 101;
}

.clock-icon {
    position: relative;
}

.clock-time {
    position: absolute;
    top: 63%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.clock-time>h3
{
    font-family: 'Lilita One', sans-serif;
    font-size: 2rem;
    color: #494949;
}

#word-container
{
    font-family: 'Lilita One', sans-serif;
}

#word-container>h2
{
    color: white;
    font-size: 2.3rem;
}
</style>