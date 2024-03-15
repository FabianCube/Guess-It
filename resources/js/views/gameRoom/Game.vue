<template>

        <div class="container mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn-smll-default">
                    <img src="/storage/icons/home-05.svg" alt="">
                </button>
    
                <img id="logo" src="/storage/guess-it-logo.svg" class="logo"></img>
    
                <button class="btn-smll-default">
                    <img src="/storage/icons/volume-on.svg" alt="">
                </button>
            </div>
    
            <!-- CANVAS & CHAT -->
            <div class="col-12 flex flex-row bg-primary p-0">
                <div class="col-7 p-0">
                    <!-- CANVAS -->
                    <div>
                        <div class="canvas">
                            <!-- COMPONENTE CANVAS -->
                            <div>
                                <canvas-component :new-canvas="newCanvas" @canvasupdate="sendCanvas"></canvas-component>
                            </div>
                            <!-- FIN COMPONENTE CANVAS -->
                        </div>
                    </div>
    
                    <!-- JUGADORES -->
                    <div>
                        <div class="jugadores">
                            <!-- COMPONENTE JUGADORES -->
    
                            <!-- FIN COMPONENTE JUGADORES -->
                        </div>
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

    
    
</template>

<script setup>
</script>

<style>

.testCanvas
{
    width: 100%;
    height: 600px;
    background-color: yellow;
    border-radius: 12px;
}
.chat
{
    width: 100%;
    height: 600px;
    background-color: red;
    border-radius: 12px;
    padding-left: 20px;
}

</style>