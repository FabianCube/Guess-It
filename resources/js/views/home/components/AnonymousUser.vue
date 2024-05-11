<template>
    <div class="card border-0 shadow-sm popup-login">
        <div class="w-100 p-2">
            <button @click="toggleAnonymous(), playHovers('/storage/sounds/click-back.mp3')" id="closeLogin">
                <img src="/storage/icons/arrow-left.svg" alt="">
            </button>
        </div>
        <div class="card-body" style="width: 50%;">
            <h1 style="text-align: center;">NOMBRE Y ASPECTO</h1>
            <div class="avatar-wrapper">
                <div class="image-container">
                    <img :src="avatarImage" alt="Avatar" class="avatar-image">
                </div>
                <button class="change-avatar" @click="changeAvatar">
                    <img src="/storage/icons/next-avatar.svg" alt="Next-avatar">
                </button>
            </div>
            <form @submit.prevent>
                <div class="">
                    <!-- Nickname -->
                    <div class="mb-3">
                        <label for="nickname" class="form-label h4">Nickname</label>
                        <input id="nickname" type="text" class="form-control" v-model="nickname" required autofocus
                            autocomplete="username">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end mt-4">
                        <button 
                            @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')"  
                            @click="createRoom(), playHovers('/storage/sounds/hover2.mp3')" 
                            class="btn-default btn-login">
                            PREPARADO!
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</template>

<script setup>
import { defineProps, defineEmits, ref, onMounted, onBeforeMount } from 'vue';
import axios from 'axios';
import useAuth from '@/composables/auth';
import { useRouter } from 'vue-router';
import { v4 as uuidv4 } from 'uuid';

const emits = defineEmits(['close-anonymous']);
const hovers = ref(null);

const playHovers = (soundFile) => {
    hovers.value = new Audio(soundFile);
    hovers.value.volume = 0.5;
    hovers.value.play();
}

function toggleAnonymous() {
    emits('close-anonymous');
}

const passedRoomCode = defineProps({
    roomCode: String
});

const baseAvatar = '/storage/avatars/';
const router = useRouter();
const nickname = ref();
const avatarImage = ref();
let avatarId = ref(1);

// Función para cargar el nombre del archivo del avatar según su Id
const loadAvatar = () => {
    axios.get('/api/get-avatar/' + avatarId.value)
        .then(({ data }) => {
            avatarImage.value = baseAvatar + data.image;
        })
        .catch(error => {
            console.error("Error al obtener el avatar: ", error);
        });
};

// Cargamos el avatar inicial cuando el componente se monta
onBeforeMount(loadAvatar);

// Método para cambiar el avatar
const changeAvatar = () => {
    if (avatarId.value === 4) {
        avatarId.value = 1;
    } else {
        avatarId.value += 1;
    }

    loadAvatar();
};



const roomCode = ref();
const playerUuid = ref();

// Se crea la sala, se añade al jugador anónimo y se redirige a la sala
const createRoom = async () => {
    console.log(passedRoomCode.roomCode);
    if (passedRoomCode.roomCode) {
        console.log("Uniendo al jugador a la sala existente con código:", passedRoomCode.roomCode);
        try {
            playerUuid.value = uuidv4();

            await submitAnonymousLogin();
            await enterRoom(passedRoomCode.roomCode);
            await localStorage.setItem('Sala', passedRoomCode.roomCode);
            router.push({ name: 'create-game', params: { code: passedRoomCode.roomCode } });
        } catch (error) {
            console.error("Error al unirse a la sala existente:", error);
        }
    } else {
        // Si no hay código de sala se crea una nueva sala
        try {
            // Genera el uuid para el jugador anónimo
            playerUuid.value = uuidv4();

            const response = await axios.post(`/api/create-room/${playerUuid.value}`);
            roomCode.value = response.data.code;

            console.log("Sala creada con código:", roomCode.value);
            await submitAnonymousLogin();
            await enterRoom(roomCode.value);
            await localStorage.setItem('Sala', roomCode.value);
            router.push({ name: 'create-game', params: { code: roomCode.value } });
        } catch (error) {
            console.error("Error al crear la sala:", error);
        }
    }
};


// Método para generar la sesión de un usuario anónimo
const submitAnonymousLogin = () => {
    // Prepara los datos a guardar
    const userData = {
        nickname: nickname.value,
        avatar: avatarImage.value,
        uuid: playerUuid.value,
    };

    // Guarda los datos en Session Storage
    sessionStorage.setItem('userData', JSON.stringify(userData));

    console.log('Sesión anónima creada correctamente en Session Storage.');
};


// Se inserta al jugador anónimo en el array de jugadores de la sala
const enterRoom = (code) => {
    axios.post('/api/enter-room', {
        code: code,
        nickname: nickname.value,
        avatar: avatarImage.value,
        uuid: playerUuid.value,
    }).then(response => {
        console.log(response.data.mensaje);
    }).catch(error => {
        console.error("Error al unirse a la sala: ", error);
    });
};

</script>


<style scoped>

@import './../style/anonymousUser.css';

</style>