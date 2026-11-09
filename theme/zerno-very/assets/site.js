(() => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('#navigation');
  const closeMenu = () => { nav?.classList.remove('open'); toggle?.setAttribute('aria-expanded', 'false'); toggle?.setAttribute('aria-label', 'Открыть меню'); };
  toggle?.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Закрыть меню' : 'Открыть меню');
    nav?.classList.toggle('open', open);
  });
  nav?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
  document.addEventListener('keydown', e => { if(e.key === 'Escape') closeMenu(); });
  const dialog = document.querySelector('#booking-dialog');
  document.querySelectorAll('.booking-pending').forEach(button => button.addEventListener('click', () => dialog?.showModal()));
  dialog?.querySelectorAll('.dialog-close, .dialog-done').forEach(button => button.addEventListener('click', () => dialog.close()));
  dialog?.addEventListener('click', e => { if(e.target === dialog) { const r = dialog.getBoundingClientRect(); if(e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom) dialog.close(); } });
})();

(() => {
  const notice = document.querySelector('#cookie-notice');
  const dismiss = document.querySelector('#cookie-dismiss');
  const details = document.querySelector('#cookie-details');
  const storageKey = 'zv-cookie-notice-v1';
  const ttl = 180 * 24 * 60 * 60 * 1000;
  let seen = false;
  try {
    const saved = JSON.parse(localStorage.getItem(storageKey));
    seen = saved?.version === 1 && typeof saved.at === 'number' && saved.at <= Date.now() && Date.now() - saved.at < ttl;
  } catch (_) { /* Storage may be disabled; the page remains usable. */ }
  if (notice) notice.hidden = seen;
  dismiss?.addEventListener('click', () => {
    try { localStorage.setItem(storageKey, JSON.stringify({version:1, at:Date.now()})); } catch (_) {}
    notice.hidden = true;
  });
  document.querySelectorAll('[data-cookie-details]').forEach(button => button.addEventListener('click', () => details?.showModal()));
  details?.querySelectorAll('.dialog-close, .dialog-done').forEach(button => button.addEventListener('click', () => details.close()));
})();
