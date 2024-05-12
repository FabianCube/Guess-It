<template>
    <div class="modal">
        <div class="p-4 modal-content">
            <div class="w-100">
                <button @click="closeModal(), playHovers('/storage/sounds/click-back.mp3')" id="closeInvitation">
                    <img src="/storage/icons/arrow-left.svg" alt="">
                </button>
            </div>
            <div class="d-flex flex-column align-items-center w-100">
                <h1 class="mb-4">Invitar Amigos</h1>
                <div v-for="friend in props.friends" :key="friend.id" class="d-flex justify-content-between w-75 etiqueta">
                    <div class="d-flex align-items-center">
                        <div class="me-3 avatar">
                            <img :src="`/storage/avatars/avatar${friend.avatar_id}.jpg`" alt="avatar">
                        </div>
                        <p class="mb-0">{{ friend.nickname }}</p>
                    </div>
                    <button @click="inviteFriend(friend.id)" class="btn-invite-small">INVITAR</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';
const hovers = ref(null);

const props = defineProps({
    friends: Array,
    roomCode: String
});

const emits = defineEmits(['close']);

const playHovers = (soundFile) => {
    hovers.value = new Audio(soundFile);
    hovers.value.volume = 0.25;
    hovers.value.play();
}

const closeModal = () => {
    emits('close');
};

// Envia una invitación a partida a otro usuario
const inviteFriend = async (friendId) => {
    try {
        const response = await axios.post((`/api/invite-user`), {
            roomCode: props.roomCode,
            userId: friendId
        });
    } catch (error) {
        console.error('Error obteniendo detalles de la sala:', error);
    }
};
</script>

<style scoped>

@import './../style/friendsList.css';

</style>