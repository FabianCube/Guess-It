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
            <form @submit.prevent="submitLogin">
                <div class="">
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label h4">{{ $t('Nickname') }}</label>
                        <input v-model="loginForm.email" id="email" type="email" class="form-control" required autofocus
                            autocomplete="username">
                        <!-- Validation Errors -->
                        <div class="text-danger mt-1">
                            <div v-for="message in validationErrors?.email">
                                {{ message }}
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end mt-4">
                        <router-link to="/create-game" class="btn-default btn-login"
                            :class="{ 'opacity-25': processing }" :disabled="processing">
                            PREPARADO!
                        </router-link>
                    </div>
                </div>
            </form>
        </div>
    </div>


</template>

<script setup>
import { defineProps, defineEmits, ref, onMounted } from 'vue';
import axios from 'axios';
import useAuth from '@/composables/auth';

const emits = defineEmits(['close-anonymous']);
const { loginForm, validationErrors, processing, submitLogin } = useAuth();

function toggleAnonymous() {
    emits('close-anonymous');
}

const baseAvatar = '/storage/avatars/';

const avatarImage = ref();

let avatarId = ref(1);

// Función para cargar el el nombre del archivo del avatar según si ID
const loadAvatar = () => {
    axios.get('/api/get-avatar/' + avatarId.value)
        .then(({ data }) => {
            console.log(data);
            avatarImage.value = baseAvatar + data.image;
        })
        .catch(error => {
            console.error("Error al obtener el avatar: ", error);
        });
};

// Cargamos el avatar inicial cuando el componente se monta
onMounted(loadAvatar);

// Método para cambiar el avatar
const changeAvatar = () => {
    if (avatarId.value === 4) {
        avatarId.value = 1;
    } else {
        avatarId.value += 1; // Incrementamos el ID
    }
    loadAvatar(); // Cargamos el nuevo avatar
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