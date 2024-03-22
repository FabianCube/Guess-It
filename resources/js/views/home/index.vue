<template>
    <div class="login">
        <login-popup @close-popup="toggleLogin"/> 
    </div>
    <div class="anonymous">
        <anonymous-user @close-anonymous="toggleAnonymous"/>
    </div>
    <div id="background"></div>
    <div class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
        <div class="w-100 flex flex-row justify-between py-5">
            <div class="col-2 flex flex-column justify-content-end align-items-end">
                <button class="btn-smll-default mb-5" style="border: none;"><img src="/storage/icons/info-circle.svg" alt=""></button>
                <button class="btn-smll-default" style="border: none;"><img src="/storage/icons/volume-on.svg" alt=""></button>
            </div>

            <div class="col-8 flex justify-center align-items-center flex-column pt-8">
                <img id="logo" src="/storage/guess-it-logo.svg"></img> 

                <button @click="toggleAnonymous()" to="/create-game" class="btn-default">CREAR PARTIDA</button>
                <router-link :to="{ name : 'public-posts.index'}" class="btn-default">UNIRSE A PARTIDA</router-link>
                
            </div>

            <div class="col-2 flex flex-column justify-content-between">
                <button @click="toggleLogin()" to="/register" class="btn-smll-default flex justify-content-center">
                    <img src="/storage/icons/account.svg" alt="">
                </button>
                <button class="btn-smll-default" style="border: none">
                    <img src="/storage/icons/friends.svg" alt="">
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref , onMounted } from 'vue';

onMounted(() => {
    const bg = document.getElementById('background');

    if (!bg) {
        console.error('Elemento #background no encontrado.');
        return;
    }

    document.addEventListener("mousemove", (e) => {
        const width = window.innerWidth / 2;
        const height = window.innerHeight / 2;

        const mouseX = e.clientX - width;
        const mouseY = e.clientY - height;

        // Ajusta estos multiplicadores para cambiar la sensibilidad del efecto
        const bgX = mouseX * 0.02; // Por ejemplo, 0.05 para un efecto sutil
        const bgY = mouseY * 0.02;

        // Aplica la transformación
        bg.style.transform = `translate(${bgX}px, ${bgY}px) translateZ(0)`;
    });
});

const isLoginPopupVisible = ref(true);
const isAnonymousPopupVisible = ref(true);

// Abrir cerrar popup de login
function toggleLogin()
{
    let login = document.querySelector('.login');
    let isOpen = login.classList.contains('active');
    isOpen ? login.classList.remove('active') : login.classList.add('active');
    isLoginPopupVisible.value = !isLoginPopupVisible.value;
}

// Abrir cerrar popup de usuario anónimo
function toggleAnonymous()
{
    let anonymous = document.querySelector('.anonymous');
    let isOpen = anonymous.classList.contains('active');
    isOpen ? anonymous.classList.remove('active') : anonymous.classList.add('active');
    isAnonymousPopupVisible.value = !isAnonymousPopupVisible.value;
}

</script>

<style scoped>

#logo
{
    width: 570px;
    height: auto;
}

.login
{
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 100;
    display: none;
    background-color: rgba(0,0,0,.25);
    backdrop-filter: blur(4px);
}

.anonymous
{
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 100;
    display: none;
    background-color: rgba(0,0,0,.25);
    backdrop-filter: blur(4px);
}

.active
{
    display: block!important;
}
</style>
