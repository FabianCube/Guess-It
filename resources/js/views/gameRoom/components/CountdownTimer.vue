<template>
    <div v-if="props.startTimer" class="countdown">
        <img :src="images[currentImage]" :key="timeLeft" />
    </div>
</template>

<script setup>
import { ref, onUnmounted , defineEmits , defineProps , watch } from 'vue';

const props = defineProps(['startTimer']);
const emits = defineEmits(['update-timer']);

const timeLeft = ref(4);
const images = [
    '/storage/3.svg',
    '/storage/2.svg',
    '/storage/1.svg',
    '/storage/a_jugar.svg'
];
const currentImage = ref(0);

watch(() => props.startTimer, (newValue) => {
    if (newValue) {
        startCountdown();
    }
});

const playSound = (soundPath) => {
    const audio = new Audio(soundPath);
    audio.volume = 0.05;
    audio.play();
};

watch([timeLeft], () => {
    emits('update-timer', {
        timeLeft: timeLeft.value
    });
});


function startCountdown() {
    playSound('/storage/sounds/inicio_ronda.mp3');
    const intervalId = setInterval(() => {
        if (timeLeft.value > 0) {
            timeLeft.value--;
            currentImage.value++;
        } else {
            clearInterval(intervalId);
        }
    }, 1000);
}

</script>

<style scoped>

@import './../style/countdownTimer.css';

</style>