<template>
    <div class="login">
        <button @click="toggleLogin()">close</button>
        <login-popup />
    </div>
    <div id="background" class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
        <div class="w-100 flex flex-row justify-between py-5">
            <div class="col-2 flex flex-column justify-content-end align-items-end">
                <button class="btn-smll-default mb-5" style="border: none;"><img src="/storage/icons/info-circle.svg" alt=""></button>
                <button class="btn-smll-default" style="border: none;"><img src="/storage/icons/volume-on.svg" alt=""></button>
            </div>

            <div class="col-8 flex justify-center align-items-center flex-column pt-8">
                <img id="logo" src="/storage/guess-it-logo.svg"></img> 

                <router-link to="/create-game" class="btn-default">CREAR PARTIDA</router-link>
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

//Control de movimiento del fondo
document.addEventListener('mousemove', function (e) {
    let anchura = window.innerWidth / 2;
    let altura = window.innerHeight / 2;
    let mouseX = e.clientX;
    let mouseY = e.clientY;
    let fondo = document.getElementById('background');

    // Los valores divididos controlan la sensibilidad del movimiento del fondo
    let posX = (mouseX - anchura) / anchura * -5;
    let posY = (mouseY - altura) / altura * -5; 

    // Actualiza el estilo del fondo
    fondo.style.backgroundPosition = `calc(50% + ${posX}px) calc(50% + ${posY}px)`;
});

// Abrir cerrar poop up de login
function toggleLogin()
{
    let login = document.querySelector('.login');
    let isOpen = login.classList.contains('active');
    isOpen ? login.classList.remove('active') : login.classList.add('active');
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

.active
{
    display: block!important;
}
</style>
