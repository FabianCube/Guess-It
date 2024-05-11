<script setup>
import { ref, onMounted, onUpdated, nextTick, defineEmits, defineProps, watch } from 'vue';

const isDrawing = ref(false);
const canvas = ref(null);
const ctx = ref(null);
// const flag = ref(false); 
const x = ref(0);
const y = ref(0);
const lineWidth = ref("8");
const lineColor = ref("black");
const movements = ref([]);
const keepLastColor = ref('');
const currentTool = ref('draw');

const props = defineProps(['user', 'newCanvas', 'isDrawingEnabled', 'roomCode']);
const emits = defineEmits(['canvasupdate']);

console.log(props.isDrawingEnabled);


onMounted(() => {
    init();
    window.addEventListener('resize', adjustCanvas);
    adjustCanvas();

    listenRoundFinished();
    listenCleanCanvas();

});

watch(() => props.newCanvas, (canvas) => {
    console.log(canvas);
    if (canvas == "clear") {
        clearArea();
    }
    else {
        let positions = JSON.parse(canvas);
        // console.log(canvas);
        positions.forEach((movement, index) => {
            setTimeout(() => {
                drawLine(ctx.value, movement.x, movement.y, movement.offsetX, movement.offsetY, movement.lineColor, movement.lineWidth);
            }, index * 5);
        })
    }
});

const listenRoundFinished = () => {
    window.Echo.channel('room-' + props.roomCode)
        .listen('.RoundFinished', (e) => {
            clearArea();
        });
}

//  ¡¡¡¡NO SE ESTÁ APLICANDO!!!!
const listenCleanCanvas = () => {
    window.Echo.channel('room-' + props.roomCode)
        .listen('.CleanCanvas', (e) => {
            clearArea();
        });
}


const init = () => {
    canvas.value = document.getElementById('can');
    ctx.value = canvas.value.getContext("2d");

    canvas.value.addEventListener('mousedown', (e) => {
        x.value = e.offsetX;
        y.value = e.offsetY;
        isDrawing.value = true;
    });

    canvas.value.addEventListener('mousemove', (e) => {
        if (isDrawing.value) {
            movements.value.push({ x: x.value, y: y.value, offsetX: e.offsetX, offsetY: e.offsetY, lineColor: lineColor.value, lineWidth: lineWidth.value });
            drawLine(ctx.value, x.value, y.value, e.offsetX, e.offsetY, lineColor.value, lineWidth.value);
            x.value = e.offsetX;
            y.value = e.offsetY;

            if (movements.value.length >= 1000) {
                getParams();
                movements.value = [];
            }
        }
    });

    canvas.value.addEventListener('mouseup', (e) => {
        if (isDrawing.value) {
            movements.value.push({ x: x.value, y: y.value, offsetX: e.offsetX, offsetY: e.offsetY, lineColor: lineColor.value, lineWidth: lineWidth.value });
            drawLine(ctx.value, x.value, y.value, e.offsetX, e.offsetY, lineColor.value, lineWidth.value);
            getParams();
            x.value = 0;
            y.value = 0;
            isDrawing.value = false;
            movements.value = [];
        }
    });

    if (props.isDrawingEnabled) {
        let clear = document.getElementById('clearArea');

        clear.addEventListener('click', (e) => {
            clearArea();
            emits("canvasupdate", {
                user: props.user,
                canvas: "clear"
            });
        });
    }
};


const sendCanvas = (params) => {
    emits("canvasupdate", {
        user: props.user,
        canvas: params
    });
    console.log("[Canvas.vue]:sendCanvas: Canvas sent!");
}

const getParams = () => {
    console.log("[Canvas.vue]:getParams: Entrado en getParams!");

    const params = JSON.stringify(movements.value);

    // console.log(params);
    sendCanvas(params);
};

const drawLine = (ctx, x1, y1, x2, y2, color, stroke) => {
    console.log("Color: " + color + " Stroke: " + stroke)
    ctx.beginPath();
    // ctx.strokeStyle = document.getElementById('selColor').value;
    // ctx.strokeStyle = lineColor.value;
    ctx.strokeStyle = color;
    // ctx.lineWidth = document.getElementById('selWidth').value;
    // ctx.lineWidth = lineWidth.value;
    ctx.lineWidth = stroke;
    ctx.lineJoin = "round";
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.closePath();
    ctx.stroke();
};

const clearArea = () => {
    ctx.value.setTransform(1, 0, 0, 1, 0, 0);
    ctx.value.clearRect(0, 0, ctx.value.canvas.width, ctx.value.canvas.height);
};

const adjustCanvas = () => {
    let style = getComputedStyle(canvas.value);
    canvas.value.width = parseInt(style.width);
    canvas.value.height = parseInt(style.height);
};

const updateSize = (element, size) => {
    lineWidth.value = size;

    document.querySelectorAll('.size').forEach(el => {
        el.classList.remove('active-size');
    });
    element.classList.add('active-size');
}

const updateColor = (element, color) => {
    if (currentTool.value === "erase") {
        changeTool("draw");
    }

    lineColor.value = color;

    document.querySelectorAll('.color').forEach(el => {
        el.classList.remove('active-color');
    });

    element.classList.add('active-color');
}

const changeTool = (tool) => {
    if (tool == "erase") {
        if (lineColor !== 'white') {
            keepLastColor.value = lineColor.value;
        }

        lineColor.value = "white";
    }
    else if (tool == "draw") {
        lineColor.value = keepLastColor.value;
    }

    currentTool.value = tool;

    document.querySelectorAll('.tool').forEach(el => {
        el.classList.contains('active-tool') ?
            el.classList.remove('active-tool') : el.classList.add('active-tool');
    });
}

</script>

<template>
    <!-- CANVAS COMPONENT -->
    <canvas ref="canvas" id="can"></canvas>
    <!--  -->
    <div v-if="isDrawingEnabled">
        <div class="controls">

            <div id="select-size">
                <div @click="updateSize($event.target, '8')" class="size size-small active-size"></div>
                <div @click="updateSize($event.target, '14')" class="size size-medium"></div>
                <div @click="updateSize($event.target, '20')" class="size size-large"></div>
                <div @click="updateSize($event.target, '30')" class="size size-large-plus"></div>
            </div>

            <div id="select-tool">
                <button @click="changeTool('draw')" class="tool tool-paint active-tool">
                    <img src="/storage/icons/draw-tool.svg" alt="">
                </button>
                <button @click="changeTool('erase')" class="tool tool-erase">
                    <img src="/storage/icons/erase-tool.svg" alt="">
                </button>
            </div>

            <div id="select-color">
                <div @click="updateColor($event.target, 'black')" class="color active-color"></div>
                <div @click="updateColor($event.target, 'blue')" class="color"></div>
                <div @click="updateColor($event.target, 'purple')" class="color"></div>
                <div @click="updateColor($event.target, 'green')" class="color"></div>
                <div @click="updateColor($event.target, 'brown')" class="color"></div>
                <div @click="updateColor($event.target, 'red')" class="color"></div>
                <div @click="updateColor($event.target, 'pink')" class="color"></div>
                <div @click="updateColor($event.target, 'orange')" class="color"></div>
            </div>

        </div>

        <button @click="clearArea" id="clearArea">Clear Area</button>
    </div>
    <div v-else class="overlay"></div>
    <!--  -->
    <!-- END CANVAS COMPONENT -->
</template>

<style scoped>

@import './../style/canvas.css';

</style>
