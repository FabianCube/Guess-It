<template>
    <div id="background-game"></div>
    <div class="min-h-screen sm:items-center py-4 main-content">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <router-link to="/" class="btn-smll-default">
                    <img src="/storage/icons/home-05.svg" alt="">
                </router-link>

                <img id="logo" src="/storage/guess-it-logo.svg" class="logo"></img>

                <button class="btn-smll-default">
                    <img src="/storage/icons/volume-on.svg" alt="">
                </button>
            </div>

            <!-- CANVAS & CHAT -->
            <div class="container flex flex-row p-0 mt-5 justify-content-between">

                <!-- PLAYER INFO -->
                <div class="col-1 p-0 info-jugador">
                    <!-- COMPONENTE INFO JUGADOR -->
                    <info-players />
                </div>

                <div class="col-8 p-0" style="height: 622.5px; width: 830px;">
                    <!-- CANVAS -->
                    <div style="width: 100%; height: 100%;border-radius: 12px; position: relative;">
                        <!-- COMPONENTE STATUS BAR -->
                        <status-bar />
                        <!-- COMPONENTE CANVAS -->
                        <canvas-component :user="user" :new-canvas="newCanvas" @canvasupdate="sendCanvas"></canvas-component>
                    </div>
                </div>

                <div class="col-3 flex flex-row chat-container">
                    <!-- CHAT -->
                    <div class="p-3 chat flex flex-column justify-content-between">
                        <!-- COMPONENTE CHAT  -->
                        <div class="col-8 p-0" style="height: 95%; width:100%;">
                            <chat-messages :messages="messages"></chat-messages>
                        </div>
                        <div class="col-4 p-0" style="width:100%">
                            <chat-form @messagesent="addMessage" :user="user"></chat-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { onBeforeMount, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import useAuth from '@/composables/auth';

const route = useRoute();
const { isLoggedIn } = useAuth();
const user = ref();
const messages = ref([]);
const newCanvas = ref();
const roomCode = ref();

const addMessage = (newMessage) => {
    messages.value.push(newMessage);

    console.log("[Game.vue]:addMessage:user.nickname -> " + newMessage.user.nickname)
    console.log("[Game.vue]:addMessage:message -> " + newMessage.message)

    // axios.post('/api/messages', newMessage).then(response => {
    //     console.log(response.data);
    // }).catch(err => {console.error("Error sdanslndajndkjans", err)});

    axios.post('/api/messages', {
        user: newMessage.user,
        message: newMessage.message,
        code: roomCode.value
    }).then(response => {
        console.log(response.data.status);
    }).catch(error => {
        console.error("Error sdanslndajndkjans", error);
    });

    console.log("[Game.vue]:addMessage:messages{} -> " + messages.value);
}

const sendCanvas = (canvas) => {

    console.log("[Game.vue]:sendCanvas:user.nickname -> " + canvas.user.nickname)
    console.log("[Game.vue]:sendCanvas:canvas -> " + canvas.canvas)

    axios.post('/api/canvas', {
        user: canvas.user,
        canvas: canvas.canvas,
        code: roomCode.value
    }).then(response => {
        console.log(response.data);
    }).catch(error => {
        console.error("Error sdanslndajndkjans", error);
    });
}

onBeforeMount(async () => {
    roomCode.value = route.params.code;
    console.log('Playing in channel ==== room-' + roomCode.value);
})

onMounted(() => {
    getUserData();
    listenEventMessageSent();
    listenEventCanvasUpdate();

    const bg = document.getElementById('background-game');

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

    console.log("User: " + user.value.nickname)
});

const listenEventMessageSent = () => {
    console.log("[Game.vue]:listenEventMessageSent: Entrado!");

    window.Echo.channel('room-' + roomCode.value)
        .listen('.MessageSent', (e) => {
            console.log("[Game.vue]:listenEventMessageSent:.MessageSent -> " + e.message);

            // messages.value.push(e);
            messages.value.push({
                message: e.message,
                user: e.user
            });
        });
}

const listenEventCanvasUpdate = () => {
    console.log("[Game.vue]:listenEventCanvasUpdate: Entrado!");

    window.Echo.channel('room-' + roomCode.value)
        .listen('.CanvasUpdate', (e) =>{
            console.log("[Game.vue]:listenEventCanvasUpdate:.CanvasUpdate -> " + e.canvas);

            newCanvas.value = e.canvas;
        });
}

const getUserData = async () => {

    if (isLoggedIn()) {
        console.log("[INFO]: Entrando como usuario registrado.");

        const userData = await axios.get('/getUserData');
        user.value = userData.data;

        console.log(userData.nickname);
    }
    else {
        console.log("[INFO]: Entrando como usuario anónimo.");

        let getUserData = sessionStorage.getItem('userData');
        const userData = JSON.parse(getUserData);
        user.value = userData;

        if (user.value !== undefined) {
            console.log("User Nickname -> " + user.value.nickname);
            console.log("User UUID -> " + user.value.uuid);
        }
        else {
            console.log("[ERROR]: Al obtener datos de usuario anónimo.");
        }
    }
}


</script>

<style>
.canvas-container {
    height: 100%;
    width: 100%;
    position: relative;
}

.canvas {
    width: 100%;
    height: 500px;
    border-radius: 22px;
}

.chat {
    width: 100%;
    height: 622.5px;
    background-color: #FFFDFD;
    border-radius: 22px;
}

.chat-container {
    padding: 0 0 0 0px;
}

.info-jugador {
    height: 622.5px;
}

.main-content {
    animation: scaleUp 0.7s ease forwards;
    transform-origin: center;
    will-change: transform, opacity;
}

@keyframes scaleUp {
    from {
        transform: scale(0.1);
        opacity: 0;
    }

    to {
        transform: scale(1);
        opacity: 1;
    }
}
</style>