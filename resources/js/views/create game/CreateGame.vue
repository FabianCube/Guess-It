<template>
    <div id="background"></div>
    <div v-if="isLoading" class="d-flex flex-column justify-content-center align-items-center loading-container">
        <img src="/storage/loading.svg" alt="" class="loading-logo">
        <div class="mt-5 loading-bar-container">
            <div class="loading-bar"></div>
        </div>
    </div>
    <div v-else class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0 lilita-one-regular">
        <invite-friends v-if="showFriendsList" :roomCode="roomCode" :friends="friendsList" @close="handleClose"
            />
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center">
                <router-link 
                    @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')" 
                    to="/" class="btn-smll-default">
                    <img src="/storage/icons/home-05.svg" alt="">
                </router-link>
                <img id="logo" src="/storage/guess-it-logo.svg" class="logo"></img>
                <button 
                    v-if="isMusicMuted" 
                    @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')" 
                    @click="muteAllSounds(), playHovers('/storage/sounds/hover3.mp3')" 
                    class="btn-smll-default"><img src="/storage/icons/volume-off.svg" alt=""></button>
                
                <button 
                    v-else 
                    @click="muteAllSounds(), playHovers('/storage/sounds/hover3.mp3')" 
                    @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')" 
                    class="btn-smll-default"><img src="/storage/icons/volume-on.svg" alt=""></button>
            </div>
            <div class="row d-flex mt-5">
                <div class=" order-2 order-sm-1 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="background-players">
                        <div class="h-100 d-flex flex-column justify-content-between p-5">
                            <h2 class="mb-3 players-font">JUGADORES</h2>
                            <PlayerList class="mb-3" @update-players="handlePlayers" />
                            <div 
                                @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')" 
                                class="d-flex justify-content-center btn-invite" 
                                @click="showFriendsList = true, playHovers('/storage/sounds/hover3.mp3')">

                                <div  class="d-flex align-items-center">
                                    <h3  id="invitar-amigos" class="mb-0 me-3 ">INVITAR AMIGOS</h3>
                                    <img src="../../../../storage/app/public/icons/user-plus-01.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" order-1 order-sm-2 col-xs-12 col-sm-12 col-md-8 col-lg-8 d-flex flex-column justify-content-between ps-sm-5">
                    <div class="background-instructions">
                        <div class="p-3 h-100 container-settings">
                            <!-- COMPONENTE AJUSTES DE PARTIDA -->
                            <GameSettings v-if="options" @update-settings="handleSettingsUpdate" />
                            <!-- COMPONENTE CAROUSEL -->
                            <Carousel v-else />
                        </div>
                    </div>
                    <div class="d-flex justify-content-between pb-3">
                        <div class="align-self-start d-flex alig-items-center background-code">
                            <h4 id="room-code" class="mb-0 me-4">CODE: {{ roomCode }}</h4>
                            <img src="../../../../storage/app/public/icons/copy-03.svg" alt="Copiar código"
                                class="copiar" @click="copiarCodigo">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')" v-if="options" @click="startGame(), playHovers('/storage/sounds/hover3.mp3')" :disabled="!startButtonEnabled"
                                :class="{ 'btn-disabled': !startButtonEnabled }"
                                class="d-flex align-items-center btn-play">
                                <h1 class="mb-2 me-3 play-font">INICIAR</h1>
                                <img src="../../../../storage/app/public/icons/play.svg" alt="">
                            </button>
                            <div v-else class="d-flex align-items-center">
                                <h3 class="mb-0 me-3 white">Esperando a que el anfitrión configure la partida</h3>
                                <img id="carga" src="/storage/carga.svg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeMount, onBeforeUnmount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import sweetAlertNotifications from '@/utils/swal_notifications';
import useAuth from '@/composables/auth';

const { throwSuccessMessage, throwRedirectMessage } = sweetAlertNotifications();
const { isLoggedIn } = useAuth();

const roomCode = ref();
const route = useRoute();
const router = useRouter();
const hovers = ref(null);
const backgroundMusic = ref(null);
const isMusicMuted = ref(false);

// Variable con el id del creador de partida
const owner = ref();

const options = ref(false);
const startButtonEnabled = ref(false);

// Constante para no mostrar componentes hasta no haber cargado los datos de la sala
const isLoading = ref(true);

// Guardamos en variables los datos de sesión del usuario anónimo
const storedUserDataString = sessionStorage.getItem('userData');
const storedUserData = JSON.parse(storedUserDataString || '{}');

// Id de usuario registrado
const userRegistered = ref();

// Parámetros de la partida
const gameSettings = ref({
    numberRounds: 2,
    roundTime: 30,
    difficulty: 'Fácil'
});

const players = ref();

// Variables para mostrar la lista de amigos
const showFriendsList = ref(false);
const friendsList = ref([]);

/* MOUNTING */

onBeforeMount(async () => {
    if (localStorage.getItem('Sala') != route.params.code) {
        router.push({ name: 'home' });
    }

    roomCode.value = route.params.code;

    // Definimos una espera de 3 segundos
    const loadingPromise = new Promise((resolve) => setTimeout(resolve, 3000));

    // Guardamos en una variable el método getOwner
    const ownerPromise = getOwner();

    // Obtenemos el id del usuario si está autenticado
    if (isLoggedIn()) {
        const userId = await axios.get(`/api/user`);
        userRegistered.value = userId.data.data.id;
    }

    // Esperamos 3 segundos después de ejecutar getOwner para que se muestre la animación de carga
    // await Promise.all([loadingPromise, ownerPromise, jugadoresPromise]);
    await Promise.all([loadingPromise, ownerPromise]);

    // Si el usuario es el creador de partida, se le muestran las opciones de partida
    if (owner.value == storedUserData.uuid || owner.value == userRegistered.value) {
        options.value = true;
    }

    localStorage.removeItem('Sala');

    // Finaliza la carga y muestra los componentes
    isLoading.value = false;
});

onMounted(() => {
    backgroundMusic.value = null;
    playBackgroundMusic();

    loadFriends();

    movingBackground();

    Echo.channel('room-' + roomCode.value)
        .listen('.room-owner-left', (e) => {
            throwRedirectMessage();
        });

    Echo.channel('room-' + roomCode.value)
        .listen('.GameStart', (e) => {
            const encodedGameData = encodeURIComponent(JSON.stringify(e.gameData));
            localStorage.setItem('Partida', roomCode.value);
            router.push({ name: 'play-game', params: { code: roomCode.value }, query: { gameData: encodedGameData } });
        });
});

onBeforeUnmount(() => {
    // Usamos sendBeacon porque permite enviar datos de forma asíncrona al servidor
    // sin afectar en la experiencia del usuario
    const formData = new FormData();
    formData.append("uuid", storedUserData.uuid);

    backgroundMusic.value.pause();
    navigator.sendBeacon(`/api/leave-room/${roomCode.value}`, formData);
});

/* LISTENERS */

window.addEventListener('beforeunload', function (event) {
    const formData = new FormData();
    formData.append("uuid", storedUserData.uuid);

    navigator.sendBeacon(`/api/leave-room/${roomCode.value}`, formData);
});

// Mueve el fondo de pantalla según el movimiento del ratón
const movingBackground = () => {
    const bg = document.getElementById('background');

    if (!bg) {
        console.error('Elemento #background no encontrado.');
        return;
    }

    // Mueve el fondo de pantalla según el movimiento del ratón
    document.addEventListener("mousemove", (e) => {
        const width = window.innerWidth / 2;
        const height = window.innerHeight / 2;

        const mouseX = e.clientX - width;
        const mouseY = e.clientY - height;

        // Con esto se ajusta la sensibilidad del movimiento del fondo de pantalla
        const bgX = mouseX * 0.02;
        const bgY = mouseY * 0.02;

        // Aplica la transformación
        bg.style.transform = `translate(${bgX}px, ${bgY}px) translateZ(0)`;
    });
}

/* HANDLERS */

// Función para manejar la actualización de la configuración
const handleSettingsUpdate = (newSettings) => {
    gameSettings.value = newSettings;
};

// Función para manejar la actualización de la configuración
const handlePlayers = (newPlayers) => {
    players.value = newPlayers;
    startButtonEnabled.value = newPlayers >= 2;
};

const handleClose = () => {
    showFriendsList.value = false;
};

/* FUNCTIONS */

// Reproduce sonido en los hover
const playHovers = (soundFile) => {
    hovers.value = new Audio(soundFile);
    hovers.value.volume = 0.25;
    hovers.value.play();
}

//Reproduce la música de fondo
const playBackgroundMusic = () => {
    backgroundMusic.value = new Audio('/storage/sounds/create-game-background.mp3');
    backgroundMusic.value.volume = 0.1;
    backgroundMusic.value.loop = true;
    backgroundMusic.value.play();
}

// Silencia todos los sonidos
const muteAllSounds = () => {
    if(backgroundMusic.value.paused)
    {
        backgroundMusic.value.play();
        isMusicMuted.value = false;
    }
    else
    {
        backgroundMusic.value.pause();
        isMusicMuted.value = true;
    }
}

// Cargar la lista de amigos
const loadFriends = async () => {
    try {
        const response = await axios.get('/api/friends/list');
        friendsList.value = response.data;
        console.log(friendsList.value);
    } catch (error) {
        console.error('Error loading friends:', error);
    }
};

// Obtenemos el id del creador de partida
const getOwner = async () => {
    try {
        const response = await axios.get(`/api/room/owner/${roomCode.value}`);
        owner.value = response.data;
    } catch (error) {
        console.error('Error obteniendo detalles de la sala:', error);
    }
};

// Copia el código de la sala
const copiarCodigo = () => {
    // El navegador no soporta la API del portapapeles
    if (!navigator.clipboard) {
        console.error('La API del portapapeles no está soportada en este navegador');
        return;
    }


    navigator.clipboard.writeText(roomCode.value)
        .then(() => {
            throwSuccessMessage('Código de partida copiado');
        })
        .catch(err => {
            console.error('Error al copiar el código al portapapeles:', err);
        });
};

// Se crea la partida y se le pasa la configuración y los jugadores 
const startGame = async () => {
    try {
        const response = await axios.post(`/api/start-game/${roomCode.value}`, {
            roomCode: roomCode.value,
            settings: gameSettings.value
        });

        console.log('Partida creada:', response.data);
    } catch (error) {
        console.error("Error al crear la partida:", error);
    }
};
</script>

<style scoped>

@import 'style/createGame.css';

@media(max-width: 520px)
{
    .btn-play
    {
        margin: 10px 0 10px 0;
    }

    .background-code
    {
        margin: 10px 0 10px 0;
        justify-content: center;
        align-items: center;
    }

    .background-code>h4
    {
        margin: 10px 0 10px 0;
        font-size: 1rem!important;
        margin: 0;
    }

    .background-instructions
    {
        max-height: 400px!important;
        justify-content: center;
        align-items: center;
    }
}

</style>