<template>
    <div class="countdown">
        <img :src="images[currentImage]" :key="timeLeft" />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted , defineEmits , watch } from 'vue';

const emits = defineEmits(['update-timer']);

const timeLeft = ref(4);
const images = [
    '/storage/3.svg',
    '/storage/2.svg',
    '/storage/1.svg',
    '/storage/a_jugar.svg'
];
const currentImage = ref(0);

watch([timeLeft], () => {
    emits('update-timer', {
        timeLeft: timeLeft.value
    });
});


function startCountdown() {
    const intervalId = setInterval(() => {
        if (timeLeft.value > 0) {
            timeLeft.value--;
            currentImage.value++;
        } else {
            clearInterval(intervalId);
        }
    }, 1000);

    onUnmounted(() => {
        clearInterval(intervalId);
    });
}

onMounted(() => {
    startCountdown();
});

</script>

<style scoped>
.countdown {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    font-size: 4em;
    padding: 20px;
    border-radius: 10px;
}

.countdown img {
    width: auto;
    height: 30rem;
    animation: growAndFade 1s ease-out forwards;
}

@keyframes growAndFade {
    0% {
        transform: scale(0.1);
        opacity: 1;
    }

    70% {
        transform: scale(1);
        opacity: 1;
    }

    100% {
        transform: scale(2);
        opacity: 0;
    }
}
</style>