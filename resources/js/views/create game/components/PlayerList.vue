<template>
    <div class="w-100">
        <!-- Renderizar jugadores -->
        <div v-for="(jugador, index) in jugadores" :key="index" class="d-flex align-items-center mb-2 etiqueta" :class="`player-${index + 1}`">
            <div class="me-3 avatar">
                <!-- Aquí puedes incluir una imagen basada en jugador.avatarUrl si tienes -->
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
import { ref, computed, onMounted } from 'vue';

// Lista reactiva de jugadores
const jugadores = ref([
    // Ejemplo de jugadores preexistentes
    { nombre: 'SUPER_DRAWER2000' },
    { nombre: 'ANOTHER_PLAYER' }
    // Agrega aquí más jugadores según se unan
]);

// Número máximo de jugadores permitidos
const maxJugadores = 6;

// Espacios vacíos calculados
const espaciosVacios = computed(() => maxJugadores - jugadores.value.length);

const cargarJugadores = async () => {
  try {
    // Reemplaza con la llamada adecuada a tu API para obtener los jugadores actuales
    const response = await axios.get('/api/partida/jugadores');
    jugadores.value = response.data; // Asume que la respuesta incluye la lista de jugadores
  } catch (error) {
    console.error('Error al cargar los jugadores:', error);
  }
};

// Inicialmente cargar el jugador creador de la partida
onMounted(cargarJugadores);
</script>

<style scoped>
.etiqueta {
    border-radius: 10px;
    height: 4.3rem;
    padding: 0.5rem;
    box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25);
}

.player-1{
    border: 3px solid #950398;
}

.player-2{
    border: 3px solid #d42424;
}

.player-3{
    border: 3px solid #1ed621;
}

.player-4{
    border: 3px solid #1f39df;
}

.player-5{
    border: 3px solid #fb930c;
}

.player-6{
    border: 3px solid #fcf010;
}

.vacia{
    border: 1px solid #B2B2B2;
    box-shadow: none;
}

.avatar {
    border-radius: 50%;
    height: 3rem;
    width: 3rem;
    background-image: url('/storage/app/public/funny-avatar.jpg');
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    flex-shrink: 0;
}

.avatar-vacio{
    border-radius: 50%;
    height: 3rem;
    width: 3rem;
    border: 3px solid #A2A2A2;
    flex-shrink: 0;
}

.nombre-jugador {
    width: 70%;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #454545;
}

.nombre-jugador-vacio{
    color: #737373;
}
</style>