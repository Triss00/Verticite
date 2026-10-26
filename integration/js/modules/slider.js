/**
 * Carrousel :
 * Accroches : [data-slider] > [data-slider-track], [data-slider-prev],
 * [data-slider-next], [data-slider-progress].
 */
export function initSliders() {
  document.querySelectorAll('[data-slider]').forEach(setupSlider);
}

function setupSlider(root) {
  const track = root.querySelector('[data-slider-track]');
  const prev = root.querySelector('[data-slider-prev]');
  const next = root.querySelector('[data-slider-next]');
  const progress = root.querySelector('[data-slider-progress]');
  if (!track) return;

  const step = () => {
    const slide = track.firstElementChild;
    const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
    return slide ? slide.getBoundingClientRect().width + gap : track.clientWidth;
  };

  const update = () => {
    const max = track.scrollWidth - track.clientWidth;
    const ratio = max > 0 ? track.scrollLeft / max : 0;
    const visible = track.scrollWidth > 0 ? track.clientWidth / track.scrollWidth : 1;

    if (progress) {
      progress.style.setProperty('--progress-size', `${visible * 100}%`);
      // Le décalage est exprimé en % de la largeur de la barre elle-même
      progress.style.setProperty('--progress-offset', `${((1 - visible) / visible) * ratio * 100}%`);
    }
    if (prev) prev.disabled = track.scrollLeft <= 1;
    if (next) next.disabled = track.scrollLeft >= max - 1;
  };

  prev?.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
  next?.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
  track.addEventListener('scroll', update, { passive: true });
  window.addEventListener('resize', update);
  update();
}
