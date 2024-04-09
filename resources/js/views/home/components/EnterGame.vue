<template>
    <div class="card border-0 shadow-sm popup-login">
        <div class="w-100">
            <button @click="toggleEnterGame()" id="closeLogin">
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
                        <button @click="findRoom()" class="btn-default btn-login">
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

const emits = defineEmits(['close-enterGame']);

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
        
        console.log("Sala existe");
        await userRegistered();
        await enterRoom();
        router.push({ name: 'create-game', params: { code: roomCode.value } });
    } catch (error) {
        throwErrorMessage("Código no válido");
        roomCode.value = "";
    }
}

// Comprueba si el usuario está registrado
const userRegistered = () => {
    if (!isLoggedIn()) {
        console.log("No registrado")
        return;
    } else {
        console.log("Usuario registrado");
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
#closeLogin {
    background-color: transparent;
    border: none;
}

.popup-login {
    width: 800px;
    height: 500px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 100px;
    border-radius: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Lilita One', sans-serif;
}

.btn-login {
    width: 100%;
    height: 65px;
    border: none;
    margin: 0 0 15px 0;
    line-height: 65px;
    font-size: 2rem;
}

.form-control {
    border-radius: 12px !important;
    border: solid 2px #757575;
}
</style>