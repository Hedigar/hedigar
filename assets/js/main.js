/**
 * Comportamento da Home: navegação mobile, header e ano do rodapé.
 */
(() => {
  const header = document.querySelector("[data-header]");
  const toggle = document.querySelector("[data-nav-toggle]");
  const nav = document.querySelector("[data-nav]");
  const year = document.querySelector("[data-year]");

  if (year) year.textContent = String(new Date().getFullYear());

  function setNav(open) {
    if (!toggle || !nav) return;
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? "Fechar menu" : "Abrir menu");
    nav.classList.toggle("is-open", open);
  }

  toggle?.addEventListener("click", () => {
    const open = toggle.getAttribute("aria-expanded") !== "true";
    setNav(open);
  });

  nav?.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => setNav(false));
  });

  window.addEventListener(
    "scroll",
    () => {
      header?.classList.toggle("is-scrolled", window.scrollY > 8);
    },
    { passive: true }
  );
})();
