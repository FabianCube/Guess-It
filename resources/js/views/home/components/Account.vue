<template>
    <div class="card border-0 shadow-sm popup-account">
        <div class="w-100">
            <button @click="toggleAccount()" id="closeLogin">
                <img src="/storage/icons/arrow-left.svg" alt="">
            </button>
        </div>
        <div class="card-body flex flex-column p-5" style="width: 100%">
            <div style="width: 100%; height: 50%; background-color: white;">
                a
            </div>
            <div class="info-container">
                <div class="tabs">
                    <div class="tab active">
                        <p>HISTORIAL</p>
                        <img src="/storage/icons/history-icon.svg" alt="">
                    </div>
                    <div class="tab">
                        <p>ESTADÍSTICAS</p>
                        <img src="/storage/icons/stats-icon.svg" alt="">
                    </div>
                    <div class="tab">
                        <p>AJUSTES</p>
                        <img src="/storage/icons/settings-icon.svg" alt="">
                    </div>
                </div>
                <div class="history">

                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import useAuth from '@/composables/auth';

const emits = defineEmits(['close-account']);
const { loginForm, validationErrors, processing, submitLogin } = useAuth();

function toggleAccount() {
    emits('close-account');
}
</script>

<style scoped>
#closeLogin {
    background-color: transparent;
    border: none;
}

.popup-account {
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

.info-container {
    width: 100%;
    height: 100%;
    background-color: #A05FD3;
    border-radius: 29px;
    padding: 13px;
}

.history
{
    background-color: white;
    width: 100%;
    height: 87%;
    border-radius: 0 23px 23px;
}

.tabs
{
    display: flex;
    flex-flow: row;
    margin: 0;
    padding: 0;
}

.tab
{
    height: 35px;
    width: 150px;
    background-color: #CDCDCD;
    color: #4E4E4E;
    margin-right: 10px;
    border-radius: 17px 17px 0 0;
    display: flex;
    flex-flow: row;
    justify-content: space-around;
    align-items: center;
}

.tab>p
{
    margin: 0;
}

.active
{
    background-color: #fff;
    color: black;
}
</style>