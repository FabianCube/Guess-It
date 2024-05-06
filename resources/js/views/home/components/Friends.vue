<template>
    <div class="d-flex flex-column justify-content-between align-items-start popup-friends p-5 mb-2">
        <div class="w-100 mb-3">
            <h3>AÑADIR AMIGO</h3>
            <form @submit.prevent="sendFriendRequest" class="d-flex flex-column w-100">
                <input type="email" class="mail-input p-1 mb-3" v-model="friendEmail" placeholder="Email del amigo"
                    required>
                <button type="submit" class="btn-send">ENVIAR SOLICITUD</button>
            </form>
        </div>
        <div class="w-100 mb-3">
            <h3>PETICIONES DE AMISTAD</h3>
            <div v-for="request in friendRequests" :key="request.id"
                class="d-flex flex-column align-items-center request">
                <p class="mb-2">{{ request.sender.nickname }}</p>
                <div class="w-100 d-flex justify-content-around">
                    <button class="btn-accept" @click="acceptRequest(request.id)">ACEPTAR</button>
                    <button class="btn-deny" @click="rejectRequest(request.id)">RECHAZAR</button>
                </div>
            </div>
        </div>
        <div class="w-100">
            <h3>LISTA DE AMIGOS</h3>
            <div v-for="friend in friendsList" :key="friend.id" class="d-flex align-items-center mb-2 friend">
                <img :src="`/storage/avatars/avatar${friend.avatar_id}.jpg`" alt="avatar" class="avatar">
                <p class="mb-0 ms-3">{{ friend.nickname }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onBeforeMount } from 'vue';


const friendEmail = ref('');
const friendRequests = ref([]);
const friendsList = ref([]);


onBeforeMount(async () => {
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
        console.log(friendsList.value);
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
        loadFriendsList();
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
    font-family: "Lilita One", sans-serif;
    width: 100%;
    height: 50vh;
    background-color: white;
    border-radius: 20px;
    transform-origin: bottom;
    animation: growUp 0.3s ease-out forwards;
    overflow: hidden;
}

.mail-input {
    width: 100%;
    border-radius: 6px;
    border: solid 2px #757575;
}

.request {
    border: solid 2px #757575;
    border-radius: 8px;
    padding: 5px;
    padding-bottom: 10px;
    box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.25);
}

.friend {
    border: 2px solid #B2B2B2;
    border-radius: 8px;
    height: 4.3rem;
    padding: 0.5rem;
    box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.25);
}

.avatar {
    border-radius: 50%;
    overflow: hidden;
    height: 3rem;
    width: 3rem;
    flex-shrink: 0;
}

@keyframes growUp {
    from {
        transform: scaleY(0);
    }

    to {
        transform: scaleY(100%);
    }
}
</style>