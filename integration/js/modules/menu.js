/**
 * Menu mobile :
 * [data-menu-button] (aria-controls → id du panneau).
 */
export function initMenu() {
  const button = document.querySelector('[data-menu-button]');
  if (!button) return;

  const panel = document.getElementById(button.getAttribute('aria-controls'));
  if (!panel) return;

  const setOpen = (open) => {
    button.setAttribute('aria-expanded', String(open));
    button.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
    panel.classList.toggle('is-open', open);
  };

  button.addEventListener('click', () => {
    setOpen(button.getAttribute('aria-expanded') !== 'true');
  });

  // Échap ferme le menu et rend le focus au bouton
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
      setOpen(false);
      button.focus();
    }
  });

  // Repasse en état fermé quand on revient au format tablette / desktop
  window.matchMedia('(min-width: 768px)').addEventListener('change', (event) => {
    if (event.matches) setOpen(false);
  });
}
