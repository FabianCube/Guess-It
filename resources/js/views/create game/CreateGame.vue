<template>
    <div id="background"
        class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0 lilita-one-regular">
        <div class="container mt-6">
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn-smll-default"><img src="/storage/icons/home-05.svg"
                        alt=""></button>
                <img id="logo" src="/storage/guess-it-logo.svg" class="logo"></img>
                <button class="btn-smll-default"><img src="/storage/icons/volume-on.svg" alt=""></button>
            </div>
            <div class="flex mt-5">
                <div class="col-3 ">
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
                <div class="col-9 d-flex flex-column ps-5">
                    <div class="background-instructions">
                        <div class="p-3 d-flex justify-content-center">
                            <Carousel />
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

<script>

</script>