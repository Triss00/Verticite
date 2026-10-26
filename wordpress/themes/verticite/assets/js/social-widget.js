document.addEventListener('click', (e) => {
  const add = e.target.closest('.js-social-add');
  const remove = e.target.closest('.js-social-remove');
  if (!add && !remove) return;

  const box = e.target.closest('.js-social');

  if (add) {
    const html = box.querySelector('template').innerHTML.replaceAll('__row__', Date.now());
    box.querySelector('.js-social-rows').insertAdjacentHTML('beforeend', html);
  } else {
    remove.closest('.js-social-row').remove();
  }

  // Signale la modification à WordPress pour activer le bouton "Enregistrer"
  jQuery(box).find('input').first().trigger('change');
});