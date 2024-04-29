<template>
    <div class="modal">
        <div class="modal-content">
            <span @click="closeModal" class="close">&times;</span>
            <h3>Invitar Amigos</h3>
            <div v-for="friend in props.friends" :key="friend.id">
                {{ friend.nickname }}
                <button @click="inviteFriend(friend.id)">Invitar</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    friends: Array
});


console.log(props.friends);

const emits = defineEmits(['close', 'invited']);


const closeModal = () => {
    emits('close');
};

const inviteFriend = (friendId) => {
    // Aquí añadirías la lógica para procesar la invitación
    console.log(`Inviting friend ${friendId}`);
    // Emitir un evento cuando se invite a un amigo, si es necesario
    emits('invited', friendId);
};
</script>

<style scoped>
.modal {
    display: flex;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.6);
    /* Semi-transparente para oscurecer el fondo */
    backdrop-filter: blur(10px);
    /* Desenfoca el fondo, verifica la compatibilidad del navegador */
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 80%;
    /* O el ancho que prefieras */
    max-width: 600px;
    /* Para no ser demasiado grande en pantallas grandes */
}

.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
}
</style>