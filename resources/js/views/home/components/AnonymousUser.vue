<template>
    <div class="card border-0 shadow-sm popup-login">
        <div class="w-100">
            <button @click="toggleAnonymous()" id="closeLogin">
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
                        <button @click="createRoom" class="btn-default btn-login">
                            PREPARADO!
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</template>

<script setup>
import { defineEmits, ref, onMounted, onBeforeMount } from 'vue';
import axios from 'axios';
import useAuth from '@/composables/auth';
import { useRouter } from 'vue-router';
import { v4 as uuidv4 } from 'uuid';

const emits = defineEmits(['close-anonymous']);

function toggleAnonymous() {
    emits('close-anonymous');
}

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
    try {
        // Generamos el uuid del jugador anónimo
        playerUuid.value = uuidv4();

        const response = await axios.post(`/api/create-room/${playerUuid.value}`);
        roomCode.value = response.data.code;
        console.log("Sala creada con código:", roomCode.value);
        await submitAnonymousLogin();
        await enterRoom();
        router.push({ name: 'create-game', params: { code: roomCode.value } });
    } catch (error) {
        console.error("Error al crear la sala: ", error);
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
const enterRoom = () => {
    axios.post('/api/enter-room', {
        code: roomCode.value,
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

.avatar-wrapper {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px auto;
    width: fit-content;
}

.image-container {
    position: relative;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.change-avatar {
    position: absolute;
    bottom: 10px;
    right: -5px;
    border: none;
    background-color: transparent;
    cursor: pointer;
    z-index: 10;
}

.change-avatar:hover {
    animation: rotate 1s;
}

.change-avatar:active {
    animation: scale 0.5s;
}

@keyframes rotate {

    0%,
    50% {
        transform: rotate(-180deg);
    }

    50%,
    100% {
        transform: rotate(180deg);
    }
}

@keyframes scale {

    0%,
    50% {
        transform: scale(1.2);
    }

    50%,
    100% {
        transform: scale(1);
    }
}
</style>