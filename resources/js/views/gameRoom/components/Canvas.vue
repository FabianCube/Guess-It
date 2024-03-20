<template>
    <!-- CANVAS COMPONENT -->
    <canvas id="can"></canvas>

    <div class="controls">
        <button id="clearArea">Clear Area</button>
        Line width : <select id="selWidth">
            <option value="11">11</option>
            <option value="13" selected="selected">13</option>
            <option value="15">15</option>
        </select>
        Color : <select id="selColor">
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

<script>

// window.onload = adjustCanvas;

export default {
    props: ["newCanvas"],
    updated() {
        this.$nextTick(() => {
            if (this.newCanvas) {

                if (this.newCanvas == "clear") {
                    this.clearArea();
                } else {
                    let positions = JSON.parse(this.newCanvas);

                    // Dibujamos punto por punto los movimientos
                    positions.forEach((movement, index) => {
                        setTimeout(() => {
                            this.drawLine(this.ctx, movement.x, movement.y, movement.offsetX, movement.offsetY);
                        }, index * 15); //El valor es el tiempo de espera en milisegundos
                    });
                }

            }
        })
    },
    data() {
        return {
            isDrawing: false,
            canvas: null,
            ctx: null,
            flag: false,
            x: 0,
            y: 0,
            offsetX: null,
            offsetY: null,
            movements: []
        };
    },
    mounted() {
        this.init();
        window.addEventListener('resize', this.adjustCanvas);
        this.adjustCanvas();
    },
    methods: {
        sendCanvas(params) 
        {
            this.$emit("canvasupdate", {
                user: this.user,

                canvas: params,
            });
            console.log("canvas sent");
        },
        getParams() 
        {
            console.log("Dentro de getParams");
            const params = JSON.stringify(this.movements);
            this.sendCanvas(params)
        },
        init() 
        {
            this.canvas = document.getElementById('can');
            this.ctx = this.canvas.getContext("2d");

            this.canvas.addEventListener('mousedown', (e) => {
                this.x = e.offsetX;
                this.y = e.offsetY;
                this.isDrawing = true;
            });

            this.canvas.addEventListener('mousemove', (e) => {
                if (this.isDrawing) {
                    this.movements.push({ x: this.x, y: this.y, offsetX: e.offsetX, offsetY: e.offsetY });
                    this.drawLine(this.ctx, this.x, this.y, e.offsetX, e.offsetY);
                    this.x = e.offsetX;
                    this.y = e.offsetY;

                    if (this.movements.length >= 1000) {
                        this.getParams();
                        this.movements = [];
                    }
                }
            });

            this.canvas.addEventListener('mouseup', (e) => {
                if (this.isDrawing) {
                    this.movements.push({ x: this.x, y: this.y, offsetx: e.offsetX, offsetY: e.offsetY });
                    this.drawLine(this.ctx, this.x, this.y, e.offsetX, e.offsetY);
                    this.getParams();
                    this.x = 0;
                    this.y = 0;
                    this.isDrawing = false;
                    this.movements = [];
                }
            });

            let clear = document.getElementById('clearArea');

            clear.addEventListener('click', (e) => {
                this.clearArea();
                this.sendCanvas("clear");
            })
        },
        drawLine(ctx, x1, y1, x2, y2) 
        {
            ctx.beginPath();
            ctx.strokeStyle = document.getElementById('selColor').value;
            ctx.lineWidth = document.getElementById('selWidth').value;
            ctx.lineJoin = "round";
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
            ctx.closePath();
            ctx.stroke();
        },
        clearArea() 
        {
            this.ctx.setTransform(1, 0, 0, 1, 0, 0);
            this.ctx.clearRect(0, 0, this.ctx.canvas.width, this.ctx.canvas.height);
        },
        adjustCanvas()
        {
            let style = getComputedStyle(this.canvas);
            this.canvas.width = parseInt(style.width);
            this.canvas.height = parseInt(style.height);
        }
    }
}

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

.controls
{
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