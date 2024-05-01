<!-- * Component for information in game -->

<template>
    <div class="container flex flex-row justify-content-between align-items-center px-5 bar-container">
        <div style="font-size:2rem">
            <div :style="{ borderColor: props.currentPlayer.color }" class="card-player">
                <div class="card-avatar">
                    <div class="avatar">
                        <img :src="props.currentPlayer.avatar" alt="">
                    </div>
                </div>
                <div class="card-player-info">
                    <p>DIBUJANDO:</p>
                    <p :style="{ color: props.currentPlayer.color }">{{ props.currentPlayer.nickname }}</p>
                </div>
            </div>
        </div>
        <div id="word-container" style="font-size:2rem">
            <h2>{{ props.playingWord }}</h2>
        </div>
        <div id="clock-container">

            <div class="clock-icon">
                <div class="clock-time">
                    <h3>{{ roundTimeLeft }}</h3>
                </div>
                <img src="/storage/clock.svg" alt="">
            </div>
        </div>
    </div>
</template>

<script setup>
import { watch, ref, onMounted, computed, defineEmits } from 'vue';
import axios from 'axios';

const props = defineProps(['timeRound', 'difficulty', 'currentPlayer', 'playingWord', 'startRound']);
const emits = defineEmits(['wordselected', 'endOfRound']);
const words = ([]);
const difficulty = ref('');

const playerDrawing = ref('');
const playerColor = ref('');

const roundTimeLeft = ref();

const isWordSelected = ref(false);

watch(() => props.startRound, (newValue) => {
    if (newValue) {
        startRoundTimer();
    }
});

onMounted(async () => {
    roundTimeLeft.value = props.timeRound;

    console.log("dificultad: " + props.difficulty)
    console.log("[StatusBar.vue]:playingWord: " + props.playingWord)

    // switch(props.difficulty)
    // {
    //     case 'Fácil':
    //         difficulty.value = 'easy';
    //         break;
    //     case 'Medio':
    //         difficulty.value = 'medium';
    //         break;
    //     case 'Difícil':
    //         difficulty.value = 'hard';
    //         break;
    // }

    // await axios.get(`/api/get-word/${difficulty.value}`)
    //     .then(response => {
    //         words.value = response.data;

    //         if(isWordSelected.value === false)
    //         {
    //             selectRandomWord();
    //             isWordSelected.value = true;
    //             console.log("Playing word: " + playingWord.value);

    //             emits("wordselected", {
    //                 word: playingWord.value
    //             });
    //         }
    //     });
})

// const selectRandomWord = () => {
//     // length de las palabras
//     let length = computed(() => words.value.length);
//     // indice aleatorio
//     let index = Math.floor(Math.random() * length.value);
//     playingWord.value = words.value[index].toUpperCase();
// }

const startRoundTimer = () => {
    const intervalId = setInterval(() => {
        if (roundTimeLeft.value > 0) {
            roundTimeLeft.value--;
        } else {
            clearInterval(intervalId);
            emits('endOfRound');
            console.log('Emitido final de ronda');
        }
    }, 1000);
};

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

.clock-time>h3 {
    font-family: 'Lilita One', sans-serif;
    font-size: 2rem;
    color: #494949;
}

#word-container {
    font-family: 'Lilita One', sans-serif;
}

#word-container>h2 {
    color: white;
    font-size: 2.3rem;
}

.card-player {
    width: 160px;
    height: 60px;
    border-radius: 14px;
    border: 3px solid red;
    background-color: #fff;
    display: flex;
    justify-content: space-around;
    flex-flow: row;
    align-items: center;
}

.card-player-info {
    width: 60%;
}

.card-player-info>p {
    font-size: 14px;
    font-family: 'Lilita One', sans-serif;
    margin: 0;
    padding: 0;
    height: 20px;
}

.card-player-name {
    color: #FD6F5A;
}

.avatar {
    width: 37px;
    height: 37px;
    border-radius: 50px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
}

.avatar>img {
    width: auto;
    height: 100%;
}
</style>