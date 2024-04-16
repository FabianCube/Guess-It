<template>
    <div id="background"></div>
    <div v-if="isLoading" class="d-flex flex-column justify-content-center align-items-center loading-container">
        <img src="/storage/loading.svg" alt="" class="loading-logo">
        <div class="mt-5 loading-bar-container">
            <div class="loading-bar"></div>
        </div>
    </div>
    <div v-else class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0 lilita-one-regular">
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
                            <PlayerList class="mb-3" />
                            <div class="d-flex justify-content-center btn-invite">
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
                            <GameSettings v-if="options" />
                            <Carousel v-else />
                        </div>
                    </div>
                    <div class="d-flex justify-content-between pb-3">
                        <div class="align-self-start d-flex alig-items-center background-code">
                            <h4 id="room-code" class="mb-0 me-4">CODE: {{ codigoSala }}</h4>
                            <img src="../../../../storage/app/public/icons/copy-03.svg" alt="Copiar código"
                                class="copiar" @click="copiarCodigo">
                        </div>
                        <div class="d-flex justify-content-end">
                            <router-link to="/play-game" class="d-flex align-items-center btn-play">
                                <h1 class="mb-2 me-3 play-font">INICIAR</h1>
                                <img src="../../../../storage/app/public/icons/play.svg" alt="">
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeMount, onBeforeUnmount, inject } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import sweetAlertNotifications from '@/utils/swal_notifications';
import Pusher from 'pusher-js';

const { throwSuccessMessage , throwRedirectMessage } = sweetAlertNotifications();


const codigoSala = ref();
const route = useRoute();

const router = useRouter();

const swal = inject('$swal');

// Variable con el id del creador de partida
const owner = ref();

const options = ref(false);

// Constante para no mostrar componentes hasta no haber cargado los datos de la sala
const isLoading = ref(true);

// Guardamos en variables los datos de sesión del usuario anónimo
const storedUserDataString = sessionStorage.getItem('userData');
const storedUserData = JSON.parse(storedUserDataString || '{}');

onBeforeMount(async () => {
    codigoSala.value = route.params.code;

    // Definimos una espera de 3 segundos
    const loadingPromise = new Promise((resolve) => setTimeout(resolve, 3000));

    // Guardamos en una variable el método getOwner
    const ownerPromise = getOwner();

    // Esperamos 3 segundos después de ejecutar getOwner para que se muestre la animación de carga
    await Promise.all([loadingPromise, ownerPromise]);

    // Si el usuario es el creador de partida, se le muestran las opciones de partida
    if (owner.value === storedUserData.uuid) {
        options.value = true;
    }

    // Finaliza la carga y muestra los componentes
    isLoading.value = false;
});

onMounted(() => {
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

    Echo.channel('room-channel')
        .listen('.room-owner-left', (e) => {
            throwRedirectMessage();
        });
});

onBeforeUnmount(() => {
    // Usamos sendBeacon porque permite enviar datos de forma asíncrona al servidor
    // sin afectar en la experiencia del usuario
    const formData = new FormData();
    formData.append("uuid", storedUserData.uuid);

    navigator.sendBeacon(`/api/leave-room/${codigoSala.value}`, formData);
});

// Obtenemos el id del creador de partida
const getOwner = async () => {
    try {
        const response = await axios.get(`/api/room/owner/${codigoSala.value}`);
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


    navigator.clipboard.writeText(codigoSala.value)
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

    navigator.sendBeacon(`/api/leave-room/${codigoSala.value}`, formData);
});
</script>

<style scoped>
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