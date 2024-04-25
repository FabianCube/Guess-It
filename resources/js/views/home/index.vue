<template>
    <div class="login">
        <!-- COMPONENTE LOGIN DE USUARIO -->
        <login-popup @close-popup="toggleLogin" @open-register="toggleRegister" />
    </div>
    <div class="register">
        <!-- COMPONENTE DE REGISTRO -->
        <register-popup />
    </div>
    <div class="account">
        <!-- COMPONENTE DE PERFIL DE USUARIO -->
        <account-management @close-account="toggleAccount" />
    </div>
    <div class="anonymous">
        <!-- COMPONENTE LOGIN DE USUARIO ANÓNIMO -->
        <anonymous-user @close-anonymous="toggleAnonymous" :roomCode="passedRoomCode" />
    </div>
    <div class="enter-game">
        <!-- COMPONENTE PARA ENTRAR A PARTIDA CON CÓDIGO -->
        <enter-game @close-enterGame="toggleEnterGame" @open-anonymous="enterAnonymous" />
    </div>
    <div id="background"></div>
    <div class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
        <div class="w-100 flex flex-row justify-between py-5">
            <div class="col-2 flex flex-column justify-content-end align-items-end">
                <button class="btn-smll-default mb-5" style="border: none;"><img src="/storage/icons/info-circle.svg"
                        alt=""></button>
                <button class="btn-smll-default" style="border: none;"><img src="/storage/icons/volume-on.svg"
                        alt=""></button>
            </div>

            <div class="col-8 flex justify-center align-items-center flex-column pt-8">
                <img id="logo" src="/storage/guess-it-logo.svg" class="shake-img"></img>

                <button @click="toggleAnonymous()" to="/create-game" class="btn-default">CREAR PARTIDA</button>
                <button @click="toggleEnterGame()" class="btn-default">UNIRSE A PARTIDA</button>

            </div>

            <div class="col-2 flex flex-column justify-content-between">
                <button v-if="logged" @click="toggleAccount()" class="btn-smll-default flex justify-content-center">
                    <div class="avatar-image">
                        <img src="/storage/avatars/avatar1.jpg" alt="">
                    </div>
                </button>
                <button v-else @click="toggleLogin()" class="btn-smll-default flex justify-content-center">
                    <img src="/storage/icons/account.svg" alt="">
                </button>

                <button @click="logout" class="btn-smll-default" style="border: none">
                    <img src="/storage/icons/friends.svg" alt="">
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import useAuth from '@/composables/auth';

const { isLoggedIn, logout } = useAuth();

const logged = ref();

const passedRoomCode = ref();

const roomCode = ref();

const userRegistered = ref();

const router = useRouter();

onMounted(() => {

    logged.value = isLoggedIn();

    // Background animation
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
        const bgX = mouseX * 0.02;
        const bgY = mouseY * 0.02;

        // Aplica la transformación
        bg.style.transform = `translate(${bgX}px, ${bgY}px) translateZ(0)`;
    });
});

document.addEventListener('loggin-done', () => {
    toggleLogin();
})

// Abrir cerrar popup de login
function toggleLogin() {
    let login = document.querySelector('.login');
    let isOpen = login.classList.contains('active');
    isOpen ? login.classList.remove('active') : login.classList.add('active');

    logged.value = isLoggedIn();
    console.log("La sesion se ha " + (logged.value ? "iniciado" : "cerrado") + " correctamente");
}

// Abrir cerrar popup de usuario anónimo
function toggleAnonymous() {
    // Si el usuario está logueado creamos directamente la partida
    if (isLoggedIn()) {
        createRoom();
    } else {
        let anonymous = document.querySelector('.anonymous');
        let isOpen = anonymous.classList.contains('active');
        isOpen ? anonymous.classList.remove('active') : anonymous.classList.add('active');
    }

}

function toggleAccount() {
    let account = document.querySelector('.account');
    let isOpen = account.classList.contains('active');
    isOpen ? account.classList.remove('active') : account.classList.add('active');
}

function toggleRegister() {
    toggleLogin();
    let register = document.querySelector('.register');
    let isOpen = register.classList.contains('active');
    isOpen ? register.classList.remove('active') : register.classList.add('active');
}

// Abrir cerrar popup de unirse a partida
function toggleEnterGame() {
    let enterGame = document.querySelector('.enter-game');
    let isOpen = enterGame.classList.contains('active');
    isOpen ? enterGame.classList.remove('active') : enterGame.classList.add('active');
}

const enterAnonymous = (code) => {
    let anonymous = document.querySelector('.anonymous');
    let isOpen = anonymous.classList.contains('active');
    isOpen ? anonymous.classList.remove('active') : anonymous.classList.add('active');
    passedRoomCode.value = code;
}

const createRoom = async () => {
    try {
        const userId = await axios.get(`/api/get-user`);
        userRegistered.value = userId.data.uuid;

        console.log(userRegistered.value);

        const response = await axios.post(`/api/create-room/${userRegistered.value}`);
        roomCode.value = response.data.code;

        console.log("Sala creada con código:", roomCode.value);

        await enterRoom();
        router.push({ name: 'create-game', params: { code: roomCode.value } });
    } catch (error) {
        console.error("Error al crear la sala:", error);
    }
};

const enterRoom = () => {
    axios.post('/api/enter-room', {
        code: roomCode.value
    }).then(response => {
        console.log(response.data.mensaje);
    }).catch(error => {
        console.error("Error al unirse a la sala: ", error);
    });
};

</script>

<style scoped>
#logo {
    width: 570px;
    height: auto;
}

.login {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 100;
    display: none;
    background-color: rgba(0, 0, 0, .25);
    backdrop-filter: blur(4px);
}

.register {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 100;
    display: none;
    background-color: rgba(0, 0, 0, .25);
    backdrop-filter: blur(4px);
}

.anonymous {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 100;
    display: none;
    background-color: rgba(0, 0, 0, .25);
    backdrop-filter: blur(4px);
}

.account {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 100;
    display: none;
    background-color: rgba(0, 0, 0, .25);
    backdrop-filter: blur(4px);
}

.enter-game {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 100;
    display: none;
    background-color: rgba(0, 0, 0, .25);
    backdrop-filter: blur(4px);
}

.active {
    display: block !important;
}

.shake-img {
    display: inline-block;
    /* Necesario para que transform funcione */
    animation: shake 5s infinite;
}

@keyframes shake {

    0%,
    30% {
        transform: translate(0px, 0px);
    }

    30%,
    40% {
        transform: scale(0.95);
    }

    40%,
    50% {
        transform: scale(1);
    }

    60%,
    90% {
        transform: translate(0px, 0px);
    }

    92% {
        transform: translate(0px, 5px);
    }

    94% {
        transform: translate(0px, -5px);
    }

    96% {
        transform: translate(0px, 5px);
    }

    98% {
        transform: translate(0px, -5px);
    }

    100% {
        transform: translate(0px, 0px);
    }
}

.avatar-image {
    width: 80%;
    height: 70%;
    border-radius: 50px;
    overflow: hidden;
}

.avatar-image>img {
    width: 100%;
    height: auto;
}
</style>
