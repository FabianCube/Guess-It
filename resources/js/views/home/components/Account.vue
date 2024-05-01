<!-- Componente para Cuenta usuario -->
<template>
    <div class="card border-0 shadow-sm popup-account">
        <div class="w-100">
            <button @click="toggleAccount()" id="closeLogin">
                <img src="/storage/icons/arrow-left.svg" alt="">
            </button>
        </div>
        <div class="card-body flex flex-column p-5" style="width: 100%">
            <div class="flex justify-content-end container-info" style="width: 100%; height: 50%;">
                <div class="user-container">
                    <div class="info-user">
                        <h3>{{ user.nickname }}</h3>
                        <h3>NIVEL: {{ user.level }}</h3>
                        <button class="btn-default btn-logout" @click="logout">LOGOUT!</button>
                    </div>
                    <div class="avatar-image">
                        <img :src="avatar" alt="">
                    </div>
                </div>
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
                    <account-history/>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, onMounted, watch } from 'vue';
import useAuth from '@/composables/auth';
import axios from 'axios';

const emits = defineEmits(['close-account']);
const { isLoggedIn, loginForm, validationErrors, processing, submitLogin , logout } = useAuth();
const user = ref({});
const avatar = ref();
const activeTab = ref();

onMounted(() => {
    if(isLoggedIn())
    {
        getUser();
    }
})

watch(() => isLoggedIn(), () => {
    getUser();
});

const getUser = async () => {
    
    return axios.get('/api/user')
        .then(response => {
            console.log(response.data.data);
            user.value = response.data.data;
            avatar.value = "/storage/avatars/avatar" + response.data.data.avatar_id + ".jpg";
        })
}

const toggleAccount = () => {
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

.container-info
{
    padding-bottom: 8px;
}

.user-container
{
    width: 400px;
    height: auto;
    background-color: #fff;
    border: #3E3E3E 3px solid;
    border-radius: 23px;
    display: flex;
    padding: 20px;
    justify-content: space-between;
    align-items: center;
}

.info-user>h3{
    font-size: 20px;
    color: #3E3E3E;
    margin: 5px;
}

.avatar-image
{
    width: 100px;
    height: 100px;
    overflow: hidden;
    border-radius: 50px;
}

.avatar-image>img
{
    width: 100%;
    height: auto;
}

.btn-logout
{
    border: none;
    border-radius: 6px;
    width: 100px!important;
    height: 30px!important;
    font-size: 14px!important;
    line-height: 30px!important;
    margin: 0;

    background-color: #df1a1a;
    box-shadow: 0 5px #861713;
}

</style>