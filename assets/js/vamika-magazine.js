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
// 1. Prompt button → fill chat input
  // ============================================================
  document.querySelectorAll('.prompt-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const searchData = btn.dataset.search;
      const input = document.querySelector('.chat-input-row input[type="search"], .chat-input-row input[type="text"]');
      if (searchData && input && input.type === 'search') {
        /* Static card: fill search input and submit the form */
        input.value = searchData;
        var form = input.closest('form');
        if (form) { form.submit(); }
      } else if (input) {
        /* Plugin card: fill the AI chat input */
        const txt = btn.querySelector('.txt').textContent.trim();
        const cleaned = txt.replace(/^(Summarize|Discover|Related|Archive|Latest|Popular)/, '').trim();
        input.value = cleaned;
        input.focus();
      }
    });
  });

  // ============================================================
  // 2. Quick Summary — per-card "Summarize" pill
  // ============================================================
  // The .tldr button and its Iris-backed handler are owned by the Iris
  // Theme Kit plugin — markup via Card_Summary::render_button(),
  // wiring via vamika-iris.js initCardSummaries(). When the plugin is
  // inactive, nothing renders and nothing needs wiring.

  // ============================================================
  // 3. Floating dock + 4. Chat-card 3D tilt live in the Iris Theme
  //    Kit plugin (vamika-iris.js). Both bind to markup that only
  //    renders when the Iris AI plugin is active, so they belong
  //    with the plugin per the WordPress.org theme-review "plugin
  //    territory" rule. Nothing for the theme to wire here.
  // ============================================================

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

  // ============================================================
  // 6. Stat counter animation on scroll into view
  // ============================================================
  const statEls = document.querySelectorAll('.chat-stat .n');
  const animatedStats = new WeakSet();

  function animateNumber(el, targetText) {
    // detect format
    const isK = /K$/.test(targetText);
    const isS = /s$/.test(targetText);
    const numeric = parseFloat(targetText.replace(/[^0-9.]/g, ''));
    if (isNaN(numeric)) return;

    const duration = 1400;
    const start = performance.now();

    function tick(now) {
      const elapsed = now - start;
      const t = Math.min(1, elapsed / duration);
      // ease-out cubic
      const eased = 1 - Math.pow(1 - t, 3);
      const current = numeric * eased;

      let display;
      if (isK) display = current.toFixed(current < 10 ? 1 : 0) + 'K';
      else if (isS) display = current.toFixed(1) + 's';
      else display = Math.round(current).toLocaleString();

      el.textContent = display;
      if (t < 1) requestAnimationFrame(tick);
      else el.textContent = targetText;
    }
    requestAnimationFrame(tick);
  }

  const statObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !animatedStats.has(entry.target)) {
        animatedStats.add(entry.target);
        const original = entry.target.textContent;
        animateNumber(entry.target, original);
      }
    });
  }, { threshold: 0.5 });

  statEls.forEach(el => statObserver.observe(el));

  // ============================================================
  // 7. Scroll reveal — fade up on enter
  // ============================================================
  const revealTargets = document.querySelectorAll(
    '.feat, .story-card, .related-card, .section-title, .features .intro, .semantic-intro, .reading-now, .newsletter .inner'
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
