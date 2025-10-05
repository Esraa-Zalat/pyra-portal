<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
      html, body { height: 100%; margin: 0; }
      body { background: #ffffff; }
      :root { --header-h: 0px; }

      .page {
        min-height: 100vh;
        min-height: 100svh;
        display: grid;
        grid-template-rows: auto 1fr;
      }

      .buttons-bar {
        width: 100%;
        /* box-sizing: border-box; */
        padding: 12px 16px;
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        column-gap: 5%;
        align-items: center;
      }
      .buttons-bar .btn {
        /* height: 48px; */
        margin-bottom:-550px;
        z-index: 1000;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border:none;
        background:transparent;
      
      }
      .buttons-bar .btn img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
      }
      /* .buttons-bar .btn:hover { background: #efefef; }
      .buttons-bar .btn:focus-visible { outline: 3px solid rgba(0,119,255,.35); outline-offset: 2px; }
      .buttons-bar .btn.active { border-color: #0b6bff; background: #e9f2ff; } */

      /* Stage centers the player */
    /* Fullscreen player */
.player {
  flex: 1;                     /* expand to fill stage */
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.player > svg, .player > canvas, .player > div {
  width: 100% !important;
  height: 100% !important;
}
.stage {
  min-height: calc(100svh - var(--header-h));
  display: flex;
  align-items: center;
  justify-content: center;
  background: url('{{ asset('bg/bg.png') }}') center bottom / cover no-repeat;
}



    </style>

    <!-- Lottie Web -->
    <script src="https://cdn.jsdelivr.net/npm/lottie-web@5.12.2/build/player/lottie.min.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="page">
      
    
        <div class="buttons-bar"  style="width:75%; margin:auto; margin-button:-20px;">
        <button class="btn" data-src="{{ asset('doors/School.json') }}"   aria-pressed="false" aria-label="School"><img src="{{ asset('button/school.png') }}" alt="School" /></button>
        <button class="btn" data-src="{{ asset('doors/Teacher.json') }}"  aria-pressed="false" aria-label="Teacher"><img src="{{ asset('button/teacher.png') }}" alt="Teacher" /></button>
        <button class="btn" data-src="{{ asset('doors/Parent.json') }}"   aria-pressed="false" aria-label="Parent"><img src="{{ asset('button/parent.png') }}" alt="Parent" /></button>
        <button class="btn" data-src="{{ asset('doors/Student.json') }}"  aria-pressed="false" aria-label="Student"><img src="{{ asset('button/student.png') }}" alt="Student" /></button>
        <button class="btn" data-src="{{ asset('doors/Company.json') }}"  aria-pressed="false" aria-label="Company"><img src="{{ asset('button/company.png') }}" alt="Company" /></button>
        <button class="btn" data-src="{{ asset('doors/Shop.json') }}"     aria-pressed="false" aria-label="Shop"><img src="{{ asset('button/shop.png') }}" alt="Shop" /></button>
        <button class="btn" data-src="{{ asset('doors/NurseryR.json') }}" aria-pressed="false" aria-label="Nursery"><img src="{{ asset('button/nursery.png') }}" alt="Nursery" /></button>
</div>

      <main class="stage">
        <!-- Single centered player -->


          <div id="intro-player" class="player"></div>
       <div id="player" class="player" style="display:none;" aria-label="Selected animation area"></div>
      </main>
    </div>

<script>
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

  // Cache JSONs
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
        container: playerEl,
        renderer: 'svg',
        loop: false,
        autoplay: true,
        animationData: data,
        rendererSettings: { preserveAspectRatio: 'xMidYMid slice' }
      });
    } catch (e) { console.error('Failed to play animation:', e); }
  }

  // --- NEW: Play intro.json immediately ---
  (async function playIntro() {
    try {
      const data = await getAnimationData("{{ asset('doors/intro.json') }}");
      const introAnim = LW.loadAnimation({
        container: introEl,
        renderer: 'svg',
        loop: false,
        autoplay: true,
        animationData: data,
        rendererSettings: { preserveAspectRatio: 'xMidYMid slice' }
      });
    } catch (e) {
      console.error("Intro load failed:", e);
    }
  })();

  // Preload all door JSONs
  buttons.forEach(b => {
    const src = b.dataset.src;
    if (src) getAnimationData(src).catch(() => {});
  });

  // Wire buttons (hide intro, show main player, then run normal function)
  buttons.forEach((btn, idx) => {
    const src = btn.dataset.src;
    const handler = () => {
      // hide intro if still visible
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
