<template>
    <div class="carousel">
        <vueper-slides :dragging-distance="50" class="no-shadow" fixed-height="33rem" slide-image-inside>
            <vueper-slide v-for="(slide, i) in slides" :key="i" :image="slide.image" />
        </vueper-slides>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { VueperSlides, VueperSlide } from 'vueperslides';
import 'vueperslides/dist/vueperslides.css';

const slides = ref([
    {
        title: 'Bob',
        image: '/storage/carousel_1.svg'
    },
    {
        title: 'Pikachu',
        image: '/storage/carousel_2.svg'
    },
    {
        title: 'Robot',
        image: '/storage/carousel_3.svg'
    },
    {
        title: 'Robot',
        image: '/storage/carousel_4.svg'
    }
])

</script>

<style>

@import './../style/carousel.css';

</style>