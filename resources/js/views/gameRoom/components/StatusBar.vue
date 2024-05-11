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
                    <p>DIBUJANDO: </p>
                    <p :style="{ color: props.currentPlayer.color }">{{ props.currentPlayer.nickname }}</p>
                </div>
            </div>
        </div>

        <div id="word-container" style="font-size:2rem">
            <!-- CHECK IF USER IS DRAWING {SHOW WORD / DO NOT SHOW} -->
            <div class="letters" v-if="props.currentPlayer.uuid != props.user.uuid && !props.guessedWord"
                v-for="letter in currentWordEncrypted">
                <h2 v-if="letter.visibility == 1"> {{ letter.letter }} </h2>
                <h2 v-else> {{ letter.character }} </h2>
            </div>
            <div class="letters" v-else-if="props.currentPlayer.uuid != props.user.uuid && props.guessedWord">
                <h2>{{ props.playingWord }}</h2>
            </div>
            <div class="letters" v-else-if="props.currentPlayer.uuid == props.user.uuid ">
                <h2> {{ props.playingWord }} </h2>
            </div>
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

const props = defineProps(['timeRound', 'difficulty', 'currentPlayer', 'playingWord', 'startRound', 'user', 'roomCode', 'guessedWord']);
const emits = defineEmits(['wordselected', 'endOfRound', 'roundTimeLeft']);
const words = ([]);
const difficulty = ref('');

const playerDrawing = ref('');
const playerColor = ref('');

const roundTimeLeft = ref();
const currentWord = ref('');
const currentWordEncrypted = ref({});
const wordLenght = ref(0);
const currentInterval = ref(0);
const usedIndex = ref([]);



/* MOUNTING */

onMounted(async () => {
    roundTimeLeft.value = props.timeRound;

    console.log("dificultad: " + props.difficulty)
    console.log("[StatusBar.vue]:playingWord: " + props.playingWord)

    listenEncryptedWord();
})

/* WATCHERS */


watch(() => props.startRound, (newValue) => {
    if (newValue == true) {

        if (usedIndex.value.length != 0) {
            reset();
            console.log("New round: Reset done. usedIndex.length -> [" + usedIndex.value + "]");
        }

        startRoundTimer();

    } else {
        roundTimeLeft.value = props.timeRound;
    }
});

watch(() => props.playingWord, (word) => {
    console.log("WORD IN WATCH === > " + word);
    currentWord.value = word;

    encryptWord(currentWord.value);
});

watch(() => roundTimeLeft.value, () => {

    if (props.currentPlayer.uuid == props.user.uuid) {
        letterRevealer();
    }
})

/* LISTENERS */

const listenEncryptedWord = () => {
    window.Echo.channel('room-' + props.roomCode)
        .listen('.EncryptedWord', (e) => {
            currentWordEncrypted.value = e.encryptedWord;
        });
}

/* FUNCTIONS */

const reset = () => {
    usedIndex.value = [];
}

// 
const startRoundTimer = () => {

    const intervalId = setInterval(() => {
        if (roundTimeLeft.value > 0 && props.startRound) {
            roundTimeLeft.value--;
            emits('roundTimeLeft', {
                roundTimeLeft: roundTimeLeft.value
            });
        } else {
            clearInterval(intervalId);
            if (props.currentPlayer.uuid == props.user.uuid) {
                console.log("[StatusBar.vue]:Emit de fin de ronda");
                emits('endOfRound');
            }
            roundTimeLeft.value = props.timeRound;
        }

    }, 1000);
};


const letterRevealer = () => {
    let actualWord = countVisibleLetters(currentWordEncrypted.value);
    let index = 0;
    let time = props.timeRound;
    let size = wordLenght.value;
    let interval = Math.trunc(time / size);

    let antiFrezexdd = 0;

    do {
        index = Math.round(Math.random() * (size - 1));
        // console.log("Index: " + index);
        antiFrezexdd++;
    }
    while (usedIndex.value.includes(index) && antiFrezexdd < 20)

    if (currentInterval.value == interval) {
        if (currentWordEncrypted.value[index]) {
            currentWordEncrypted.value[index].visibility = 1;
            usedIndex.value.push(index);
        }

        currentInterval.value = 0;
    }
    else {
        currentInterval.value++;
    }

    if (actualWord !== countVisibleLetters(currentWordEncrypted.value)) {
        axios.post('/api/encrypted-word', {
            code: props.roomCode,
            encryptedWord: currentWordEncrypted.value
        }).then(response => {
            console.log(response.data);
        }).catch(error => {
            console.error("Error al unirse a la sala: ", error);
        });
    }
}

const encryptWord = (word) => {
    let splitted = word.split('');
    wordLenght.value = splitted.length;
    let wordSplitted = [];

    for (let i = 0; i < splitted.length; i++) {
        if (splitted[i] !== " ") {
            wordSplitted.push({
                'letter': splitted[i],
                'character': '_',
                'visibility': 0
            });
        }
        else {
            wordSplitted.push({
                'letter': splitted[i],
                'character': ' ',
                'visibility': 0
            });
        }

    }

    currentWordEncrypted.value = wordSplitted;
    console.log(currentWordEncrypted.value[0]);
}

// Filtra y cuenta los elementos con `visibility` igual a 1
function countVisibleLetters(wordArray) {
    return wordArray.filter(letterObj => letterObj.visibility === 1).length;
}

</script>

<style scoped>

@import './../style/statusBar.css';

</style>