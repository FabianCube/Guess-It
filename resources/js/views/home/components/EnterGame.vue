<template>
    <div class="card border-0 shadow-sm popup-login">
        <div class="w-100 p-2">
            <button @click="toggleEnterGame(), playHovers('/storage/sounds/click-back.mp3')" id="closeLogin">
                <img src="/storage/icons/arrow-left.svg" alt="">
            </button>
        </div>
        <div class="card-body" style="width: 50%;">
            <h1 style="text-align: center;">CÓDIGO DE PARTIDA</h1>
            <form @submit.prevent>
                <div class="">
                    <!-- Código partida -->
                    <div class="mb-3">
                        <label for="codigo" class="form-label h4">Código</label>
                        <input id="codigo" type="text" class="form-control" v-model="roomCode" required autofocus
                            autocomplete="username">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end mt-4">
                        <button
                            @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')"  
                            @click="findRoom(), playHovers('/storage/sounds/hover3.mp3')" class="btn-default btn-login">
                            PREPARADO!
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</template>

<script setup>
import { defineEmits, ref } from 'vue';
import axios from 'axios';
import useAuth from '@/composables/auth';
import { useRouter } from 'vue-router';
import sweetAlertNotifications from '@/utils/swal_notifications';

const { throwErrorMessage } = sweetAlertNotifications();

const emits = defineEmits(['close-enterGame', 'open-anonymous']);
const hovers = ref(null);

const playHovers = (soundFile) => {
    hovers.value = new Audio(soundFile);
    hovers.value.volume = 0.5;
    hovers.value.play();
}

function toggleEnterGame() {
    emits('close-enterGame');
}

const { isLoggedIn } = useAuth();

const roomCode = ref();
const router = useRouter();

const roomExists = ref();

// Verifica si existe una sala con el código insertado
const findRoom = async () => {
    try {
        const response = await axios.get(`/api/find-room/${roomCode.value}`);
        roomExists.value = response.data;
        console.log(roomExists.value.mensaje);

        if (roomExists.value.mensaje) {
            console.log("Sala existe");
            // Esperamos la ejecución de userRegistered y guardamos el resultado en una variable
            const isRegistered = await userRegistered();

            // Si el usuario está registrado entra en la sala, sino abre el login de user anónimo
            if (isRegistered) {
                await enterRoom();
                await localStorage.setItem('Sala', roomCode.value);
                router.push({ name: 'create-game', params: { code: roomCode.value } });
            }
        } else {
            throwErrorMessage("Sala completa");
            roomCode.value = "";
        }

    } catch (error) {
        throwErrorMessage("Código no válido");
        roomCode.value = "";
    }
}

// Comprueba si el usuario está registrado, si está registrado entra en partida, sino abre el login de anónimo
const userRegistered = () => {
    if (!isLoggedIn()) {
        console.log("No registrado");
        // Cerramos este componente y abrimos el login de usuario anónimo
        emits('close-enterGame');
        emits('open-anonymous', roomCode.value);
        return false;
    } else {
        console.log("Usuario registrado");
        return true;
    }
}

// Introduce a un usuario registrado en la sala
const enterRoom = () => {
    axios.post('/api/enter-room', {
        code: roomCode.value
    }).then(response => {
        console.log(response.data.mensaje);
    }).catch(error => {
        console.error("Error al unirse a la sala: ", error);
    });
};

</script>


<style scoped>

@import './../style/enterGame.css';

</style>