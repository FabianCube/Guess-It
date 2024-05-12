<template>
    <div class="row d-flex flex-row">
        <div class="col col-6 f-flex justify-content-center align-items-right">
            <div class="avatar-wrapper">
                <div class="image-container">
                    <img :src="avatarImage" alt="Avatar" class="avatar-image">
                </div>
                <button class="change-avatar"
                    @click="changeAvatar(), playHovers('/storage/sounds/pop-change-avatar.mp3')">
                    <img src="/storage/icons/next-avatar.svg" alt="Next-avatar">
                </button>
            </div>
        </div>
        <div class="col col-6 d-flex flex-column justify-content-center align-items-left">
            <div>
                <p>NICKNAME </p>
                <input type="text" v-model="editedUser.nickname" name="nickname">
                <p>EMAIL </p>
                <input type="text" v-model="editedUser.email" name="email">
            </div>
            <button class="btn-default" @click="saveChanges">MODIFICAR</button>

        </div>
    </div>
</template>

<script setup>
import { ref, onBeforeMount, onMounted, defineProps } from 'vue';
import axios from 'axios';
import sweetAlertNotifications from '@/utils/swal_notifications';

const { throwSuccessMessage, throwInviteMessage } = sweetAlertNotifications();
const props = defineProps(['user']);
const baseAvatar = '/storage/avatars/';
const avatarImage = ref();
const avatarId = ref(props.user.avatar_id);
const editedUser = { ...props.user };

onMounted(async () => {
    
})
// hago una copia del user para modificarlo


// Función para cargar el nombre del archivo del avatar según su Id
const loadAvatar = () => {
    axios.get('/api/get-avatar/' + avatarId.value)
        .then(({ data }) => {
            avatarImage.value = baseAvatar + data.image;
        })
        .catch(error => {
            console.error("Error al obtener el avatar: ", error);
        });
};

// Cargamos el avatar inicial cuando el componente se monta
onBeforeMount(loadAvatar);

// Método para cambiar el avatar
const changeAvatar = () => {
    if (avatarId.value === 4) {
        avatarId.value = 1;
    } else {
        avatarId.value += 1;
    }

    loadAvatar();
};

// Método para guardar los cambios en el servidor
const saveChanges = async () => {
    editedUser.avatar_id = avatarId.value;
    let id = editedUser.id;
    await axios.put(`/api/update-user-settings/${id}`, editedUser)
    throwSuccessMessage('CAMBIOS GUARDADOS');
};

</script>

<style scoped>
@import './../style/accountSettings.css';
@import './../style/anonymousUser.css';

.btn-default
{
    width: 175px;
    height: 40px;
    font-size: 1.5rem;
    line-height: 40px;
    border-radius: 6px;
    margin: 0;
}

p
{
    margin: 0;
}

input
{
    margin-bottom: 10px;
}
</style>