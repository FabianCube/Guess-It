<template>
    <div class="background min-h-screen sm:items-center py-4">
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
                <div class="col-8 p-0" style="height: 622.5px; width: 830px;">
                    <!-- CANVAS -->
                    <div style="width: 100%; height: 100%;border-radius: 12px; position: relative;">
                        <!-- COMPONENTE STATUS BAR -->
                        <status-bar />
                        <!-- COMPONENTE CANVAS -->
                        <canvas-component :new-canvas="newCanvas" @canvasupdate="sendCanvas"></canvas-component>
                    </div>
                </div>

                <div class="col-4 flex flex-row chat-container">
                    <!-- CHAT -->
                    <div class="p-3 chat flex flex-column justify-content-between">
                        <!-- COMPONENTE CHAT  -->
                        <div class="col-8 p-0" style="height: 90%; width:100%;">
                            <chat-messages :messages="messages"></chat-messages>
                        </div>
                        <div class="col-4 p-0" style="width:100%">
                            <chat-form @messagesent="addMessage"></chat-form>
                        </div>
                    </div>

                    <!-- PLAYER INFO -->
                    <div class="p-0 info-jugador">
                    <!-- COMPONENTE INFO JUGADOR -->
                        <div class="jugadores">

                        </div>
                    </div>
                </div>
            </div>
            <!--  -->

        </div>
    </div>
</template>

<script setup>
</script>

<style>
.background {
    background-image: url('/storage/background-play-game.svg');
    background-size: cover;
    background-repeat: no-repeat;
}

.canvas-container {
    height: 100%;
    width: 100%;
    position: relative;
}

.canvas {
    width: 100%;
    height: 500px;
    border-radius: 12px;
}

.chat {
    width: 100%;
    height: 622.5px;
    background-color: white;
    border-radius: 12px 0 0 12px;
}

.chat-container {
    padding: 0 0 0 0px;
}

.info-jugador
{
    width: 300px;
    height: 100%;
}

.jugadores
{
    width: 100%;
    height: 100%;
    background-color: rgb(218, 218, 218);
    border-radius: 0 12px 12px 0;
}
</style>