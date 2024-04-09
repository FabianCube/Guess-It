<template>
    <!-- CANVAS COMPONENT -->
    <canvas ref="canvas" id="can"></canvas>

    <div class="controls">
        <button @click="clearArea" id="clearArea">Clear Area</button>
        Line width : <select v-model="lineWidth" id="selWidth">
            <option value="11">11</option>
            <option value="13" selected="selected">13</option>
            <option value="15">15</option>
        </select>
        Color : <select v-model="lineColor" id="selColor">
            <option value="black">black</option>
            <option value="blue" selected="selected">blue</option>
            <option value="red">red</option>
            <option value="green">green</option>
            <option value="yellow">yellow</option>
            <option value="gray">gray</option>
        </select>
    </div>

    <!-- END CANVAS COMPONENT -->
</template>

<script setup>
import { ref, onMounted, onUpdated, nextTick, defineEmits } from 'vue';

const canvas = ref(null);
const ctx = ref(null);
const isDrawing = ref(false);
const movements = ref([]);
const lineWidth = ref("13");
const lineColor = ref("blue");

const init = () => {
    canvas.value = document.getElementById('can');
    ctx.value = canvas.value.getContext("2d");

    canvas.value.addEventListener('mousedown', (e) => {
        isDrawing.value = true;
    });

    canvas.value.addEventListener('mousemove', (e) => {
        if (isDrawing.value) {
            movements.value.push({ x: e.offsetX, y: e.offsetY });
            drawLine(ctx.value, e.offsetX, e.offsetY);
        }
    });

    canvas.value.addEventListener('mouseup', (e) => {
        if (isDrawing.value) {
            movements.value.push({ x: e.offsetX, y: e.offsetY });
            drawLine(ctx.value, e.offsetX, e.offsetY);
            getParams();
            isDrawing.value = false;
            movements.value = [];
        }
    });

    let clear = document.getElementById('clearArea');

    clear.addEventListener('click', clearArea);
};

const { emit } = (["canvasupdate"]);

const sendCanvas = (params) => {
    emit("canvasupdate", {
        user: this.user,
        canvas: params,
    });
    console.log("canvas sent");
}

const drawLine = (ctx, x1, y1, x2, y2) => {
    ctx.beginPath();
    ctx.strokeStyle = lineColor.value;
    ctx.lineWidth = lineWidth.value;
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

const getParams = () => {
    const params = JSON.stringify(movements.value);
    console.log(params);
    sendCanvas(params);
};

const adjustCanvas = () => {
    let style = getComputedStyle(canvas.value);
    canvas.value.width = parseInt(style.width);
    canvas.value.height = parseInt(style.height);
};

onMounted(() => {
    init();
    window.addEventListener('resize', adjustCanvas);
    adjustCanvas();
});

onUpdated(() => {
    nextTick(() => {
        if (lineWidth.value === "clear") {
            clearArea();
        }
    });
});

</script>

<style scoped>
#can {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0%;
    left: 0%;
    background-color: #fff;
    border-radius: 22px;
}

.controls {
    position: absolute;
    bottom: 0%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 100;
    background-color: white;
    border-radius: 15px;
    border: solid 2px;
    width: 350px;
    height: 65px;

    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
