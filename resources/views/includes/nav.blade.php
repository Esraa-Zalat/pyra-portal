
<header class="pmz-navbar">
  <div class="pmz-nav-inner">
    <a class="pmz-brand" href="{{ url('/') }}">
      <img class="pmz-logo" src="{{ asset('logo.png') }}" alt="Pyramakerz" />
    </a>

    <button class="pmz-hamburger" id="pmzMenuBtn"
            aria-label="Open menu" aria-expanded="false" aria-controls="pmzPanel">
      <span class="pmz-bars" aria-hidden="true"></span>
    </button>
  </div>
</header>

<div class="pmz-overlay" id="pmzOverlay" hidden></div>

<aside class="pmz-panel" id="pmzPanel" aria-hidden="true" aria-labelledby="pmzMenuBtn">
  <button class="pmz-panel-close" id="pmzCloseBtn" aria-label="Close menu">
    <svg viewBox="0 0 24 24" width="16" height="16" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
         aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
  </button>

  <div class="pmz-panel-content">
    <nav class="pmz-sidenav" aria-label="Mobile">
      <a href="{{ url('/') }}" @if(request()->is('/')) aria-current="page" @endif>HOME</a>
      <a href="{{ route('about') }}" @if(request()->routeIs('about')) aria-current="page" @endif>ABOUT US</a>
      <a href="{{ url('/subsidiaries') }}" @if(request()->is('subsidiaries')) aria-current="page" @endif>SUBSIDIARIES COMPANIES</a>
      <a href="{{ url('/contact') }}" @if(request()->is('contact')) aria-current="page" @endif>CONTACT US</a>
    </nav>

    <section>
      <div class="pmz-preview" aria-hidden="true"></div>
      <h2 class="pmz-title">Wanna Enhance Your Future?</h2>
      <div class="pmz-links">
        <a href="{{ url('/contact') }}">Contact Us</a>
        <a href="{{ url('/login') }}">Log In</a>
      </div>
    </section>
  </div>
</aside>


<script>
(function(){
  const body = document.body;
  const overlay = document.getElementById('pmzOverlay');
  const panel = document.getElementById('pmzPanel');
  const openBtn = document.getElementById('pmzMenuBtn');
  const closeBtn = document.getElementById('pmzCloseBtn');

  function openMenu(){
    body.classList.add('pmz-open');
    overlay.hidden = false;
    panel.setAttribute('aria-hidden','false');
    openBtn.setAttribute('aria-expanded','true');
    openBtn.setAttribute('aria-label','Close menu');
    body.style.overflow='hidden';
  }
  function closeMenu(){
    body.classList.remove('pmz-open');
    panel.setAttribute('aria-hidden','true');
    openBtn.setAttribute('aria-expanded','false');
    openBtn.setAttribute('aria-label','Open menu');
    setTimeout(()=>{ overlay.hidden = true; }, 200);
    body.style.overflow='';
  }
  function toggleMenu(){ body.classList.contains('pmz-open') ? closeMenu() : openMenu(); }

  openBtn?.addEventListener('click', toggleMenu);
  overlay?.addEventListener('click', closeMenu);
  closeBtn?.addEventListener('click', closeMenu);
  window.addEventListener('keydown', e => { if(e.key==='Escape') closeMenu(); });

  // close the panel after tapping a link
  panel?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
})();
</script>

