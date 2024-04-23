<script setup>
import { ref, onMounted, onUpdated, nextTick, defineEmits } from 'vue';

const isDrawing = ref(false);
const canvas = ref(null);
const ctx = ref(null);
// const flag = ref(false); 
const x = ref(0);
const y = ref(0);
const lineWidth = ref("13");
const lineColor = ref("blue");
const movements = ref([]);

const props = defineProps([ 'user', 'newCanvas' ]);
const emits = defineEmits([ 'canvasupdate' ]);

onMounted(() => {
    init();
    window.addEventListener('resize', adjustCanvas);
    adjustCanvas();
});

onUpdated(() => {
    nextTick(() => {
        if (props.newCanvas)
        {
            if (props.newCanvas == "clear")
            {
                clearArea();
            }
            else
            {
                let positions = JSON.parse(props.newCanvas);

                positions.forEach((movement, index) => {
                    setTimeout(() => {
                        drawLine(ctx.value, movement.x, movement.y, movement.offsetX, movement.offsetY);
                    }, index * 5);
                })
            }
        }
    });
});

const init = () => {
    canvas.value = document.getElementById('can');
    ctx.value = canvas.value.getContext("2d");

    canvas.value.addEventListener('mousedown', (e) => {
        x.value = e.offsetX;
        y.value = e.offsetY;
        isDrawing.value = true;
    });

    canvas.value.addEventListener('mousemove', (e) => {
        if (isDrawing.value) 
        {
            movements.value.push({ x: x.value, y: y.value, offsetX: e.offsetX, offsetY: e.offsetY });
            drawLine(ctx.value, x.value, y.value, e.offsetX, e.offsetY);
            x.value = e.offsetX;
            y.value = e.offsetY;

            if (movements.value.length >= 1000)
            {
                getParams();
                movements.value = [];
            }
        }
    });

    canvas.value.addEventListener('mouseup', (e) => {
        if (isDrawing.value) 
        {
            movements.value.push({ x: x.value, y: y.value, offsetX: e.offsetX, offsetY: e.offsetY });
            drawLine(ctx.value, x.value, y.value, e.offsetX, e.offsetY);
            getParams();
            x.value = 0;
            y.value = 0;
            isDrawing.value = false;
            movements.value = [];
        }
    });

    let clear = document.getElementById('clearArea');

    clear.addEventListener('click', (e) => {
        clearArea();
        sendCanvas("clear");
    });
};


const sendCanvas = (params) => {
    emits("canvasupdate", {
        user: props.user,
        canvas: params,
    });
    console.log("[Canvas.vue]:sendCanvas: Canvas sent!");
}

const getParams = () => {
    console.log("[Canvas.vue]:getParams: Entrado en getParams!");

    const params = JSON.stringify(movements.value);

    console.log(params);
    sendCanvas(params);
};

const drawLine = (ctx, x1, y1, x2, y2) => {
    ctx.beginPath();
    ctx.strokeStyle = document.getElementById('selColor').value;;
    ctx.lineWidth = document.getElementById('selWidth').value;
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



</script>

<template>
    <!-- CANVAS COMPONENT -->
    <canvas ref="canvas" id="can"></canvas>

    <div class="controls">
        <!-- Line width : <select v-model="lineWidth" id="selWidth">
            <option value="6">6</option>
            <option value="11" selected="selected">11</option>
            <option value="13">13</option>
        </select> -->
        <div id="select-size">
            <div class="size size-small"></div>
            <div class="size size-medium"></div>
            <div class="size size-large"></div>
            <div class="size size-large-plus"></div>
        </div>
        <div id="select-tool">
            <button class="tool tool-paint active-tool">x</button>
            <button class="tool tool-erase">x</button>
        </div>
        <!-- <select v-model="lineColor" id="selColor">
            <option value="black">black</option>
            <option value="blue" selected="selected">blue</option>
            <option value="red">red</option>
            <option value="green">green</option>
            <option value="yellow">yellow</option>
            <option value="gray">gray</option>
        </select> -->
        <div id="select-color">
            <div class="color active-color"></div>
            <div class="color"></div>
            <div class="color"></div>
            <div class="color"></div>
            <div class="color"></div>
            <div class="color"></div>
            <div class="color"></div>
            <div class="color"></div>
        </div>
        <!-- <button @click="clearArea" id="clearArea">Clear Area</button> -->
    </div>

    <!-- END CANVAS COMPONENT -->
</template>

<style scoped>

/*  SELECT SIZE  */
#select-size
{
    width: 33%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-flow: row;
}

.size
{
    background-color: #5F5F5F;
    border-radius: 50px;
    margin-right: 5px;
}

.size-small
{
    width: 8px;
    height: 8px;
}
.size-medium
{
    width: 14px;
    height: 14px;
}
.size-large
{
    width: 20px;
    height: 20px;
}

.size-large-plus
{
    width: 30px;
    height: 30px;
}

/*  END SELECT SIZE   */

#select-tool
{
    display: flex;
    justify-content: center;
    align-items: center;
    width: 33%;
}

.tool{
    width: 35px;
    height: 35px;
    border: rgba(149, 2, 152, 54%) solid 2px;
    border-radius: 6px;
    margin: 3px;
    transition: all .3s ease-in-out;
}

.active-tool
{
    width: 45px!important;
    height: 45px!important;
    border: #950298 solid 2px;
}

/*   CANVAS   */

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
    border: solid 1px black;
    width: 380px;
    height: 65px;
    box-shadow: 0 4px 4px rgba(0,0,0,.25);

    display: flex;
    justify-content: space-around;
    align-items: center;
}

/*  END CANVAS   */


#select-color
{
    width: 33%;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 4px;
    padding: 0 20px 0 20px;
}

.color
{
    width: 17px;
    height: 17px;
    border-radius: 50px;
    background-color: red;
    opacity: 50%;
}

.active-color
{
    border: solid 1px;
    opacity: 100%;
}

.color:nth-child(2)
{
    background-color: blue;
}
.color:nth-child(3)
{
    background-color: purple;
}
.color:nth-child(4)
{
    background-color: green;
}
.color:nth-child(5)
{
    background-color: brown;
}
.color:nth-child(6)
{
    background-color: black;
}
.color:nth-child(7)
{
    background-color: pink;
}
.color:nth-child(8)
{
    background-color: orange;
}

#selColor
{
    width: 33%;
}
</style>
