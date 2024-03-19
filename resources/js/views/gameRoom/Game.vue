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
            <div class="col-12 flex flex-row bg-primary p-0 mt-5">
                <div class="col-7 p-0">

                    <!-- CANVAS -->
                    <div style="width: 100%; height: 100%; background-color: yellow; border: 2px solid black; border-radius: 12px; position: relative;">
                        <!-- COMPONENTE CANVAS -->
                        <canvas-component :new-canvas="newCanvas" @canvasupdate="sendCanvas"></canvas-component>
                    </div>

                    <!-- JUGADORES -->
                    <div class="jugadores mt-5" style="width: 100%; height: 100px; background-color: #fff; border-radius: 12px;">
                        <!-- COMPONENTE JUGADORES -->
                        
                    </div>
                </div>

                <div class="col-5 p-0">
                    <!-- CHAT -->
                    <div>
                        <div class="chat">

                            <!-- COMPONENTE CHAT  -->
                            <div class="">
                                <div class="card-body chat">
                                    <!-- <chat-messages :messages="messages"></chat-messages> -->
                                </div>
                                <div class="card-footer">
                                    <!-- <chat-form @messagesent="addMessage" :user="user"></chat-form> -->
                                </div>
                            </div>
                            <!-- FIN COMPONENTE CHAT -->

                        </div>
                    </div>

                    <!-- PLAYER INFO -->
                    <div>
                        <div class="info-jugador">
                            <!-- COMPONENTE INFO JUGADOR -->

                            <!-- FIN COMPONENTE INOF JUGADOR -->
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
    height: 600px;
    background-color: red;
    border-radius: 12px;
    padding-left: 20px;
}
</style>