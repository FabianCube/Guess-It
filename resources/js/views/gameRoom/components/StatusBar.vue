<!-- * Component for information in game -->

<template>
    <div class="container flex flex-row justify-content-between align-items-center px-5 bar-container">
        <div style="font-size:2rem">JUGADOR</div>
        <div style="font-size:2rem">_ _ _ _ A _</div>
        <div style="font-size:2rem">TIME LEFT</div>
    </div>
</template>

<script setup>
</script>

<style scoped>
.bar-container
{
    position: absolute;
    background-color: #FD6F5A;
    width: 100%;
    height: 100px;
    border-radius: 12px 12px 0 0;
    z-index: 101;
}
</style>