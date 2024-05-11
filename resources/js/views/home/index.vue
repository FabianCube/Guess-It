<template>
    <div class="login">
        <!-- COMPONENTE LOGIN DE USUARIO -->
        <login-popup @close-popup="toggleLogin" @open-register="toggleRegister" />
    </div>
    <div class="register">
        <!-- COMPONENTE DE REGISTRO -->
        <register-popup @open-register="toggleRegister"/>
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
    <div id="total-container" class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
        <div class="row col-12 flex justify-between py-5 p-5 p-md-0">
            <div class="order-xs-2 order-lg-1 col-xs-12 col-sm-12 col-md-4 d-flex flex-md-column justify-content-sm-between justify-content-end align-items-start pl-sm-0 pl-8">
                <!-- <button class="btn-smll-default mb-5" style="border: none;">
                    <img src="/storage/icons/info-circle.svg" alt="">
                </button> -->
                <button 
                    @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')" 
                    @click="muteAllSounds(), playHovers('/storage/sounds/hover3.mp3')" 
                    class="btn-smll-default" style="border: none;">
                        <img v-if="isMusicMuted" src="/storage/icons/volume-off.svg" alt="">
                        <img v-else src="/storage/icons/volume-on.svg" alt="">
                </button>
            </div>

            <div class="order-xs-1 order-lg-2 col-xs-12 col-sm-12 col-md-4 flex justify-center align-items-center flex-column md:pt-8">
                <img id="logo" src="/storage/guess-it-logo.svg" class="shake-img"></img>

                <button 
                    @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')" 
                    @click="toggleAnonymous(), playHovers('/storage/sounds/hover3.mp3')" 
                    to="/create-game" class="btn-default">
                    CREAR PARTIDA
                </button>
                <button 
                    @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')"  
                    @click="toggleEnterGame(), playHovers('/storage/sounds/hover3.mp3')" 
                    class="btn-default">
                    UNIRSE A PARTIDA
                </button>
            </div>

            <div class="order-xs-3 order-lg-3 col-xs-12 col-sm-12 col-md-4 d-flex flex-md-column justify-content-between align-items-end pe-buttons">
                <button v-if="logged" @click="toggleAccount()" class="btn-smll-default flex justify-content-center">
                    <div 
                        @click="playHovers('/storage/sounds/hover3.mp3')" 
                        @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')" 
                        class="avatar-image">
                            <img src="/storage/avatars/avatar1.jpg" alt="">
                    </div>
                </button>
                <button 
                    @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')"  
                    v-else @click="toggleLogin(), playHovers('/storage/sounds/hover3.mp3')" 
                    class="btn-smll-default flex justify-content-center">
                    <img src="/storage/icons/account.svg" alt="">
                </button>

                <div class="d-flex flex-column justify-content-end">
                    <friends v-if="friendsList" />
                    <button 
                        @mouseenter="() => playHovers('/storage/sounds/hover1.mp3')"  
                        @click="toggleFriendsList(), playHovers('/storage/sounds/hover3.mp3')" 
                        class="btn-smll-default btn-friends" style="border: none">
                        <img src="/storage/icons/friends.svg" alt="">
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import useAuth from '@/composables/auth';
import sweetAlertNotifications from '@/utils/swal_notifications';

const { throwInviteMessage, throwInfoMessage } = sweetAlertNotifications();
const { isLoggedIn, logout } = useAuth();
const logged = ref();
const passedRoomCode = ref();
const roomCode = ref();
const userRegistered = ref();
const router = useRouter();
const friendsList = ref(false);
const user = ref();
const hovers = ref(null);
const backgroundMusic = ref(null);
const isMusicMuted = ref(true);


const playHovers = (soundFile) => {
    hovers.value = new Audio(soundFile);
    hovers.value.play();
}

const playBackgroundMusic = () => {
    backgroundMusic.value = new Audio('/storage/sounds/background-music.mp3');
    backgroundMusic.value.loop();
    backgroundMusic.value.play();
}

const muteAllSounds = () => {
    if(backgroundMusic.value.paused)
    {
        backgroundMusic.value.play();
        isMusicMuted.value = false;
    }
    else
    {
        backgroundMusic.value.pause();
        isMusicMuted.value = true;
    }
}

let UserPrivateChannel;

/* MOUNTING */

onMounted(async () => {
    backgroundMusic.value = null;
    playBackgroundMusic();

    logged.value = isLoggedIn();

    listenNewCache();
    listenDeleteCache();

    movingBackground();

    if (isLoggedIn()) {
        const userId = await axios.get(`/api/user`);

        UserPrivateChannel = window.Echo.private(`user.${userId.data.data.id}`);

        UserPrivateChannel
            .listen('.FriendRequest', (event) => {
                throwInfoMessage('Tienes una nueva petición de amistad!');
            });

        UserPrivateChannel
            .listen('.GameInvitation', (event) => {
                roomCode.value = event.roomCode;
                throwInviteMessage(
                    'Te han invitado! ¿Unirte a partida?',
                    async () => {
                        await enterRoom();
                        await localStorage.setItem('Sala', roomCode.value);
                        router.push({ name: 'create-game', params: { code: roomCode.value } });
                    },
                    () => console.log('Invitación rechazada')
                );
            });
    }
});

onUnmounted(() => {
    // Eliminar listeners de Echo
    if (UserPrivateChannel) {
        UserPrivateChannel.stopListening('.FriendRequest');
        UserPrivateChannel.stopListening('.GameInvitation');
    }

    backgroundMusic.value.pause();
    window.Echo.leave('cache');
});

document.addEventListener('loggin-done', () => {
    toggleLogin();
})

/* LISTENERS */

const listenNewCache = () => {
    window.Echo.channel('cache')
        .listen('.GetCache', (e) => {
            createCache(e.code,e.roomData);
        });
}

const listenDeleteCache = () => {
    window.Echo.channel('cache')
        .listen('.DeleteCache', (e) => {
            deleteCache(e.code);
        });
}

/* HANDLERS */

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

// Abrir cerrar popup de cuenta de usuario
function toggleAccount() {
    let account = document.querySelector('.account');
    let isOpen = account.classList.contains('active');
    isOpen ? account.classList.remove('active') : account.classList.add('active');
}

// Abrir cerrar popup de registro
function toggleRegister() {
    // toggleLogin();
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

// Abrir cerrar popup de unirse a partida
function toggleFriendsList() {
    if (isLoggedIn()) {
        friendsList.value = !friendsList.value;
    } else {
        toggleLogin();
    }
}


const enterAnonymous = (code) => {
    let anonymous = document.querySelector('.anonymous');
    let isOpen = anonymous.classList.contains('active');
    isOpen ? anonymous.classList.remove('active') : anonymous.classList.add('active');
    passedRoomCode.value = code;
}

/* FUNCTIONS */

// Crea e inserta al jugador en la sala
const createRoom = async () => {
    try {
        await axios.get('/api/user')
        .then(response => {
            console.log(response.data.data);
            userRegistered.value = response.data.data.id;
        })

        console.log(userRegistered.value);

        const response = await axios.post(`/api/create-room/${userRegistered.value}`);
        roomCode.value = response.data.code;

        console.log("Sala creada con código:", roomCode.value);

        await enterRoom();
        await localStorage.setItem('Sala', roomCode.value);
        router.push({ name: 'create-game', params: { code: roomCode.value } });
    } catch (error) {
        console.error("Error al crear la sala:", error);
    }
};

// Inserta al jugador en la sala
const enterRoom = () => {
    axios.post('/api/enter-room', {
        code: roomCode.value
    }).then(response => {
        console.log(response.data.mensaje);
    }).catch(error => {
        console.error("Error al unirse a la sala: ", error);
    });
};

const movingBackground = () => {
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
}

// Guarda en la caché las salas abiertas
const createCache = async (code,roomData) => {
    await axios.post('/api/create-cache', {
        code: code,
        roomData: roomData
    }).then(response => {
        console.log(response.data);
    }).catch(error => {
        console.error("Error al crear cache: ", error);
    });
}

// Elimina de la caché la salas cerradas
const deleteCache = async (code) => {
    await axios.post('/api/delete-cache', {
        code: code
    }).then(response => {
        console.log(response.data);
    }).catch(error => {
        console.error("Error al crear cache: ", error);
    });
}



</script>

<style scoped>

@import 'style/index.css';

</style>
