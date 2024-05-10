<!-- Componente para Registrar -->
<template>
    <div class="card border-0 shadow-sm popup-login">
        <div class="w-100 p-2">
            <button @click="toggleLogin()" id="closeLogin">
                <img src="/storage/icons/arrow-left.svg" alt="">
            </button>
        </div>
        <div class="card-body">
            <form @submit.prevent="submitRegister">
                <div class="">
                    <div class="avatar-wrapper">
                        <div class="image-container">
                            <img :src="avatarImage" alt="Avatar" class="avatar-image">
                            <input v-model="registerForm.avatar_id" type="text" id="currentAvatarId" hidden>
                        </div>
                        <a class="change-avatar" @click="changeAvatar">
                            <img src="/storage/icons/next-avatar.svg" alt="Next-avatar">
                        </a>
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ $t('name') }}</label>
                        <input v-model="registerForm.name" id="name" type="text" class="form-control" autofocus>
                        <!-- Validation Errors -->
                        <div class="text-danger mt-1">
                            <div v-for="message in validationErrors?.name">
                                {{ message }}
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ $t('email') }}</label>
                        <input v-model="registerForm.email" id="email" type="email" class="form-control"
                            autocomplete="username">
                        <!-- Validation Errors -->
                        <div class="text-danger mt-1">
                            <div v-for="message in validationErrors?.email">
                                {{ message }}
                            </div>
                        </div>
                    </div>
                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            {{ $t('password') }}
                        </label>
                        <input v-model="registerForm.password" id="password" type="password" class="form-control"
                            autocomplete="current-password">
                        <!-- Validation Errors -->
                        <div class="text-danger-600 mt-1">
                            <div v-for="message in validationErrors?.password">
                                {{ message }}
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            {{ $t('confirm_password') }}
                        </label>
                        <input v-model="registerForm.password_confirmation" id="password_confirmation" type="password"
                            class="form-control" autocomplete="current-password">
                        <!-- Validation Errors -->
                        <div class="text-danger-600 mt-1">
                            <div v-for="message in validationErrors?.password_confirmation">
                                {{ message }}
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end mt-4">
                        <button class="btn btn-primary" :class="{ 'opacity-25': processing }" :disabled="processing">
                            {{ $t('register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>

import useAuth from '@/composables/auth'
import { defineEmits, ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const { registerForm, validationErrors, processing, submitRegister } = useAuth();
const baseAvatar = '/storage/avatars/';
const router = useRouter();
const avatarImage = ref();
let avatarId = ref(1);

// Asigno el avatar_id para usarlo en registerForm (input)

// TODO añadir campos necesarios para el registro.
const loadAvatar = () => {
    axios.get('/api/get-avatar/' + avatarId.value)
    .then(({ data }) => {
        document.getElementById('currentAvatarId').value = avatarId;
        avatarImage.value = baseAvatar + data.image;
        })
        .catch(error => {
            console.error("Error al obtener el avatar: ", error);
        });
};

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
    height: 700px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 100px;
    border-radius: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Lilita One', sans-serif;
}

@media (max-width: 820px)
{
    .popup-login
    {
        width: 90vw;
        border-radius: 15px;
        padding: 0;
        
    }

    .card-body
    {
        width: 80%!important;
        padding: 30px!important;
    }

    .card-body>h1
    {
        font-size: 2rem;
    }
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
    background-color: blue;
}

.avatar-wrapper {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px auto;
    width: fit-content;
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