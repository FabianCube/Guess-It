<template>
    <table>
        <tr class="first-row">
            <td>FECHA</td>
            <td>POSICIÓN</td>
            <td>PUNTUACIÓN</td>
        </tr>
        <tr v-for="game in games">
            <td>{{ game.created_at }}</td>
            <td>{{ game.user_position }}</td>
            <td>{{ game.user_points }}</td>
        </tr>
    </table>
    
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, defineProps, onBeforeMount, watch } from 'vue';

// obtenemos los datos mediante props del padre.
const props = defineProps([ 'user', 'historyData' ]);
const games = ref({});

// hacemos un watch de la variable de historyData, cada vez que cambie lo
// asignaremos a games.value.
watch(() => props.historyData, () => {
    games.value = props.historyData;
})

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