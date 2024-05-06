<template>
    <table>
        <tr class="first-row">
            <th>GAME ID</th>
            <th>USER ID</th>
            <th>USER PTS.</th>
            <th>USER POS.</th>
            <th>CREATED</th>
            <th>MODIFY</th>
        </tr>
        <tr v-for="game in games">
            <td>{{ game.id }}</td>
            <td>{{ game.user_id }}</td>
            <td>{{ game.user_points }}</td>
            <td>{{ game.user_position }}</td>
            <td>{{ game.created_at }}</td>
            <td>
                <button>MODIFY</button>
            </td>
        </tr>
    </table>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, defineProps, onBeforeMount } from 'vue';

const props = defineProps([ 'user', 'historyData' ]);

const games = ref({});

onMounted(() => {
    getAllGames();
})

const getAllGames = async () => {

    await axios.get('/api/admin-history')
        .then(response => {
            games.value = response.data;
            console.log(response.data);
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

