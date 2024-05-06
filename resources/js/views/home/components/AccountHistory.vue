<template>
    <table>
        <tr class="first-row">
            <td>FECHA</td>
            <td>POSICIÓN</td>
            <td>PUNTUACIÓN</td>
        </tr>
        <tr v-for="game in props.historyData">
            <td>{{ game.created_at }}</td>
            <td>{{ game.user_position }}</td>
            <td>{{ game.user_points }}</td>
        </tr>
    </table>
    
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, defineProps, onBeforeMount } from 'vue';

const props = defineProps([ 'user', 'historyData' ]);

onMounted(() => {
    getHistory();
})

const getHistory = async () => {

    await axios.get(`/api/account-history/${props.user.id}`)
        .then(response => {
            props.historyData = response.data;
            console.log(response.data[0].id);
        })
}

</script>

<style scoped>
table
{
    width: 100%;
}

.first-row
{
    border-bottom: solid 1px black;
}

.first-row>th
{
    font-size: 14px;

}
</style>