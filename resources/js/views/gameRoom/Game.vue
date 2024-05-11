<template>
    <div id="background-game"></div>
    <countdown-timer v-if="timer" @update-timer="handleTimerUpdate" :startTimer="startTimer" />
    <round-end :user="user" :roundEnd="roundEnd" :players="players" :playedWord="playingWord" :lastRound="lastRound" />
    <div v-if="!gameFinished" class="min-h-screen sm:items-center py-4 main-content">

        <div class="container">

            <div class="d-flex justify-content-between align-items-center">

                <router-link to="/" class="btn-smll-default">
                    <img src="/storage/icons/home-05.svg" alt="">
                </router-link>

                <img id="logo" src="/storage/guess-it-logo.svg" class="logo"></img>

                <button class="btn-smll-default">
                    <img src="/storage/icons/volume-on.svg" alt="">
                </button>
            </div>

            <!-- CANVAS & CHAT -->
            <div class="container flex flex-row p-0 mt-5 justify-content-between block">
                <div id="overlay" v-if="showOverlay"></div>
                <!-- PLAYER INFO -->
                <div class="col-1 p-0 info-jugador">
                    <!-- COMPONENTE INFO JUGADOR -->
                    <info-players :players="players" :user="user"/>
                </div>

                <div class="col-8 p-0" style="height: 622.5px; width: 830px;">
                    <!-- CANVAS -->
                    <div style="width: 100%; height: 100%;border-radius: 12px; position: relative;">
                        <!-- COMPONENTE STATUS BAR -->
                        <status-bar :playingWord="playingWord" :timeRound="timeRound" :difficulty="difficulty"
                            :currentPlayer="currentPlayer" :user="user" :startRound="startRound"
                            @endOfRound="handleEndOfRound" @roundTimeLeft="handleTimeLeft" :roomCode="roomCode"
                            :roundFinished="roundFinished" :guessedWord="guessedWord" />
                        <!-- COMPONENTE CANVAS -->
                        <canvas-component :user="user" :newCanvas="newCanvas" @canvasupdate="sendCanvas"
                            :isDrawingEnabled="isDrawingEnabled" :roomCode="roomCode"></canvas-component>
                    </div>
                </div>

                <div class="col-3 flex flex-row chat-container">
                    <!-- CHAT -->
                    <div class="p-3 chat flex flex-column justify-content-between">
                        <!-- COMPONENTE CHAT  -->
                        <div class="col-8 p-0" style="height: 95%; width:100%;">
                            <chat-messages :messages="messages" :rounds="rounds"
                                :currentRound="currentRound"></chat-messages>
                        </div>
                        <div class="col-4 p-0" style="width:100%">
                            <chat-form @messagesent="addMessage" :user="user"
                                :isChatEnabled="isChatEnabled"></chat-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <game-end v-else :players="players" :user="user"/>
</template>

<script setup>
import axios from 'axios';
import { onBeforeMount, onMounted, onUnmounted, ref, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import useAuth from '@/composables/auth';
import RoundEnd from '@/views/gameRoom/components/RoundEnd.vue';
import GameEnd from '@/views/gameRoom/components/GameEnd.vue';

const route = useRoute();
const router = useRouter();
const { isLoggedIn } = useAuth();
const user = ref();
const messages = ref([]);
const newCanvas = ref();
const roomCode = ref();
const gameData = ref(null);
const players = ref([]);
const timeRound = ref();
const rounds = ref();
const difficulty = ref();
const timer = ref(true);
const startRound = ref(false);
const currentPlayerIndex = ref(0);
const currentPlayer = computed(() => players.value[currentPlayerIndex.value]);
const roundFinished = ref(false);
const currentRound = ref(1);
const isDrawingEnabled = ref(false);
const isChatEnabled = ref(true);
const roundTimeLeft = ref();
const guessOrder = ref(1);
const gameFinished = ref(false);
const startTimer = ref(false);
const roundInProgress = ref(false);
const roundEnd = ref(false);
const guessedWord = ref(false);
const showOverlay = ref(true);
const lastRound = ref(false);

const playingWord = ref('');
const words = ([]);

/* MOUNTING */

onBeforeMount(async () => {
    // if (localStorage.getItem('Partida') != route.params.code) {
    //     await router.push({ name: 'home' });
    // }

    const decodedData = decodeURIComponent(route.query.gameData);
    gameData.value = JSON.parse(decodedData);

    players.value = gameData.value.players;
    timeRound.value = gameData.value.time_per_round;
    rounds.value = gameData.value.rounds;
    difficulty.value = gameData.value.difficulty;


    console.log("INFO DE PLAYER ==> "
        + " Nickame: " + players.value[0].nickname
        + " Color: " + players.value[0].color
        + " Points: " + players.value[0].points
        + " Position: " + players.value[0].position);

    console.log(gameData.value);
    console.log(players.value);

    roomCode.value = route.params.code;

    console.log('Playing in channel ==== room-' + roomCode.value);
    console.log('Injected game data:', gameData.value);

    await getUserData();

    // localStorage.removeItem('Partida');
    await userAcces();

    await getUserColor();

    // it is generating a word everytime page refresh
    if (currentPlayer.value.uuid == user.value.uuid) {
        await setPlayingWord();
    }
})

onMounted(async () => {
    console.log("[Game.vue]:currentPlayerIndex.value -> " + currentPlayerIndex.value);
    console.log("[Game.vue]:currentPlayer.value.uuid -> " + currentPlayer.value.uuid);
    console.log("[Game.vue]:user.value.uuid -> " + user.value.uuid);

    beginStartTimer();

    listenEventMessageSent();
    listenEventCanvasUpdate();
    listenEventSendWord();
    listenRoundFinished();
    listenCorrectWord();
    listenDrawerpoints();
    listenStartTimer();

    movingBackground();

    console.log("User: " + user.value.nickname);
});

onUnmounted(() => {
    window.Echo.leave('room-' + roomCode.value);
});

/* WATCHERS */

// Observa cuándo termina la ronda
watch(roundFinished, (newValue) => {
    if (newValue) {
        if (currentRound.value + 1 > rounds.value && currentPlayerIndex.value == players.value.length - 1) {      
            lastRound.value = true;
            roundEnd.value = true; 
            setTimeout(() => {
                gameFinished.value = true;
            }, 2500);
        } else {
            console.log('Siguiente jugador');
            showOverlay.value = true;
            roundEnd.value = true;
            startRound.value = false;
            setTimeout(() => {
                moveToNextPlayer();
                guessedWord.value = false;
                roundEnd.value = false;
                roundFinished.value = false;
                timer.value = true;
                beginStartTimer();
                guessOrder.value = 1;
                userAcces();
                if (currentPlayer.value.uuid == user.value.uuid) {
                    setPlayingWord();
                }
            }, 10000);

        }
    }
});

/* LISTENERS */

const listenEventMessageSent = () => {
    window.Echo.channel('room-' + roomCode.value)
        .listen('.MessageSent', (e) => {
            console.log("[Game.vue]:listenEventMessageSent:.MessageSent -> " + e.message);

            messages.value.push({
                message: e.message,
                user: e.user
            });
        });
}

const listenEventCanvasUpdate = () => {
    window.Echo.channel('room-' + roomCode.value)
        .listen('.CanvasUpdate', (e) => {

            if (e.canvas == "clear") {
                console.log(e.canvas);
                axios.post('/api/clean-canvas', {
                    code: roomCode.value
                }).then(response => {
                    console.log(response.data);
                }).catch(error => {
                    console.error("Error al unirse al canal: ", error);
                });
            } else {

                newCanvas.value = e.canvas;
            }
        });
}

const listenEventSendWord = () => {
    window.Echo.channel('room-' + roomCode.value)
        .listen('.SendWord', (e) => {
            console.log("[Game.vue]:listenEventSendWord:.SendWord -> " + e.word);

            playingWord.value = e.word;
        });
}

const listenRoundFinished = () => {
    window.Echo.channel('room-' + roomCode.value)
        .listen('.RoundFinished', (e) => {
            roundFinished.value = e.finished;
            roundInProgress.value = false;
            console.log("Ronda terminada");
        });
}

// Cuando alguien acierta se le suman puntos
const listenCorrectWord = () => {
    window.Echo.channel('room-' + roomCode.value)
        .listen('.CorrectWord', (e) => {
            const playerIndex = players.value.findIndex(player => player.uuid === e.userId);

            if (playerIndex !== -1) {
                players.value[playerIndex].points += e.points;
            }

            if (players.value[playerIndex].uuid == user.value.uuid) {
                guessedWord.value = true;
                isChatEnabled.value = false;
            }

            messages.value.push({
                message: "HA ACERTADO!",
                user: players.value[playerIndex],
                points: e.points
            });

            guessOrder.value++;

            console.log(guessOrder.value);
            console.log(players.value.length);

            if (guessOrder.value == players.value.length && currentPlayer.value.uuid == user.value.uuid) {
                console.log("[Game.vue]:Fin de ronda, todos los jugadores han adivinado la palabra");
                handleEndOfRound();
            }
        });
}

// Al finalizar la ronda se le asignan los puntos al jugador que dibujaba
const listenDrawerpoints = () => {
    window.Echo.channel('room-' + roomCode.value)
        .listen('.DrawerPoints', (e) => {
            const playerIndex = players.value.findIndex(player => player.uuid === e.userId);
            if (playerIndex !== -1) {
                players.value[playerIndex].points += e.points;
            }
        });
}

const listenStartTimer = () => {
    window.Echo.channel('room-' + roomCode.value)
        .listen('.StartTimer', (e) => {
            startTimer.value = true;
        });
}

/* HANDLERS */

// Cuándo la cuenta atrás llega a 0 deshabilitamos el componente del timer
const handleTimerUpdate = (timeLeft) => {
    console.log(timer.value);

    if (timeLeft.timeLeft == 0) {
        timer.value = false;
        startRound.value = true;
        startTimer.value = false;
        roundInProgress.value = true;
        showOverlay.value = false;
    }
};

// Cuándo la cuenta atrás llega a 0 deshabilitamos el componente del timer
const handleTimeLeft = (time) => {
    roundTimeLeft.value = time.roundTimeLeft;
};

// Cuándo acaba el tiempo de la ronda 
const handleEndOfRound = async () => {
    if (roundInProgress.value) {

        console.log("Gestionando fin de ronda");

        await axios.post('/api/drawer-points', {
            code: roomCode.value,
            userId: currentPlayer.value.uuid,
            correctPlayers: guessOrder.value - 1,
            players: players.value.length - 1
        }).then(response => {
            console.log(response.data);
        }).catch(error => {
            console.error("Error al unirse a la sala: ", error);
        });

        await axios.post('/api/round-finished', {
            code: roomCode.value,
            finished: true
        }).then(response => {
            console.log(response.data);
        }).catch(error => {
            console.error("Error al unirse a la sala: ", error);
        });
    }

};

/* FUNCTIONS */

const addMessage = (newMessage) => {

    // Si las palabras coinciden se suman los puntos
    if (compareWords(playingWord.value, newMessage.message)) {
        axios.post('/api/correct-word', {
            code: roomCode.value,
            players: players.value,
            userId: newMessage.user.uuid,
            elapsedTime: timeRound.value - roundTimeLeft.value,
            guessOrder: guessOrder.value
        }).then(response => {
            console.log(response.data);
        }).catch(error => {
            console.error("Error: ", error);
        });
        console.log(roundInProgress.value);
    } else {
        messages.value.push(newMessage);

        console.log("[Game.vue]:addMessage:user.nickname -> " + newMessage.user.nickname)
        console.log("[Game.vue]:addMessage:message -> " + newMessage.message)

        axios.post('/api/messages', {
            user: newMessage.user,
            message: newMessage.message,
            code: roomCode.value
        }).then(response => {
            console.log(response.data);
        }).catch(error => {
            console.error("Error: ", error);
        });
    }

    console.log("[Game.vue]:addMessage:messages{} -> " + messages.value);
}

const sendCanvas = (canvas) => {
    axios.post('/api/canvas', {
        user: canvas.user,
        canvas: canvas.canvas,
        code: roomCode.value
    }).then(response => {
        console.log(response.data);
    }).catch(error => {
        console.error("Error al unirse a la sala:", error);
    });
}

const setPlayingWord = async () => {

    playingWord.value = await getPlayingWord();
    console.log("[Game.vue]:setPlayingWord:word -> " + playingWord.value)

    await axios.post('/api/word', {
        code: roomCode.value,
        word: playingWord.value
    }).then(response => {
        console.log(response.data);
    }).catch(error => {
        console.error("Error en la petición axios de la palabra", error);
    });

}


// Asigna el color al jugador
const getUserColor = async () => {
    const playerIndex = players.value.findIndex(player => player.uuid === user.value.uuid);

    user.value.color = players.value[playerIndex].color;
}


// Al acabar la ronda pasa a dibujar el siguiente jugador, si es la última ronda termina el juego
const moveToNextPlayer = () => {
    console.log(roundInProgress.value);

    if (currentRound.value + 1 > rounds.value && currentPlayerIndex.value == players.value.length - 1) {
        gameFinished.value = true;
    } else {
        if (currentPlayerIndex.value < players.value.length - 1) {
            currentPlayerIndex.value++;
        } else {
            currentRound.value++;
            currentPlayerIndex.value = 0;
        }
    }

    console.log("[Game.vue]:currentPlayerIndex.value -> " + currentPlayerIndex.value);
    console.log("[Game.vue]:currentPlayer.value -> " + currentPlayer.value);
};

// Inicia la cuenta atrás para todos los jugadores
const beginStartTimer = () => {

    if (currentPlayer.value.uuid == user.value.uuid) {
        setTimeout(() => {
            axios.post('/api/start-timer', {
                code: roomCode.value
            }).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.error("Error: ", error);
            });
        }, 3000);
    }
}

// Obtiene los datos del usuario
const getUserData = async () => {

    if (isLoggedIn()) {
        console.log("[INFO]: Entrando como usuario registrado.");

        await axios.get('/api/user')
            .then(response => {
                console.log(response.data.data);
                user.value = response.data.data;
                user.value.avatar = "/storage/avatars/avatar" + response.data.data.avatar_id + ".jpg";
                user.value.uuid = user.value.id
            })

        console.log(user.value);
    }
    else {
        console.log("[INFO]: Entrando como usuario anónimo.");

        let getUserData = sessionStorage.getItem('userData');
        const userData = JSON.parse(getUserData);
        user.value = userData;

        if (user.value !== undefined) {
            console.log("User Nickname -> " + user.value.nickname);
            console.log("User UUID -> " + user.value.uuid);
        }
        else {
            console.log("[ERROR]: Al obtener datos de usuario anónimo.");
        }
    }
}

// Obtiene la palabra a jugar aleatoriamente según la dificultad
const getPlayingWord = async () => {

    let category = '';

    switch (difficulty.value) {
        case 'Fácil':
            category = 'easy';
            break;
        case 'Medio':
            category = 'medium';
            break;
        case 'Difícil':
            category = 'hard';
            break;
    }

    const response = await axios.get(`/api/get-word/${category}`);
    const words = response.data;
    return selectRandomWord(words);
}

const selectRandomWord = (words) => {

    console.log(words);
    // length de las palabras
    // let length = computed(() => words.length);
    let length = words.length;
    // indice aleatorio
    let index = Math.floor(Math.random() * length);
    let word = words[index].toUpperCase();

    return word;
}

// Define si el jugador actual puede dibujar o usar el chat
const userAcces = async () => {

    if (currentPlayer.value.uuid == user.value.uuid) {
        isDrawingEnabled.value = true;
        isChatEnabled.value = false;
    } else {
        isDrawingEnabled.value = false;
        isChatEnabled.value = true;
    }
}

// El fondo se mueve con el movimiento del ratón
const movingBackground = () => {
    const bg = document.getElementById('background-game');

    if (!bg) {
        console.error('Elemento #background no encontrado.');
        return;
    }

    document.addEventListener("mousemove", (e) => {
        const width = window.innerWidth / 2;
        const height = window.innerHeight / 2;

        const mouseX = e.clientX - width;
        const mouseY = e.clientY - height;

        // Ajusta estos multiplicadores para cambiar la sensibilidad del efecto
        const bgX = mouseX * 0.02; // Por ejemplo, 0.05 para un efecto sutil
        const bgY = mouseY * 0.02;

        // Aplica la transformación
        bg.style.transform = `translate(${bgX}px, ${bgY}px) translateZ(0)`;
    });
}

// Compara la palabra del jugador con la palabra a dibujar en la ronda sin tener en cuenta acentos ni mayúsculas
const compareWords = (playingWord, userWord) => {
    const normalizedStr1 = playingWord.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    const normalizedStr2 = userWord.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();

    // Comparamos las cadenas normalizadas
    return normalizedStr1.localeCompare(normalizedStr2, undefined, { sensitivity: 'base' }) === 0;
}

</script>

<style>

@import 'style/game.css';

</style>