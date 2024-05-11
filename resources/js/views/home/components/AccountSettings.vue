<template>
    <ul>
        <li>SETTINGS 1</li>
        <li>SETTINGS 2</li>
        <li>SETTINGS 3</li>
    </ul>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue';

const props = defineProps([ 'user' ]);

//TO-DO: - Controller history, - axios get data, - show it

</script>

<style scoped>
@import './../style/accountSettings.css';
</style>