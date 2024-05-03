<template>
    <div class="modal">
        <div class="p-4 modal-content">
            <div class="w-100">
                <button @click="closeModal" id="closeInvitation">
                    <img src="/storage/icons/arrow-left.svg" alt="">
                </button>
            </div>
            <div class="d-flex flex-column align-items-center w-100">
                <h1>Invitar Amigos</h1>
                <div v-for="friend in props.friends" :key="friend.id" class="d-flex w-70">
                    <div>
                        <div class="me-3 avatar">
                            <img :src="`/storage/avatars/avatar${friend.avatar_id}.jpg`" alt="avatar">
                        </div>
                        <p class="mb-0">{{ friend.nickname }}</p>
                    </div>
                    <button @click="inviteFriend(friend.id)">Invitar</button>
                </div>
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
#closeInvitation {
    background-color: transparent;
    border: none;
}

.modal {
    display: flex;
    position: absolute;
    z-index: 100;
    width: 100%;
    height: 100%;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(4px);
}

.modal-content {
    margin-top: 10vh;
    height: 20vh;
    max-height: 40vh;
    background-color: white;
    border-radius: 20px;
    width: 80%;
    max-width: 25vw;
    overflow: hidden;
}

.avatar {
    border-radius: 50%;
    overflow: hidden;
    height: 4rem;
    width: 4rem;
    flex-shrink: 0;
}

.avatar img {
    height: 100%;
    width: auto;
}

</style>