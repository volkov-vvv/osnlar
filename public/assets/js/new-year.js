(function () {
    'use strict';

    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    var host = document.getElementById('ny-snow');
    if (!host) {
        return;
    }

    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');
    if (!ctx) {
        return;
    }

    host.appendChild(canvas);

    var flakes = [];
    var width = 0;
    var height = 0;
    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    var reducedCount = window.matchMedia('(max-width: 768px)').matches;

    function resize() {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = Math.floor(width * dpr);
        canvas.height = Math.floor(height * dpr);
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function createFlake() {
        return {
            x: Math.random() * width,
            y: Math.random() * height,
            size: 4 + Math.random() * 7,
            speed: 0.35 + Math.random() * 1.1,
            drift: -0.35 + Math.random() * 0.7,
            opacity: 0.4 + Math.random() * 0.5,
            rot: Math.random() * Math.PI,
            spin: (-0.01 + Math.random() * 0.02)
        };
    }

    function initFlakes() {
        var count = reducedCount ? 12 : 22;
        flakes = [];
        for (var i = 0; i < count; i++) {
            flakes.push(createFlake());
        }
    }

    function drawSnowflake(f) {
        var arms = 6;
        var len = f.size;
        var branch = len * 0.38;

        ctx.save();
        ctx.translate(f.x, f.y);
        ctx.rotate(f.rot);
        ctx.strokeStyle = 'rgba(150, 190, 230, ' + f.opacity + ')';
        ctx.lineWidth = Math.max(0.9, f.size * 0.14);
        ctx.lineCap = 'round';
        ctx.shadowColor = 'rgba(100, 140, 190, 0.35)';
        ctx.shadowBlur = 2;

        for (var a = 0; a < arms; a++) {
            ctx.rotate(Math.PI / 3);
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(0, -len);
            ctx.stroke();

            ctx.beginPath();
            ctx.moveTo(0, -len * 0.55);
            ctx.lineTo(-branch * 0.55, -len * 0.55 - branch * 0.45);
            ctx.moveTo(0, -len * 0.55);
            ctx.lineTo(branch * 0.55, -len * 0.55 - branch * 0.45);
            ctx.stroke();

            ctx.beginPath();
            ctx.moveTo(0, -len * 0.3);
            ctx.lineTo(-branch * 0.35, -len * 0.3 - branch * 0.3);
            ctx.moveTo(0, -len * 0.3);
            ctx.lineTo(branch * 0.35, -len * 0.3 - branch * 0.3);
            ctx.stroke();
        }

        ctx.beginPath();
        ctx.arc(0, 0, Math.max(0.8, f.size * 0.12), 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(170, 205, 235, ' + f.opacity + ')';
        ctx.fill();
        ctx.restore();
    }

    function draw() {
        ctx.clearRect(0, 0, width, height);
        for (var i = 0; i < flakes.length; i++) {
            var f = flakes[i];
            f.y += f.speed;
            f.x += f.drift + Math.sin((f.y + i) * 0.012) * 0.35;
            f.rot += f.spin;

            if (f.y > height + f.size) {
                f.y = -f.size;
                f.x = Math.random() * width;
            }
            if (f.x > width + f.size) {
                f.x = -f.size;
            } else if (f.x < -f.size) {
                f.x = width + f.size;
            }

            drawSnowflake(f);
        }
        window.requestAnimationFrame(draw);
    }

    resize();
    initFlakes();
    draw();

    var resizeTimer = null;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            reducedCount = window.matchMedia('(max-width: 768px)').matches;
            resize();
            initFlakes();
        }, 150);
    });
})();
