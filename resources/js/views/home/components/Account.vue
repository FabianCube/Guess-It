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
                        <h3 class="rainbow-text">NIVEL: {{ user.level }}</h3>
                        <button class="btn-default btn-logout" @click="logout">LOGOUT!</button>
                    </div>
                    <div class="avatar-image">
                        <img :src="avatar" alt="">
                    </div>
                </div>
            </div>
            <div class="info-container">

                <!-- TABS -->
                <div v-if="user.admin_rights == 0" class="tabs">
                    <div @click="changeFocusTab($event.target)" class="tab active">
                        <p>HISTORIAL</p>
                        <img src="/storage/icons/history-icon.svg" alt="">
                    </div>
                    <div @click="changeFocusTab($event.target)" class="tab">
                        <p>ESTADÍSTICAS</p>
                        <img src="/storage/icons/stats-icon.svg" alt="">
                    </div>
                    <div @click="changeFocusTab($event.target)" class="tab">
                        <p>AJUSTES</p>
                        <img src="/storage/icons/settings-icon.svg" alt="">
                    </div>
                </div>
                <div v-else class="tabs">
                    <div @click="changeFocusTab($event.target)" class="tab active">
                        <p>JUGADORES</p>
                        <img src="/storage/icons/history-icon.svg" alt="">
                    </div>
                    <div @click="changeFocusTab($event.target)" class="tab">
                        <p>PARTIDAS</p>
                        <img src="/storage/icons/stats-icon.svg" alt="">
                    </div>
                    <div @click="changeFocusTab($event.target)" class="tab">
                        <p>AJUSTES</p>
                        <img src="/storage/icons/settings-icon.svg" alt="">
                    </div>
                </div>
                <!-- ========== -->

                <!-- COMPONENTS -->
                <div v-if="user.admin_rights == 0" class="content-tab">
                    <account-history :historyData="historyData" v-if="activeTab == 0" :user="user"/>
                    <account-stats :historyData="historyData" v-else-if="activeTab == 1" :user="user"/>
                    <account-settings v-else-if="activeTab == 2" :user="user"/>
                </div>
                <div v-else class="content-tab">
                    <admin-players v-if="activeTab == 0" />
                    <admin-history v-else-if="activeTab == 1" />
                    <account-settings v-else-if="activeTab == 2" :user="user"/>
                </div>
                <!-- ======== -->
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, onMounted, watch } from 'vue';
import useAuth from '@/composables/auth';
import axios from 'axios';

const emits = defineEmits([ 'close-account' ]);
const { isLoggedIn, logout } = useAuth();
const user = ref({});
const avatar = ref();
const activeTab = ref(0); // index 0-2 (history, stats, settings)
const historyData = ref({});

onMounted(() => {
    if(isLoggedIn())
    {
        // getUser();
        getHistory();
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

const getHistory = async () => {

    await axios.get(`/api/account-history/${user.value.id}`)
        .then(response => {
            historyData.value = response.data;
            console.log(response.data[0].id);
        })
}

const changeFocusTab = (element) => {

    document.querySelectorAll('.tab').forEach(el => {
        el.classList.remove('active');
    });

    if(element.classList.contains('tab'))
    {
        element.classList.add('active');
    }
    else
    {
        element.parentElement.classList.add('active');
    }

    activeTab.value = activeTabIndex();
    // console.log(activeTab.value)
}

const activeTabIndex = () => {
    let tabs = document.querySelectorAll('.tab');
    let activeIndex = -1;

    tabs.forEach((tab, index) => {
        if (tab.classList.contains('active')) 
        {
            activeIndex = index;
            return;
        }
    });

    return activeIndex;
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

.content-tab
{
    background-color: white;
    width: 100%;
    height: 87%;
    border-radius: 0 23px 23px;
    padding: 10px 30px 5px 30px;
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
    cursor: pointer;
    transition: all .1s;
}

.tab>p
{
    user-select: none; /* Para que no se pueda seleccionar el texto */
    transition: all .1s;
}

.tab:hover p
{
    font-size: 16px;
}

.tab>p
{
    margin: 0;
}

.active
{
    background-color: #fff;
    color: black;
    width: 200px;
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

@keyframes rainbow 
{
    0% { color: red; }
    14% { color: orange; }
    28% { color: yellow; }
    42% { color: green; }
    57% { color: blue; }
    71% { color: indigo; }
    85% { color: violet; }
    100% { color: red; }
}

.rainbow-text {
    animation: rainbow 6s infinite;
}

</style>