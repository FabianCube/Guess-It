<template>
    <div class="d-flex flex-column justify-content-between align-items-start popup-friends">
        <div>
            <h3>Añadir amigo</h3>
            <form @submit.prevent="sendFriendRequest">
                <input type="email" v-model="friendEmail" placeholder="Email del amigo" required>
                <button type="submit">Enviar Solicitud</button>
            </form>
        </div>
        <div>
            <h3>Peticiones de amistad</h3>
            <div v-for="request in friendRequests" :key="request.id">
                {{ request.sender.nickname }} -
                <button @click="acceptRequest(request.id)">Aceptar</button>
                <button @click="rejectRequest(request.id)">Rechazar</button>
            </div>
        </div>
        <div>
            <h3>Lista de amigos</h3>
            <div v-for="friend in friendsList" :key="friend.id">
                <img src="" alt="">
                {{ friend.nickname }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const friendEmail = ref('');
const friendRequests = ref([]);
const friendsList = ref([]);

onMounted(async () => {
    await loadFriendRequests();
    await loadFriendsList();
});

const loadFriendRequests = async () => {
    try {
        const response = await axios.get('/api/friends/requests');
        friendRequests.value = response.data;
    } catch (error) {
        console.error('Error loading friend requests:', error);
    }
};

const loadFriendsList = async () => {
    try {
        const response = await axios.get('/api/friends/list');
        friendsList.value = response.data;
    } catch (error) {
        console.error('Error loading friends list:', error);
    }
};

const sendFriendRequest = async () => {
    try {
        const response = await axios.post('/api/friends/send-request', {
            email: friendEmail.value
        });
        console.log(response.data.message);
    } catch (error) {
        console.error('Error:', error.response.data.message);
    }
};

const acceptRequest = async (requestId) => {
    try {
        const response = await axios.post(`/api/friends/accept/${requestId}`);
        console.log(response.data.message);
        friendRequests.value = friendRequests.value.filter(req => req.id !== requestId);
    } catch (error) {
        console.error('Error accepting request:', error);
    }
};

const rejectRequest = async (requestId) => {
    try {
        const response = await axios.post(`/api/friends/reject/${requestId}`);
        console.log(response.data.message);
        friendRequests.value = friendRequests.value.filter(req => req.id !== requestId);
    } catch (error) {
        console.error('Error rejecting request:', error);
    }
};
</script>


<style scoped>
.popup-friends {
    width: 15vw;
    height: 30vh;
    background-color: white;
    border-radius: 20px;
}
</style>