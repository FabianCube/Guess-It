<!-- * Component for information in game -->

<template>
    <div class="container flex flex-row justify-content-between align-items-center px-5 bar-container">
        <div style="font-size:2rem">JUGADOR</div>
        <div style="font-size:2rem">_ _ _ _ A _</div>
        <div id="clock-container">

            <div class="clock-icon">
                <div class="clock-time">
                    <h3>{{ props.timeRound }}</h3>
                </div>
                <img src="/storage/clock.svg" alt="">
            </div>
        </div>
    </div>
</template>

<script setup>
import { watch, ref, onMounted } from 'vue';

const props = defineProps(['timeRound']);

</script>

<style scoped>
.bar-container {
    position: absolute;
    background-color: #FD6F5A;
    width: 100%;
    height: 100px;
    border-radius: 22px 22px 0 0;
    z-index: 101;
}

.clock-container {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.clock-icon {
    position: relative;
}

.clock-time {
    position: absolute;
    top: 63%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.clock-time>h3
{
    font-family: 'Lilita One', sans-serif;
    font-size: 2rem;
    color: #494949;
}
</style>