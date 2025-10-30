// homepage.js — Guessr
// --- Modal + AJAX loader (accessible & responsive-ready) ---
(function () {
  const modal = document.getElementById('modal');
  const content = document.getElementById('modal-content');
  const title = document.getElementById('modal-title');
  const btnClose = document.getElementById('modal-close');
  let lastFocused = null;

  function openModal(t, url) {
    lastFocused = document.activeElement;
    title.textContent = t || '';
    content.innerHTML = '<p class="placeholder">Chargement…</p>';
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    btnClose.focus();

    if (url) {
      fetch(url, { credentials: 'same-origin' })
        .then((r) => (r.ok ? r.text() : Promise.reject(r.status)))
        .then((html) => {
          // Insère du HTML depuis les pages internes
          content.innerHTML = html;
        })
        .catch(() => {
          content.innerHTML =
            '<p class="placeholder">Impossible de charger le contenu.</p>';
        });
    }
  }

  function closeModal() {
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (lastFocused) lastFocused.focus();
  }

  // Ouvre depuis les boutons/pseudolinks
  document.querySelectorAll('.modal-open').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openModal(btn.dataset.title, btn.dataset.url);
    });
  });

  // Interactions pour fermer
  btnClose.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false')
      closeModal();
  });
})();
