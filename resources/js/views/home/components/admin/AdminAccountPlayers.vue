<template>
    <table>
        <tr class="first-row">
            <th>ID</th>
            <th>NICKNAME</th>
            <th>LEVEL</th>
            <th>EMAIL</th>
            <th>AVATAR ID</th>
            <th>MODIFY</th>
        </tr>
        <tr v-for="user in users">
            <td>{{ user.id }}</td>
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
            </td>

        </tr>
    </table>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, defineProps, onBeforeMount, watch } from 'vue';

const props = defineProps([ 'user', 'historyData' ]);
const users = ref({});

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
    axios.delete(`/api/delete-user/${id}`);
    console.log('Delete user');
    refresh();
}

const refresh = () => {
    getAllPlayers();
}

</script>

<style scoped>
table
{
    width: 100%;
    height: auto;
    overflow-y: scroll;
}

.first-row
{
    border-bottom: solid 1px black;
}

.btn
{
    width: 30px;
    height: 30px;
    border: none;
    border-radius: 6px;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modify-cel
{
    display: flex;
    flex-flow: row;
}

.modify
{
    background-color: #66BA13;
    margin-right: 5px;
}
.delete
{
    background-color: #FD6F5A;
}
</style>