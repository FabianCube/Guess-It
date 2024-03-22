<template>
    <div class="card border-0 shadow-sm popup-login">
        <div class="w-100">
            <button @click="toggleAnonymous()" id="closeLogin">
                <img src="/storage/icons/arrow-left.svg" alt="">
            </button>
        </div>
        <div class="card-body" style="width: 50%;">
            <h1 style="text-align: center;">NOMBRE Y ASPECTO</h1>
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

                    <!-- Buttons -->
                    <div class="flex items-center justify-end mt-4">
                        <router-link to="/create-game" class="btn-default btn-login" :class="{ 'opacity-25': processing }" :disabled="processing">
                            PREPARADO!
                        </router-link>
                    </div>
                </div>
            </form>
        </div>
    </div>


</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import useAuth from '@/composables/auth';

const emits = defineEmits(['close-anonymous']);
const { loginForm, validationErrors, processing, submitLogin } = useAuth();

function toggleAnonymous() {
    emits('close-anonymous');
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

.btn-login
{
    width: 100%;
    height: 65px;
    border: none;
    margin: 0 0 15px 0;
    line-height: 65px;
    font-size: 2rem;
}

.form-control
{
    border-radius: 12px!important;
    border: solid 2px #757575;
}
</style>