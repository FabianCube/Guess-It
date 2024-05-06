<template>
    <table>
        <tr class="first-row">
            <th>ID</th>
            <th>NICKNAME</th>
            <th>LEVEL</th>
            <th>EMAIL</th>
            <th>AVATAR ID</th>
            <th>MODIFY</th>
            <th>DELETE</th>
        </tr>
        <tr v-for="user in users">
            <td>{{ user.id }}</td>
            <td>{{ user.nickname }}</td>
            <td>{{ user.level }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.avatar_id }}</td>
            <td>
                <button>MODIFY</button>
            </td>
            <td>
                <button>DEL.</button>
            </td>
        </tr>
    </table>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, defineProps, onBeforeMount } from 'vue';

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
</script>

<style scoped>
table
{
    width: 100%;
    overflow-y: scroll;
}

.first-row
{
    border-bottom: solid 1px black;
}
</style>