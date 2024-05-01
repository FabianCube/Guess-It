<template>
    <table>
        <tr class="first-row">
            <td>FECHA</td>
            <td>POSICIÓN</td>
            <td>PUNTUACIÓN</td>
        </tr>
        <tr>
            <td>12 de bla bla bla</td>
            <td>1</td>
            <td>2000</td>
        </tr>
    </table>
    
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, defineProps } from 'vue';

const props = defineProps([ 'user' ]);
const historyData = ref({});

onMounted(() => {
    getHistory();
})

const getHistory = async () => {

    await axios.get(`/api/account-history/${props.user.id}`)
        .then(response => {
            historyData.value = response.data;
            console.log(response.data);
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