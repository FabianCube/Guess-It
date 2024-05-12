<template>
    <div class="w-100 players-container">
        <!-- Renderizar jugadores -->
        <div v-for="(jugador, index) in jugadores" :key="index" class="d-flex align-items-center mb-2 etiqueta"
            :class="`player-${index + 1}`">
            <div class="me-3 avatar">
                <img :src="jugador.avatar" alt="avatar">
            </div>
            <div class="nombre-jugador">
                <p class="mb-0">{{ jugador.nickname }}</p>
            </div>
        </div>

        <!-- Renderizar espacios vacíos -->
        <div v-for="n in espaciosVacios" :key="`vacio-${n}`" class="d-flex align-items-center mb-2 etiqueta vacia">
            <div class="me-3 avatar-vacio">
            </div>
            <div class="nombre-jugador-vacio">
                <p class="mb-0">VACÍO</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeMount, onUnmounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { defineEmits } from 'vue';

// Función para enviar eventos al componente padre
const emits = defineEmits(['update-players']);

// Lista reactiva de jugadores
const jugadores = ref([]);

// Número máximo de jugadores permitidos
const maxJugadores = 6;

// Espacios vacíos calculados
const espaciosVacios = computed(() => maxJugadores - jugadores.value.length);

const codigoSala = ref();

const route = useRoute();

/* MOUNTING */

// Una vez montado el componente escuchamos el canal "room-channel" para actualizar la lista de jugadores
// si alguien entra o sale de la sala
onMounted(() => {
    escucharNuevaCache();
    escucharActualizacionesDeSala();
});

// Cargamos los jugadores antes de montar el componente
onBeforeMount(() => {
    codigoSala.value = route.params.code;
    cargarJugadores();
});

// Cuando se desmonta el componente dejamos de escuchar el canal
onUnmounted(() => {
    window.Echo.leave(`room-${codigoSala.value}`);
    window.Echo.leave('cache');
});

/* FUNCTIONS */

// Obtenemos de la API la lista de jugadores
const cargarJugadores = async () => {
    try {
        const response = await axios.get(`/api/room/players/${codigoSala.value}`);
        jugadores.value = response.data;
        emits('update-players', jugadores.value.length);
    } catch (error) {
        console.error('Error al cargar los jugadores:', error);
    }
};

const crearCache = async (roomData) => {
    await axios.post('/api/create-cache', {
        code: codigoSala.value,
        roomData: roomData,
    }).then(response => {
        console.log(response.data);
    }).catch(error => {
        console.error("Error al crear cache: ", error);
    });
}

/* LISTENERS */

// Escuchamos el canal "room-channel" y si entra o sale un jugador actualizamos la lista de jugadores
const escucharActualizacionesDeSala = () => {
    window.Echo.channel(`room-${codigoSala.value}`)
        .listen('.RoomUpdate', (e) => {
            console.log("Actualización de sala recibida:", e);
            cargarJugadores();
        });
};

const escucharNuevaCache = () => {
    window.Echo.channel('cache')
        .listen('.GetCache', (e) => {
            crearCache(e.roomData);
        });
}
</script>

<style scoped>

@import './../style/playerList.css';

@media(max-width: 520px)
{
    .etiqueta
    {
        width: 99%;
    }

    .players-container
    {
        display: grid!important;
        grid-template-columns: repeat(2, 1fr);
    }
}

</style>