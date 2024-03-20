<!-- * Component for info players -->

<template>
    <div class="p-2 flex flex-column justify-content-start align-items-center players-container">
        <div class="p-2 flex flex-column align-items-center player">
            <div class="p-0 m-0 avatar" style="width: 40px; min-height: 40px; border-radius:50px; background-color:red;"></div>
            <p class="name-player-info">SUPERPICASO69</p>
            <p class="points-player-info">560 pts.</p>
        </div>
        <div class="p-2 flex flex-column align-items-center player">
            <div class="p-0 m-0 avatar" style="width: 40px; min-height: 40px; border-radius:50px; background-color:red;"></div>
            <p class="name-player-info">DA_VINCI33</p>
            <p class="points-player-info">560 pts.</p>
        </div>
        <div class="p-2 flex flex-column align-items-center player">
            <div class="p-0 m-0 avatar" style="width: 40px; min-height: 40px; border-radius:50px; background-color:red;"></div>
            <p class="name-player-info">MEGA_DRAWER20</p>
            <p class="points-player-info">560 pts.</p>
        </div>
        <div class="p-2 flex flex-column align-items-center player">
            <div class="p-0 m-0 avatar" style="width: 40px; min-height: 40px; border-radius:50px; background-color:red;"></div>
            <p class="name-player-info">MEGA_DRAWER20</p>
            <p class="points-player-info">560 pts.</p>
        </div>
        <div class="p-2 flex flex-column align-items-center player">
            <div class="p-0 m-0 avatar" style="width: 40px; min-height: 40px; border-radius:50px; background-color:red;"></div>
            <p class="name-player-info">MEGA_DRAWER20</p>
            <p class="points-player-info">560 pts.</p>
        </div>
        <div class="p-2 flex flex-column align-items-center player">
            <div class="p-0 m-0 avatar" style="width: 40px; min-height: 40px; border-radius:50px; background-color:red;"></div>
            <p class="name-player-info">MEGA_DRAWER20</p>
            <p class="points-player-info">560 pts.</p>
        </div>
    </div>
</template>

<script setup>
</script>

<style scoped>

.name-player-info
{
    font-family: 'Lilita One', sans-serif;
    font-size: 10px;
    margin: 0;
    padding: 0;
}

.points-player-info
{
    font-family: 'Lilita One', sans-serif;
    margin: 0;
    padding: 0;
}

.players-container {
    width: 100%;
    height: 100%;
    background-color: #FFFDFD;
    border-radius: 22px;
}

.player
{
    height: 95px;
    width: 100%;
    border: 3px solid purple;
    border-radius: 22px;
    margin-bottom: 6px;
    box-shadow: 0 4px 4px rgba(0,0,0,.25);
}
</style>