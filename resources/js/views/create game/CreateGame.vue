<template>
    <div id="background"></div>
    <div v-if="isLoading" class="d-flex flex-column justify-content-center align-items-center loading-container">
        <img src="/storage/loading.svg" alt="" class="loading-logo">
        <div class="mt-5 loading-bar-container">
            <div class="loading-bar"></div>
        </div>
    </div>
    <div v-else class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0 lilita-one-regular">
        <invite-friends v-if="showFriendsList" :roomCode="roomCode" :friends="friendsList" @close="handleClose" @invited="handleInvitation"/>
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center">
                <router-link to="/" class="btn-smll-default"><img src="/storage/icons/home-05.svg" alt=""></router-link>
                <img id="logo" src="/storage/guess-it-logo.svg" class="logo"></img>
                <button class="btn-smll-default"><img src="/storage/icons/volume-on.svg" alt=""></button>
            </div>
            <div class="flex mt-5">
                <div class="col-4 ">
                    <div class="background-players">
                        <div class="h-100 d-flex flex-column justify-content-between p-5">
                            <h2 class="mb-3 players-font">JUGADORES</h2>
                            <PlayerList class="mb-3" @update-players="handlePlayers"/>
                            <div class="d-flex justify-content-center btn-invite" @click="showFriendsList = true">
                                <div class="d-flex align-items-center">
                                    <h3 id="invitar-amigos" class="mb-0 me-3 ">INVITAR AMIGOS</h3>
                                    <img src="../../../../storage/app/public/icons/user-plus-01.svg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 d-flex flex-column justify-content-between ps-5">
                    <div class="background-instructions">
                        <div class="p-3 h-100">
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
                            <button v-if="options" @click="startGame" :disabled="!startButtonEnabled" :class="{ 'btn-disabled': !startButtonEnabled }"  class="d-flex align-items-center btn-play">
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

// Función para manejar la actualización de la configuración
const handleSettingsUpdate = (newSettings) => {
    gameSettings.value = newSettings;
    console.log(gameSettings.value);
};

// Función para manejar la actualización de la configuración
const handlePlayers = (newPlayers) => {
    players.value = newPlayers;
    console.log(players.value);
    startButtonEnabled.value = newPlayers >= 2;
    console.log(startButtonEnabled.value);
};

const handleClose = () => {
  showFriendsList.value = false;
};

const handleInvitation = (friendId) => {
  console.log(`Friend ${friendId} invited`);
  // Aquí podrías añadir lógica adicional después de que se haya enviado una invitación
};

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

const canStartGame = () => {
    return players.value >= 2;
};

onBeforeMount(async () => {
    if(localStorage.getItem('Sala') != route.params.code){
        router.push({ name: 'home' });
    }

    roomCode.value = route.params.code;

    // Definimos una espera de 3 segundos
    const loadingPromise = new Promise((resolve) => setTimeout(resolve, 3000));

    // Guardamos en una variable el método getOwner
    const ownerPromise = getOwner();

    // Obtenemos el id del usuario si está autenticado
    if (isLoggedIn()) {
        const userId = await axios.get(`/api/get-user`);
        userRegistered.value = userId.data.uuid;
    }

    // Esperamos 3 segundos después de ejecutar getOwner para que se muestre la animación de carga
    // await Promise.all([loadingPromise, ownerPromise, jugadoresPromise]);
    await Promise.all([loadingPromise, ownerPromise]);

    console.log(userRegistered.value);
    console.log(owner.value);

    // Si el usuario es el creador de partida, se le muestran las opciones de partida
    if (owner.value == storedUserData.uuid || owner.value == userRegistered.value) {
        options.value = true;
    }

    localStorage.removeItem('Sala');

    // Finaliza la carga y muestra los componentes
    isLoading.value = false;
});

onMounted(() => {
    const bg = document.getElementById('background');

    loadFriends();

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

    navigator.sendBeacon(`/api/leave-room/${roomCode.value}`, formData);
});

// Obtenemos el id del creador de partida
const getOwner = async () => {
    try {
        const response = await axios.get(`/api/room/owner/${roomCode.value}`);
        owner.value = response.data;
        console.log(owner.value);
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
            console.log('Código copiado al portapapeles');
            throwSuccessMessage('Código de partida copiado');
        })
        .catch(err => {
            console.error('Error al copiar el código al portapapeles:', err);
        });
};


window.addEventListener('beforeunload', function (event) {
    const formData = new FormData();
    formData.append("uuid", storedUserData.uuid);

    navigator.sendBeacon(`/api/leave-room/${roomCode.value}`, formData);
});


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
.btn-disabled {
    background-color: #9d9d9d;
    color: rgb(230, 230, 230);
    border: none;
    padding: 20px 30px 15px 30px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: not-allowed;
    border-radius: 15px;
    box-shadow: 0 14px #808080;
    pointer-events: none;
}

#carga {
    height: 1.6rem;
    width: auto;
    animation: rotate 2s linear infinite;
}

.white {
    color: #e0e0e0;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.25);
}

.loading-container {
    height: 100vh;
    width: 100vw;
}

.loading-bar-container {
    width: 30vw;
    background-color: #e0e0e0;
    border-radius: 10px;
    padding: 5px;
    box-shadow: 0px 5px 7px 1px rgba(0, 0, 0, 0.25);
}

.loading-bar {
    height: 20px;
    background-color: #66BA13;
    width: 0%;
    border-radius: 5px;
    animation: fillProgress 3s ease-in-out forwards;
}

.loading-logo {
    animation: logoAnimation 3s infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

@keyframes fillProgress {
    from {
        width: 0%;
    }

    to {
        width: 100%;
    }
}

@keyframes logoAnimation {
    0% {
        transform: translate(0);
    }

    25% {
        transform: translate(0px, 10px);
    }

    50% {
        transform: translate(0px, -10px);
    }

    75% {
        transform: translate(0px, 10px);
    }

    100% {
        transform: translate(0px, 0px);
    }
}
</style>