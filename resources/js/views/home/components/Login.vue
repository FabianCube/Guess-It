<!-- Componente para Login -->
<template>
    <div class="card border-0 shadow-sm popup-login">
        <div class="w-100 pt-2">
            <button @click="toggleLogin()" id="closeLogin">
                <img src="/storage/icons/arrow-left.svg" alt="">
            </button>
        </div>
        <div class="card-body">
            <h1 style="text-align: center;">INICIAR SESIÓN</h1>
            <form @submit.prevent="submitLogin">
                <div class="">
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ $t('email') }}</label>
                        <input v-model="loginForm.email" id="email" type="email" class="form-control" required autofocus
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
                        <input v-model="loginForm.password" id="password" type="password" class="form-control" required
                            autocomplete="current-password">
                        <!-- Validation Errors -->
                        <div class="text-danger-600 mt-1">
                            <div v-for="message in validationErrors?.password">
                                {{ message }}
                            </div>
                        </div>
                        <router-link :to="{ name: 'auth.forgot-password' }">
                            {{ $t('forgot_password') }}
                        </router-link>
                    </div>
                    <!-- Remember me -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" v-model="loginForm.remember"
                            id="flexCheckIndeterminate">
                        <label class="form-check-label" for="flexCheckIndeterminate">
                            {{ $t('remember_me') }}
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex align-items-center justify-end flex-column mt-4">
                        <button class="btn-default btn-login" :class="{ 'opacity-25': processing }" :disabled="processing">
                            ENTRAR!
                        </button>
                        <p class="m-0">O</p>
                        
                    </div>
                </div>
                
            </form>
            <button @click="toggleRegister()" class="btn-default btn-register mb-5">
                CREAR CUENTA!
            </button>
        </div>
    </div>


</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import useAuth from '@/composables/auth';

// emits para mandar al padre (index.vue) el emit de que se ha ejecutado una funcion.
const emits = defineEmits(['close-popup', 'open-register']);
const { loginForm, validationErrors, processing, submitLogin } = useAuth();

// funcion para abrir y cerrar el pop up de login
function toggleLogin() {
    emits('close-popup');
}


// funcion para abrir el popup de register
function toggleRegister()
{
    emits('open-register');
}

</script>
<style scoped>

@import './../style/login.css';

</style>