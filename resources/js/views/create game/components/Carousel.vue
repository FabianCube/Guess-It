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
.settings{
    position: absolute;
    margin-left: 1rem;
    margin-top: 1rem;
    cursor: pointer;
    z-index: 99;
}

.vueperslides__arrow {
    color: black;
}

.vueperslides__arrow svg {
    stroke-width: 2;
}

.vueperslides__bullet .default {
  background-color: #FFF;
  border: 2px solid #A52FDD;
  box-shadow: 0px 2px 2px 1px rgba(0,0,0,0.3);
  transition: 0.3s;
  width: 16px;
  height: 16px;
}

.vueperslides__bullet--active .default 
{
    background-color: #A52FDD;
}
</style>