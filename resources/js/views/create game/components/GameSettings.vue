<template>
    <div>
        <img src="/storage/icons/info-circle-black.svg" class="info">
        <div class="d-flex flex-column align-items-center">
            <h2 class="settings-font settings">AJUSTES DE PARTIDA</h2>
            <div class="d-flex justify-content-between align-items-center w-50">
                <h3 class="mb-0">NÚMERO DE RONDAS</h3>
                <select class="select-font select-settings">
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>
            <div class="d-flex justify-content-between align-items-center w-50 mt-4">
                <h3 class="mb-0">TIEMPO DE RONDA</h3>
                <select class="select-font select-settings">
                    <option>30</option>
                    <option>45</option>
                    <option>60</option>
                    <option>90</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>

</script>

<style scoped>
.settings{
    position: relative;
    margin-top: 6.5rem;
    margin-bottom: 4.5rem;
}

.info{
    margin-top: 1rem;
    margin-left: 1rem;
    cursor: pointer;
}

.select-settings{
    appearance: none;
    -webkit-appearance: none; 
    -moz-appearance: none; 
    background-image: url('/storage/icons/select-arrow.svg');
    background-repeat: no-repeat;
    background-position: right 10px center;
    width: 6rem;
    padding-left: 10px;
    border: 3px solid #000000;
    border-radius: 10px;
}
</style>