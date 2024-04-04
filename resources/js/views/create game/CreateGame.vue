<template>
    <div id="background"></div>
    <div class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0 lilita-one-regular">
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
                            <GameSettings v-if="owner"/>
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
import { ref, onMounted, inject } from 'vue';
import { useRoute } from 'vue-router';


const codigoSala = ref();
const route = useRoute();

const swal = inject('$swal');

const owner = ref(false);

onMounted(() => {
    codigoSala.value = route.params.code;

    const bg = document.getElementById('background');

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
});

onMounted(async () => {
    try {
        console.log(codigoSala.value);
        const response = await axios.get(`/api/room/owner/${codigoSala.value}`);
        owner.value = response.data.owner;
    } catch (error) {
        console.error('Error obteniendo detalles de la sala:', error);
    }
});

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
            swal({
                    icon: 'success',
                    title: 'Código de partida copiado',
                    showConfirmButton: false,
                    timer: 1500,
                    allowOutsideClick: true
                })
        })
        .catch(err => {
            console.error('Error al copiar el código al portapapeles:', err);
        });
};
</script>