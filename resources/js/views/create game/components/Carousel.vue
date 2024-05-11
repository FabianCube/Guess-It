<template>
    <div class="carousel">
        <!-- <img src="/storage/icons/settings.svg" class="settings"> si hay tiempo aplicamos la opción de cambiar entre opciones y tutorial-->
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
        image: '/storage/instrucciones-demo.svg'
    },
    {
        title: 'Pikachu',
        image: '/storage/instrucciones-demo.svg'
    },
    {
        title: 'Robot',
        image: '/storage/instrucciones-demo.svg'
    }
])

</script>

<style>

@import './../style/carousel.css';

</style>