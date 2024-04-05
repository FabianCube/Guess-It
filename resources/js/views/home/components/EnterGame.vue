<template>
    <div class="card border-0 shadow-sm popup-login">
        <div class="w-100">
            <button @click="toggleEnterGame()" id="closeLogin">
                <img src="/storage/icons/arrow-left.svg" alt="">
            </button>
        </div>
        <div class="card-body" style="width: 50%;">
            <h1 style="text-align: center;">CÓDIGO DE PARTIDA</h1>
            <form @submit.prevent>
                <div class="">
                    <!-- Nickname -->
                    <div class="mb-3">
                        <label for="nickname" class="form-label h4">Código</label>
                        <input id="nickname" type="text" class="form-control" v-model="nickname" required autofocus
                            autocomplete="username">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end mt-4">
                        <button @click="createRoom" class="btn-default btn-login">
                            PREPARADO!
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</template>

<script setup>
import { defineEmits, ref, onMounted } from 'vue';
import axios from 'axios';

const emits = defineEmits(['close-enterGame']);

function toggleEnterGame() {
    emits('close-enterGame');
}

const roomCode = ref();
const playerUuid = ref();

</script>


<style scoped>
#closeLogin {
    background-color: transparent;
    border: none;
}

.popup-login {
    width: 800px;
    height: 500px;
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
</style>