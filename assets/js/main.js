/* Blossom Public School — front-end behaviour. Vanilla JS, no dependencies. */
(function () {
  'use strict';

  var $  = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  /* ---- sticky header shadow ------------------------------------- */
  var header = $('.site-header');
  var toTop  = $('.fl-top');
  function onScroll() {
    var y = window.scrollY || document.documentElement.scrollTop;
    if (header) header.classList.toggle('is-stuck', y > 8);
    if (toTop)  toTop.classList.toggle('is-on', y > 520);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (toTop) {
    toTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ---- mobile drawer -------------------------------------------- */
  var burger = $('.burger');
  var drawer = $('.drawer');
  var scrim  = $('.scrim');
  function setDrawer(open) {
    if (!drawer) return;
    drawer.classList.toggle('is-open', open);
    if (scrim)  scrim.classList.toggle('is-open', open);
    if (burger) { burger.classList.toggle('is-open', open); burger.setAttribute('aria-expanded', String(open)); }
    document.body.classList.toggle('no-scroll', open);
  }
  if (burger) burger.addEventListener('click', function () { setDrawer(!drawer.classList.contains('is-open')); });
  if (scrim)  scrim.addEventListener('click', function () { setDrawer(false); });
  $$('.drawer__close, .drawer__nav a').forEach(function (el) {
    el.addEventListener('click', function () { setDrawer(false); });
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { setDrawer(false); closeLightbox(); }
  });

  /* ---- scroll reveal --------------------------------------------- */
  var revealables = $$('[data-reveal]');
  if ('IntersectionObserver' in window && revealables.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
    revealables.forEach(function (el) { io.observe(el); });
  } else {
    revealables.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* ---- animated counters ----------------------------------------- */
  var counters = $$('[data-count]');
  function runCounter(el) {
    var target = parseFloat(el.getAttribute('data-count')) || 0;
    var suffix = el.getAttribute('data-suffix') || '';
    var dur = 1500, start = null;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(target * eased).toLocaleString('en-IN') + suffix;
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  if ('IntersectionObserver' in window && counters.length) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { runCounter(en.target); cio.unobserve(en.target); }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (el) { cio.observe(el); });
  } else {
    counters.forEach(runCounter);
  }

  /* ---- hero slideshow -------------------------------------------- */
  var slides = $$('.hero__slide');
  var dots   = $$('.hero__dots button');
  var idx = 0, timer = null;
  function goTo(n) {
    if (!slides.length) return;
    idx = (n + slides.length) % slides.length;
    slides.forEach(function (s, i) { s.classList.toggle('is-active', i === idx); });
    dots.forEach(function (d, i) { d.classList.toggle('is-active', i === idx); });
  }
  function play() { timer = setInterval(function () { goTo(idx + 1); }, 5600); }
  function reset() { clearInterval(timer); play(); }
  if (slides.length > 1) {
    goTo(0); play();
    dots.forEach(function (d, i) { d.addEventListener('click', function () { goTo(i); reset(); }); });
  }

  /* ---- accordion --------------------------------------------------- */
  $$('.acc__q').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = btn.closest('.acc');
      var panel = $('.acc__a', item);
      var open = item.classList.contains('is-open');
      var group = btn.closest('[data-acc-group]');
      if (group) {
        $$('.acc', group).forEach(function (other) {
          if (other !== item) {
            other.classList.remove('is-open');
            var p = $('.acc__a', other); if (p) p.style.maxHeight = null;
            var b = $('.acc__q', other); if (b) b.setAttribute('aria-expanded', 'false');
          }
        });
      }
      item.classList.toggle('is-open', !open);
      btn.setAttribute('aria-expanded', String(!open));
      panel.style.maxHeight = open ? null : panel.scrollHeight + 'px';
    });
  });

  /* ---- gallery filter + lightbox ------------------------------------ */
  $$('.filters button').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var f = btn.getAttribute('data-filter');
      $$('.filters button').forEach(function (b) { b.classList.toggle('is-active', b === btn); });
      $$('.gal__item').forEach(function (item) {
        var show = f === 'all' || item.getAttribute('data-cat') === f;
        item.classList.toggle('is-hidden', !show);
      });
    });
  });

  var lb = $('.lightbox');
  function closeLightbox() {
    if (!lb) return;
    lb.classList.remove('is-open');
    document.body.classList.remove('no-scroll');
  }
  $$('.gal__item').forEach(function (item) {
    item.addEventListener('click', function () {
      if (!lb) return;
      var holder = $('.lightbox__inner .media-slot', lb);
      var cap = $('.lightbox__cap', lb);
      var fig = $('.media', item);
      if (holder && fig) holder.innerHTML = fig.outerHTML;
      if (cap) cap.textContent = item.getAttribute('data-caption') || '';
      lb.classList.add('is-open');
      document.body.classList.add('no-scroll');
    });
  });
  if (lb) {
    lb.addEventListener('click', function (e) {
      if (e.target === lb || e.target.closest('.lightbox__close')) closeLightbox();
    });
  }

  /* ---- current year ------------------------------------------------- */
  $$('[data-year]').forEach(function (el) { el.textContent = String(new Date().getFullYear()); });
})();
