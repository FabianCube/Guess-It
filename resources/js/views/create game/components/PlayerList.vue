<template>
    <div class="w-100">
        <div class="d-flex align-items-center mb-2 etiqueta player-1">
            <div class="me-3 avatar">
            </div>
            <div class="nombre-jugador">
                <p class="mb-0">SUPER_DRAWER2000</p>
            </div>
        </div>
        <div class="d-flex align-items-center mb-2 etiqueta player-2">
            <div class="me-3 avatar">
            </div>
            <div class="nombre-jugador">
                <p class="mb-0">SUPER_DRAWER2000</p>
            </div>
        </div>
        <div class="d-flex align-items-center mb-2 etiqueta player-3">
            <div class="me-3 avatar">
            </div>
            <div class="nombre-jugador">
                <p class="mb-0">SUPER_DRAWER2000</p>
            </div>
        </div>
        <div class="d-flex align-items-center mb-2 etiqueta vacia">
            <div class="me-3 avatar-vacio">
            </div>
            <div class="nombre-jugador-vacio">
                <p class="mb-0">VACÍO</p>
            </div>
        </div>
        <div class="d-flex align-items-center mb-2 etiqueta vacia">
            <div class="me-3 avatar-vacio">
            </div>
            <div class="nombre-jugador-vacio">
                <p class="mb-0">VACÍO</p>
            </div>
        </div>
        <div class="d-flex align-items-center mb-2 etiqueta vacia">
            <div class="me-3 avatar-vacio">
            </div>
            <div class="nombre-jugador-vacio">
                <p class="mb-0">VACÍO</p>
            </div>
        </div>
    </div>
</template>

<script setup>

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