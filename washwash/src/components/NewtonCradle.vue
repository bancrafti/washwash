<script setup>
import { onMounted, onUnmounted } from "vue";
import Matter from "matter-js";

let engine, render, runner;

onMounted(() => {
    const { Engine, Render, Runner, Bodies, Composite, Constraint } = Matter;

    engine = Engine.create();
    const world = engine.world;

    const canvasWidth = window.innerWidth;
    const canvasHeight = window.innerHeight;

    render = Render.create({
        element: document.getElementById("cradle-container"),
        engine: engine,
        options: {
            width: canvasWidth,
            height: canvasHeight,
            background: "transparent",
            wireframes: false
        }
    });

    Render.run(render);
    runner = Runner.create();
    Runner.run(runner, engine);

    const ballSize = Math.min(canvasWidth, canvasHeight) * 0.07;
    const startX = canvasWidth / 2 - ballSize * 4;
    const startY = canvasHeight * 0.3;
    const letters = ["W", "A", "S", "H"];

    function createNewtonCradle(xx, yy, size, length, letters) {
        const cradle = Composite.create({ label: "Newton's Cradle" });

        for (let i = 0; i < letters.length; i++) {
            const separation = 2;
            const ball = Bodies.circle(xx + i * (size * separation), yy + length, size, {
                inertia: Infinity,
                restitution: 1,
                friction: 0,
                frictionAir: 0,
                slop: size * 0.02,
                render: { fillStyle: "black" }
            });

            const rope = Constraint.create({
                pointA: { x: xx + i * (size * separation), y: yy },
                bodyB: ball
            });

            Composite.addBody(cradle, ball);
            Composite.addConstraint(cradle, rope);

            ball.letter = letters[i];
        }
        return cradle;
    }

    const cradle = createNewtonCradle(startX, startY, ballSize, ballSize * 4, letters);
    Composite.add(world, cradle);
    Matter.Body.translate(cradle.bodies[0], { x: -ballSize * 2, y: -ballSize });

    Render.lookAt(render, {
        min: { x: 0, y: 50 },
        max: { x: canvasWidth, y: canvasHeight }
    });

    // Draw letters inside circles
    (function drawLetters() {
        const context = render.context;
        requestAnimationFrame(drawLetters);
        context.font = `bold ${ballSize * 0.8}px Arial`;
        context.fillStyle = "white";
        context.textAlign = "center";
        context.textBaseline = "middle";

        for (let i = 0; i < cradle.bodies.length; i++) {
            const body = cradle.bodies[i];
            context.fillText(body.letter, body.position.x, body.position.y);
        }
    })();
});

onUnmounted(() => {
    Matter.Render.stop(render);
    Matter.Runner.stop(runner);
    Matter.World.clear(engine.world);
    Matter.Engine.clear(engine);
});
</script>

<template>
    <div id="cradle-container" class="absolute inset-0 z-0"></div>
</template>

<style scoped>
#cradle-container {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}
</style>
