<!-- Componente para Registrar -->
<template>
    <div class="card border-0 shadow-sm popup-login">
        <div class="w-100">
            <button @click="toggleLogin()" id="closeLogin">
                <img src="/storage/icons/arrow-left.svg" alt="">
            </button>
        </div>
        <div class="card-body">
            <form @submit.prevent="submitRegister">
                <div class="">
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
                        <input v-model="registerForm.email" id="email" type="email" class="form-control" autocomplete="username">
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
                        <input v-model="registerForm.password" id="password" type="password" class="form-control" autocomplete="current-password">
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
                        <input v-model="registerForm.password_confirmation" id="password_confirmation" type="password" class="form-control" autocomplete="current-password">
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

const { registerForm, validationErrors, processing, submitRegister } = useAuth();

// TODO añadir campos necesarios para el registro.

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
</style>