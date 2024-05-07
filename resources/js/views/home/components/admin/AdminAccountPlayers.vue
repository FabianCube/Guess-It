<template>
    <div id="container-players">
        <table>
            <tr class="first-row">
                <th>ID</th>
                <th>NICKNAME</th>
                <th>LEVEL</th>
                <th>EMAIL</th>
                <th>AVATAR ID</th>
                <th>MODIFY</th>
            </tr>
            <!-- <tr v-for="user in users"> -->
                <!-- <td>{{ user.id }}</td>
                <td>{{ user.nickname }}</td>
                <td>{{ user.level }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.avatar_id }}</td>
                <td class="modify-cel">
                    <button class="btn modify">
                        <img src="/storage/icons/edit.svg" alt="">
                    </button>
                    <button class="btn delete" @click="deleteUser(user.id)">
                        <img src="/storage/icons/trash.svg" alt="">
                    </button>
                </td> -->
            <tr v-for="user in users" :key="user.id">
                <template v-if="editingUserId === user.id">
                    <td>{{ user.id }}</td>
                    <td><input v-model="editedUser.nickname" type="text"></td>
                    <td><input v-model.number="editedUser.level" type="number"></td>
                    <td><input v-model="editedUser.email" type="email"></td>
                    <td><input v-model.number="editedUser.avatar_id" type="number"></td>
                    <td class="modify-cel">
                        <button class="btn modify" @click="saveChanges(user.id)">
                            <img src="/storage/icons/save.svg" alt="">
                        </button>
                        <button class="btn delete" @click="cancelEdit">
                            <img src="/storage/icons/cancel.svg" alt="">
                        </button>
                    </td>
                </template>
                <template v-else>
                    <td>{{ user.id }}</td>
                    <td>{{ user.nickname }}</td>
                    <td>{{ user.level }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.avatar_id }}</td>
                    <td class="modify-cel">
                        <button class="btn modify" @click="startEdit(user.id)">
                            <img src="/storage/icons/edit.svg" alt="">
                        </button>
                        <button class="btn delete" @click="deleteUser(user.id)">
                            <img src="/storage/icons/trash.svg" alt="">
                        </button>
                    </td>
                </template>
            </tr>
        </table>
    </div>

</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, defineProps, onBeforeMount, watch } from 'vue';
import sweetAlertNotifications from '@/utils/swal_notifications';
const { throwInfoMessage, throwSuccessMessage, throwInviteMessage } = sweetAlertNotifications();

const props = defineProps(['user', 'historyData']);
const users = ref({});
const editedUser = ref({});
const editingUserId = ref(null);

onMounted(() => {
    getAllPlayers();
})

const getAllPlayers = async () => {

    await axios.get('/api/admin-players')
        .then(response => {
            users.value = response.data;
        })
}

const deleteUser = (id) => {
    throwInviteMessage(
        '¿Eliminar usuario?',
        () => proceedDeleteUser(id),
        () => console.log('Cancelado.')
    );
}

const proceedDeleteUser = (id) => {
    axios.delete(`/api/delete-user/${id}`);
    refresh();
    throwSuccessMessage('USUARIO ELIMINADO');
}

const refresh = () => {
    getAllPlayers();
}

const startEdit = (id) => {
    editingUserId.value = id;
    const userToEdit = users.value.find(user => user.id === id);
    editedUser.value = { ...userToEdit };// ... para copiar todos los resultados
}

const cancelEdit = () => {
    editingUserId.value = null;
}

const saveChanges = async (id) => {
    await axios.put(`/api/update-user/${id}`, editedUser.value);
    cancelEdit();
    refresh();
    throwSuccessMessage('Cambios guardados');
}
</script>

<style scoped>
#container-players {
    width: 100%;
}

input
{
    width: 100px;
}

table {
    width: 100%;
    height: 100%;
}

.first-row {
    border-bottom: solid 1px black;
}

.btn {
    width: 30px;
    height: 30px;
    border: none;
    border-radius: 6px;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modify-cel {
    display: flex;
    flex-flow: row;
}

.modify {
    background-color: #66BA13;
    margin-right: 5px;
}

.delete {
    background-color: #FD6F5A;
}
</style>