<template>
    <div id="background" class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0 lilita-one-regular">
        <div class="container mt-6">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Boton</h1>
                <img id="logo" src="../../../../storage/app/public/guess-it-logo.svg" class="logo"></img>
                <h1>Boton</h1>
            </div>
            <div class="d-flex mt-5">
                <div class="col-3 me-5">
                    <div class="background-players">
                        <div class="d-flex flex-column align-items-center justify-content-between p-3">
                            <h2>JUGADORES</h2>
                            <div class="d-flex align-items-center btn-invite">
                                <p class="mb-0 me-3 ">INVITAR AMIGOS</p>
                                <img src="../../../../storage/app/public/icons/user-plus-01.svg">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9 d-flex flex-column">
                    <div class="background-instructions">
                        <div class="p-3 d-flex justify-content-center">
                            <h2>CÓMO JUGAR</h2>
                        </div>
                    </div>
                    <div class="w-100 d-flex mt-4">
                        <h3 class="mb-0">CODE: XLM984</h3>
                        <span>D</span>
                    </div>
                    <div class="w-100 d-flex justify-content-end">
                        <button>INICIAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>