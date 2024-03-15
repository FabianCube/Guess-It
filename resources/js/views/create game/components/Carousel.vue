<template>
    <div class="carousel">
        <div class="d-flex justify-content-between">
            <button @click="prevImage" class="arrow left-arrow">&lt;</button>
            <div class="image-container" style="position: relative; overflow: hidden;">
                <div class="image-container" style="position: relative; overflow: hidden;">
                    <transition :name="transitionName" mode="out-in">
                        <img :src="images[currentImage]" :alt="'Carousel image ' + currentImage" :key="currentImage"
                            class="carousel-image">
                    </transition>
                </div>
            </div>
            <button @click="nextImage" class="arrow right-arrow">&gt;</button>
        </div>
        <div class="dots">
            <span v-for="(image, index) in images" :key="index" class="dot" @click="() => setImage(index)"
                :class="{ active: currentImage === index }"></span>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    setup() {
        const currentImage = ref(0);
        const images = ref([
            '/storage/bob-esponja.jpg',
            '/storage/pikachu.jpg',
            '/storage/robot.jpg',
        ]);
        const transitionName = ref('slide-right');

        const nextImage = () => {
            transitionName.value = 'slide-right';
            currentImage.value = (currentImage.value + 1) % images.value.length;
        };

        const prevImage = () => {
            transitionName.value = 'slide-left';
            currentImage.value = (currentImage.value + images.value.length - 1) % images.value.length;
        };

        const setImage = (index) => {
            direction.value = index > currentImage.value ? 'right' : 'left';
            currentImage.value = index;
        };

        return { currentImage, images, nextImage, prevImage, setImage, transitionName };
    },
};
</script>

<style scoped>
.carousel {
    width: 100%;
    height: 100%;
}

.image-container {
    width: auto;
    height: 25rem;
}

.carousel-image {
    width: 100%;
    height: 100%;
    animation-duration: 0.5s;
}

.arrow {
    font-size: 32px;
    background-color: #ffffff00;
    border: none;
    cursor: pointer;
}

.left-arrow {
    left: 10px;
}

.right-arrow {
    right: 10px;
}

.dots {
    text-align: center;
    padding-top: 20px;
}

.dot {
    cursor: pointer;
    height: 15px;
    width: 15px;
    margin: 0 5px;
    background-color: #FFF;
    border-radius: 50%;
    border: #A52FDD solid 3px;
    display: inline-block;
    box-shadow: 0px 4px 3px 0px rgba(0, 0, 0, 0.3);
}

.dot.active {
    background-color: #A52FDD;
}

@keyframes slideInLeft {
    0% {
        transform: translateX(-100%);
    }

    100% {
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    0% {
        transform: translateX(100%);
    }

    100% {
        transform: translateX(0);
    }
}

.slide-left-enter-active, .slide-right-enter-active {
  animation-duration: 0.5s;
  position: absolute;
  width: 100%;
}
.slide-left-leave-active, .slide-right-leave-active {
  animation-duration: 0.5s;
  position: absolute;
  width: 100%;
}
.slide-left-enter, .slide-right-leave-to /* left to right */ {
  transform: translateX(100%);
}
.slide-right-enter, .slide-left-leave-to /* right to left */ {
  transform: translateX(-100%);
}
</style>