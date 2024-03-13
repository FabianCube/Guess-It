<template>
    <div class="carousel">
        <div class="d-flex justify-content-between">
            <button @click="prevImage" class="arrow left-arrow">&lt;</button>
            <div class="image-container">
                <img :src="images[currentImage]" alt="Carousel image" class="carousel-image">
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

        const nextImage = () => {
            currentImage.value = (currentImage.value + 1) % images.value.length;
        };

        const prevImage = () => {
            currentImage.value = (currentImage.value + images.value.length - 1) % images.value.length;
        };

        const setImage = (index) => {
            currentImage.value = index;
        };

        return { currentImage, images, nextImage, prevImage, setImage };
    },
};
</script>

<style scoped>
.carousel{
    width: 100%;
}

.image-container img {
    width: auto;
    height: 20rem;
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
    box-shadow: 0px 4px 3px 0px rgba(0,0,0,0.3);
}

.dot.active {
    background-color: #A52FDD;
}
</style>