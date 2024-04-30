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
    friends: Array,
    roomCode: String
});

const emits = defineEmits(['close', 'invited']);


const closeModal = () => {
    emits('close');
};

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
.modal {
    display: flex;
    position: absolute;
    z-index: 100;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(4px);
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 600px;
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