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
#closeLogin
{
    background-color: transparent;
    border: none;
}
.popup-login
{
    width: 800px;
    height: 600px;
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

.card-body
{
    width: 50%;
}

.btn-login
{
    width: 100%;
    height: 65px;
    border: none;
    margin: 0 0 15px 0;
    line-height: 65px;
    font-size: 2rem;
}

.btn-register
{
    width: 100%;
    height: 45px;
    border: none;
    margin: 0 0 15px 0;
    line-height: 45px;
    font-size: 1.6rem;
    border-radius: 12px;

    background-color: #6753DB;
    box-shadow: 0 10px #513EBE;
}


.form-control
{
    border-radius: 12px!important;
    border: solid 2px #757575;
}
</style>