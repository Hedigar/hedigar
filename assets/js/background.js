/**
 * Fundo animado: rede de nós + símbolos matemáticos flutuantes.
 * Pausa quando a aba não está visível e respeita prefers-reduced-motion.
 */
(() => {
  const canvas = document.getElementById("bg-canvas");
  if (!canvas) return;

  const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const ctx = canvas.getContext("2d");
  const symbols = ["∑", "∫", "π", "√", "∞", "Δ", "θ", "λ", "Ω", "φ"];

  let width = 0;
  let height = 0;
  let nodes = [];
  let glyphs = [];
  let raf = 0;
  let running = true;

  function resize() {
    width = canvas.width = window.innerWidth;
    height = canvas.height = window.innerHeight;
    spawn();
  }

  function spawn() {
    const density = Math.min(70, Math.floor((width * height) / 18000));
    nodes = Array.from({ length: density }, () => ({
      x: Math.random() * width,
      y: Math.random() * height,
      vx: (Math.random() - 0.5) * 0.35,
      vy: (Math.random() - 0.5) * 0.35,
    }));

    glyphs = Array.from({ length: 10 }, () => ({
      char: symbols[Math.floor(Math.random() * symbols.length)],
      x: Math.random() * width,
      y: Math.random() * height,
      s: 0.2 + Math.random() * 0.35,
      a: 0.08 + Math.random() * 0.12,
      r: Math.random() * Math.PI,
      vr: (Math.random() - 0.5) * 0.004,
    }));
  }

  function draw() {
    ctx.clearRect(0, 0, width, height);

    ctx.fillStyle = "rgba(0, 229, 255, 0.7)";
    nodes.forEach((node, i) => {
      node.x += node.vx;
      node.y += node.vy;

      if (node.x < 0 || node.x > width) node.vx *= -1;
      if (node.y < 0 || node.y > height) node.vy *= -1;

      ctx.beginPath();
      ctx.arc(node.x, node.y, 1.4, 0, Math.PI * 2);
      ctx.fill();

      for (let j = i + 1; j < nodes.length; j += 1) {
        const other = nodes[j];
        const dx = node.x - other.x;
        const dy = node.y - other.y;
        const dist = Math.hypot(dx, dy);
        if (dist < 130) {
          ctx.strokeStyle = `rgba(0, 229, 255, ${((130 - dist) / 130) * 0.18})`;
          ctx.lineWidth = 1;
          ctx.beginPath();
          ctx.moveTo(node.x, node.y);
          ctx.lineTo(other.x, other.y);
          ctx.stroke();
        }
      }
    });

    glyphs.forEach((g) => {
      g.r += g.vr;
      g.y -= g.s;
      if (g.y < -30) {
        g.y = height + 20;
        g.x = Math.random() * width;
      }
      ctx.save();
      ctx.translate(g.x, g.y);
      ctx.rotate(g.r);
      ctx.fillStyle = `rgba(181, 108, 255, ${g.a})`;
      ctx.font = "22px Orbitron, sans-serif";
      ctx.fillText(g.char, 0, 0);
      ctx.restore();
    });

    if (running) raf = requestAnimationFrame(draw);
  }

  function start() {
    if (prefersReduced) {
      canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
      return;
    }
    running = true;
    cancelAnimationFrame(raf);
    draw();
  }

  window.addEventListener("resize", resize, { passive: true });
  document.addEventListener("visibilitychange", () => {
    running = document.visibilityState === "visible";
    if (running) start();
  });

  resize();
  start();
})();
