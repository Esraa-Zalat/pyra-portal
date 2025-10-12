<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link rel="stylesheet" href="style/style.css">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
  
    
    </style>

    <!-- Lottie Web -->
    <script src="https://cdn.jsdelivr.net/npm/lottie-web@5.12.2/build/player/lottie.min.js" crossorigin="anonymous"></script>
  </head>
  @include('includes.nav') 
  <body>

    <!-- ============ NAVBAR (closed by default) ============ -->
  
    <!-- ============ /NAVBAR ============ -->

    <div class="page">
      <div class="buttons-bar" style="width:75%; margin:auto; margin-button:-20px;">
        <button class="btn" data-src="{{ asset('doors/School.json') }}"   aria-pressed="false" aria-label="School"><img src="{{ asset('button/school.png') }}" alt="School" /></button>
        <button class="btn" data-src="{{ asset('doors/Teacher.json') }}"  aria-pressed="false" aria-label="Teacher"><img src="{{ asset('button/teacher.png') }}" alt="Teacher" /></button>
        <button class="btn" data-src="{{ asset('doors/Parent.json') }}"   aria-pressed="false" aria-label="Parent"><img src="{{ asset('button/parent.png') }}" alt="Parent" /></button>
        <button class="btn" data-src="{{ asset('doors/Student.json') }}"  aria-pressed="false" aria-label="Student"><img src="{{ asset('button/student.png') }}" alt="Student" /></button>
        <button class="btn" data-src="{{ asset('doors/Company.json') }}"  aria-pressed="false" aria-label="Company"><img src="{{ asset('button/company.png') }}" alt="Company" /></button>
        <button class="btn" data-src="{{ asset('doors/Shop.json') }}"     aria-pressed="false" aria-label="Shop"><img src="{{ asset('button/shop.png') }}" alt="Shop" /></button>
        <button class="btn" data-src="{{ asset('doors/NurseryR.json') }}" aria-pressed="false" aria-label="Nursery"><img src="{{ asset('button/nursery.png') }}" alt="Nursery" /></button>
      </div>

      <main class="stage" >
        <div id="intro-player" class="player" ></div>
        <div id="player" class="player" style="display:none;" aria-label="Selected animation area"></div>
      </main>
    </div>

    <script>
/* ===== NAV logic (closed by default) ===== */

/* ===== Your existing page script (unchanged except positioned below) ===== */
(function () {
  function setHeaderHeightVar() {
    const header = document.querySelector('.buttons-bar');
    const h = header ? header.offsetHeight : 0;
    document.documentElement.style.setProperty('--header-h', h + 'px');
  }
  setHeaderHeightVar();
  addEventListener('load', setHeaderHeightVar);
  addEventListener('resize', setHeaderHeightVar);

  const buttons = Array.from(document.querySelectorAll('.buttons-bar .btn'));
  const playerEl = document.getElementById('player');
  const introEl  = document.getElementById('intro-player');

  const LW = window.lottie || window.bodymovin || (window.lottie && window.lottie.default);
  if (!LW) { console.error('Lottie Web not available'); return; }

  const cache = new Map();
  let currentAnim = null;

  async function getAnimationData(url) {
    if (cache.has(url)) return cache.get(url);
    const res = await fetch(url, { cache: 'no-cache' });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const data = await res.json();
    cache.set(url, data);
    return data;
  }
  function setActiveButton(btn) {
    buttons.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-pressed', 'false'); });
    if (btn) { btn.classList.add('active'); btn.setAttribute('aria-pressed', 'true'); }
  }
  async function playSrc(url) {
    try {
      const data = await getAnimationData(url);
      if (currentAnim) { try { currentAnim.destroy(); } catch (_) {} playerEl.innerHTML=''; }
      currentAnim = LW.loadAnimation({
        container: playerEl, renderer: 'svg', loop: false, autoplay: true,
        animationData: data, rendererSettings: { preserveAspectRatio: 'xMidYMid slice' }
      });
    } catch (e) { console.error('Failed to play animation:', e); }
  }
  (async function playIntro() {
    try {
      const data = await getAnimationData("{{ asset('doors/intro.json') }}");
      LW.loadAnimation({
        container: introEl, renderer: 'svg', loop: false, autoplay: true,
        animationData: data, rendererSettings: { preserveAspectRatio: 'xMidYMid slice' }
      });
    } catch (e) { console.error("Intro load failed:", e); }
  })();
  buttons.forEach(b => { const src = b.dataset.src; if (src) getAnimationData(src).catch(()=>{}); });
  buttons.forEach((btn) => {
    const src = btn.dataset.src;
    const handler = () => {
      introEl.style.display = "none";
      playerEl.style.display = "flex";
      setActiveButton(btn);
      playSrc(src);
    };
    btn.addEventListener('click', handler);
    btn.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); handler(); }
    });
  });
})();
    </script>
  </body>
</html>
