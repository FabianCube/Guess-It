<!-- Componente para Cuenta usuario -->
<template>
    <div class="card border-0 shadow-sm popup-account">
        <div class="w-100">
            <button @click="toggleAccount(), playHovers('/storage/sounds/click-back.mp3')" id="closeLogin">
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
                        <p class="title-tab">HISTORIAL</p>
                        <img src="/storage/icons/history-icon.svg" alt="">
                    </div>
                    <div @click="changeFocusTab($event.target)" class="tab">
                        <p class="title-tab">ESTADÍSTICAS</p>
                        <img src="/storage/icons/stats-icon.svg" alt="">
                    </div>
                    <div @click="changeFocusTab($event.target)" class="tab">
                        <p class="title-tab">AJUSTES</p>
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
                <div v-if="user.admin_rights == 1" class="content-tab">
                    <admin-players v-if="activeTab == 0" />
                    <admin-history v-else-if="activeTab == 1" />
                    <account-settings v-else-if="activeTab == 2" :user="user" />
                </div>
                <div v-else class="content-tab">
                    <account-history v-if="activeTab == 0" :historyData="historyData" :user="user" />
                    <account-stats :historyData="historyData" v-else-if="activeTab == 1" :user="user" />
                    <account-settings v-else-if="activeTab == 2" :user="user" />
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

const emits = defineEmits(['close-account']);
const { isLoggedIn, logout } = useAuth();
const user = ref({});
const avatar = ref();
const activeTab = ref(0); // index 0-2 (history, stats, settings)
const historyData = ref({});
const hovers = ref(null);


const playHovers = (soundFile) => {
    hovers.value = new Audio(soundFile);
    hovers.value.volume = 0.5;
    hovers.value.play();
}

// al cargar la página obtendremos los datos del user y del historial.
onMounted(() => {
    if (isLoggedIn()) {
        getUser();
        getHistory();
    }
})

// hacemos un watch de isLoggedIn para ver si cambia el estado a logged o viceversa.
watch(() => isLoggedIn(), () => {
    getUser();
});

const getUser = async () => {
//  hacemos una peticion a al api para obtener los datos del usuario.
    axios.get('/api/user')
        .then(response => {
            console.log(response.data.data);
            user.value = response.data.data;
            avatar.value = "/storage/avatars/avatar" + response.data.data.avatar_id + ".jpg";
        })
}

const getHistory = async () => {
// hacemos una petición a la api para obtener los datos de el historial.
    await axios.get(`/api/account-history/${user.value.id}`)
        .then(response => {
            historyData.value = response.data;
        })
}

// funcion para cambiar de tab dentro del panel de account
const changeFocusTab = (element) => {

    // quitamos a todos los divs la clase de 'active' para posteriormente
    // asignarle el 'active' unicamente al que nos interesa.
    document.querySelectorAll('.tab').forEach(el => {
        el.classList.remove('active');
    });

    if (element.classList.contains('tab')) {
        element.classList.add('active');
    }
    else {
        element.parentElement.classList.add('active');
    }

    activeTab.value = activeTabIndex();
}

// funcion para obtener el index de la tab que está actualmente activa.
const activeTabIndex = () => {
    let tabs = document.querySelectorAll('.tab');
    let activeIndex = -1;

    tabs.forEach((tab, index) => {
        if (tab.classList.contains('active')) {
            activeIndex = index;
            return;
        }
    });

    return activeIndex;
}

// funcion para cerrar account.
const toggleAccount = () => {
    emits('close-account');
}
</script>

<style scoped>

@import './../style/account.css';

</style>