// Client-side wishlist using localStorage. No account or database row needed.
(function () {
  const KEY = 'trailnest_wishlist';

  function getWishlist() {
    try { return JSON.parse(localStorage.getItem(KEY)) || []; }
    catch (e) { return []; }
  }
  function saveWishlist(list) {
    try { localStorage.setItem(KEY, JSON.stringify(list)); } catch (e) {}
  }
  function isSaved(id) { return getWishlist().includes(String(id)); }

  function toggle(id) {
    id = String(id);
    let list = getWishlist();
    if (list.includes(id)) list = list.filter(x => x !== id);
    else list.push(id);
    saveWishlist(list);
    refreshButtons();
    updateCount();
  }

  function refreshButtons() {
    document.querySelectorAll('[data-wishlist-btn]').forEach(btn => {
      const id = btn.getAttribute('data-wishlist-btn');
      const saved = isSaved(id);
      btn.classList.toggle('is-saved', saved);
      btn.innerHTML = saved ? '<i class="bi bi-bookmark-heart-fill"></i> Saved' : '<i class="bi bi-bookmark-heart"></i> Save';
    });
  }
  function updateCount() {
    const el = document.getElementById('wishlistCount');
    if (el) el.textContent = getWishlist().length;
  }

  function hideUnsaved() {
    document.querySelectorAll('[data-wishlist-card]').forEach(card => {
      const id = card.getAttribute('data-wishlist-card');
      card.style.display = isSaved(id) ? '' : 'none';
    });
    const empty = document.getElementById('wishlistEmpty');
    if (empty) empty.style.display = getWishlist().length ? 'none' : '';
  }

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-wishlist-btn]');
    if (btn) {
      e.preventDefault();
      toggle(btn.getAttribute('data-wishlist-btn'));
      if (document.getElementById('wishlistEmpty')) hideUnsaved();
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    refreshButtons();
    updateCount();
    if (document.getElementById('wishlistEmpty')) hideUnsaved();
  });
})();
