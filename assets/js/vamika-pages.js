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

const categoryFilterSections = document.querySelectorAll('.category-filters');

categoryFilterSections.forEach((section) => {
  const groups = [];
  let currentGroup = [];

  section.querySelectorAll('.filter-pill, .label').forEach((el) => {
    if (el.classList.contains('label')) {
      if (currentGroup.length) groups.push(currentGroup);
      currentGroup = [];
    } else {
      currentGroup.push(el);
    }
  });

  if (currentGroup.length) groups.push(currentGroup);

  groups.forEach((group) => {
    group.forEach((pill) => {
      pill.addEventListener('click', () => {
        group.forEach((item) => item.classList.remove('active'));
        pill.classList.add('active');
      });
    });
  });
});

})();
