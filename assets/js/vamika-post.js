(() => {
'use strict';

const topnav = document.querySelector('.topnav');
const menuToggle = document.querySelector('.menu-toggle');
const navPanel = document.getElementById('site-menu');

function setMenuState(isOpen) {
  if (!topnav || !menuToggle || !navPanel) return;
  topnav.classList.toggle('menu-open', isOpen);
  menuToggle.setAttribute('aria-expanded', String(isOpen));
  menuToggle.querySelector('.menu-label-open')?.toggleAttribute('hidden', isOpen);
  menuToggle.querySelector('.menu-label-close')?.toggleAttribute('hidden', !isOpen);
}

menuToggle?.addEventListener('click', (event) => {
  event.stopPropagation();
  setMenuState(!topnav?.classList.contains('menu-open'));
});

document.addEventListener('keydown', (event) => {
  if (
    !topnav?.classList.contains('menu-open') ||
    !menuToggle ||
    !navPanel ||
    window.innerWidth > 720
  ) {
    return;
  }

  if (event.key === 'Escape') {
    event.preventDefault();
    setMenuState(false);
    menuToggle.focus();
    return;
  }

  if (event.key !== 'Tab') return;

  const menuItems = Array.from(
    navPanel.querySelectorAll(
      'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
    )
  ).filter((item) => item.getClientRects().length > 0 && item.tabIndex >= 0);

  if (!menuItems.length) {
    event.preventDefault();
    menuToggle.focus();
    return;
  }

  const firstItem = menuItems[0];
  const lastItem = menuItems[menuItems.length - 1];
  const activeElement = document.activeElement;

  if (event.shiftKey && activeElement === firstItem) {
    event.preventDefault();
    menuToggle.focus();
  } else if (event.shiftKey && activeElement === menuToggle) {
    event.preventDefault();
    lastItem.focus();
  } else if (!event.shiftKey && activeElement === lastItem) {
    event.preventDefault();
    menuToggle.focus();
  } else if (!event.shiftKey && activeElement === menuToggle) {
    event.preventDefault();
    firstItem.focus();
  }
});

document.addEventListener('click', (event) => {
  if (!topnav?.classList.contains('menu-open')) return;
  if (topnav.contains(event.target)) return;
  setMenuState(false);
});

window.addEventListener('resize', () => {
  if (window.innerWidth > 720) setMenuState(false);
});

const currentPath = window.location.pathname.split('/').pop() || 'index.html';
document.querySelectorAll('.navlinks a[href]').forEach((link) => {
  const href = link.getAttribute('href');
  if (!href || href.startsWith('#')) return;
  if (href === currentPath) {
    link.setAttribute('aria-current', 'page');
  }
});

document.querySelectorAll('[data-href]').forEach((target) => {
  target.addEventListener('click', (event) => {
    const interactiveChild = event.target.closest('a, button, input, textarea, select, label');
    if (interactiveChild && interactiveChild !== target) return;
    window.location.href = target.dataset.href;
  });

  target.addEventListener('keydown', (event) => {
    if (event.key !== 'Enter' && event.key !== ' ') return;
    event.preventDefault();
    window.location.href = target.dataset.href;
  });
});

// ============================================================
// 1. Reading progress bar
  // ============================================================
  const progressBar = document.getElementById('progressBar');
  const articleBody = document.getElementById('articleBody');

  function updateProgress() {
    if (!articleBody || !progressBar) return;
    const rect = articleBody.getBoundingClientRect();
    const total = articleBody.offsetHeight - window.innerHeight + rect.top;
    const scrolled = Math.max(0, -rect.top);
    const pct = Math.min(100, Math.max(0, (scrolled / total) * 100));
    progressBar.style.width = pct + '%';
  }

  window.addEventListener('scroll', updateProgress, { passive: true });
  updateProgress();

  // ============================================================
  // 2. Iris Summary bar
  // ============================================================
  // Rendered and wired by the Iris Theme Kit plugin's vamika-iris.js
  // (initPostSummary). The theme no longer ships a summary-toggle
  // handler — a fake "Regenerating…" animation was misleading UX.

  // ============================================================
  // 4. Floating dock — rendered and wired by the Iris Theme Kit
  //    plugin (Iris_Dock + vamika-iris.js initIrisDock). The theme
  //    no longer ships dock JS — the markup only exists when the
  //    Iris AI plugin is active.
  // ============================================================

  // ============================================================
  // 4b. Share-float toolbar
  // ============================================================
  (function () {
    /* ---- Share button ---- */
    const shareBtn = document.querySelector('.sf-share');
    if (shareBtn) {
      shareBtn.addEventListener('click', function () {
        const url   = this.dataset.url || window.location.href;
        const title = this.dataset.title || document.title;

        if (navigator.share) {
          navigator.share({ title: title, url: url }).catch(function () {});
        } else {
          navigator.clipboard.writeText(url).then(function () {
            shareBtn.classList.add('sf-copied');
            const orig = shareBtn.innerHTML;
            shareBtn.innerHTML = '✓';
            setTimeout(function () {
              shareBtn.innerHTML = orig;
              shareBtn.classList.remove('sf-copied');
            }, 1500);
          }).catch(function () {
            /* Last-resort fallback for very old browsers */
            window.prompt('Copy this link:', url);
          });
        }
      });
    }

    /* ---- Comment button — scroll to comments ---- */
    const commentBtn = document.querySelector('.sf-comment');
    if (commentBtn) {
      commentBtn.addEventListener('click', function () {
        var target = document.getElementById('comments') || document.getElementById('respond');
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    }

  })();

  // ============================================================
  // 5. Magnetic buttons
  // ============================================================
  document.querySelectorAll('.magnetic').forEach(el => {
    el.addEventListener('mousemove', (e) => {
      const rect = el.getBoundingClientRect();
      const x = e.clientX - rect.left - rect.width / 2;
      const y = e.clientY - rect.top - rect.height / 2;
      el.style.transform = `translate(${x * 0.25}px, ${y * 0.35}px)`;
    });
    el.addEventListener('mouseleave', () => {
      el.style.transform = 'translate(0, 0)';
    });
  });
  // Note: the Iris-icon "random glance" animation (eyes looking around)
  // was removed from the theme — that markup only exists when the Iris
  // Theme Kit plugin is active, and its JS ships with the plugin.

  // ============================================================
  // 6. Scroll reveal
  // ============================================================
  const revealTargets = document.querySelectorAll(
    '.summary-box, .pullquote, .inline-image, .author-card, .related-card, .related-header, .reactions .reaction-row, .keep-reading .inner'
  );
  revealTargets.forEach(el => el.classList.add('reveal'));

  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

  revealTargets.forEach(el => revealObserver.observe(el));

})();
