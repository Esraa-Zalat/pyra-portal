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
        box-sizing: border-box;
        padding: 12px 16px;
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        column-gap: 5%;
        align-items: center;
      }
      .buttons-bar .btn {
        height: 48px;
        border-radius: 8px;
        border: 1px solid #c7c7c7;
        background: #f6f6f6;
        color: #222;
        font: 600 14px/1 system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        cursor: pointer;
      }
      .buttons-bar .btn:hover { background: #efefef; }
      .buttons-bar .btn:focus-visible { outline: 3px solid rgba(0,119,255,.35); outline-offset: 2px; }
      .buttons-bar .btn.active { border-color: #0b6bff; background: #e9f2ff; }

      .stage { padding: 0; display: flex; align-items: center; justify-content: center; }
      .doors-row {
        width: 100%;
        height: calc(100svh - var(--header-h));
        display: flex;
        align-items: flex-end; /* align bottoms */
        justify-content: center; /* center the full row */
        gap: 0; /* doors should touch */
      }
      .door {
        /* Maximize size: constrained by height and total width */
        height: min(
          calc(100svh - var(--header-h)),
          calc(100vw / (7 * 1.2466))
        );
        width: auto;
        aspect-ratio: 902 / 724; /* normalized container ratio */
        background: transparent;
        border: 0;
        border-radius: 0;
        overflow: visible; /* allow artwork to extend */
        flex: 0 0 auto; /* don’t shrink */
      }
      .door svg { width: 100%; height: 100%; display: block; }
    </style>

    <!-- Overrides to maximize door size and add background image -->
    <style>
      .stage {
        align-items: flex-end;
        justify-content: center;
        background: url('{{ asset('bg/bg.png') }}') center bottom / cover no-repeat;
      }
      .doors-row { height: calc(100svh - var(--header-h)); }
      .door {
        height: calc(100svh - var(--header-h));
        width: calc(100vw / 7);
        overflow: hidden;
        flex: 0 0 calc(100vw / 7);
        position: relative;
      }
      .door svg { width: 100%; height: 100%; display: block; }
    </style>

    <!-- Lottie Web (JSON animations) -->
    <script src="https://cdn.jsdelivr.net/npm/lottie-web@5.12.2/build/player/lottie.min.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="page">
      <header class="buttons-bar">
        <button class="btn" data-src="{{ asset('doors/NurseryL.json') }}" aria-pressed="false">Door 1</button>
        <button class="btn" data-src="{{ asset('doors/door2.json') }}" aria-pressed="false">Door 2</button>
        <button class="btn" data-src="{{ asset('doors/door3.json') }}" aria-pressed="false">Door 3</button>
        <button class="btn" data-src="{{ asset('doors/door4.json') }}" aria-pressed="false">Door 4</button>
        <button class="btn" data-src="{{ asset('doors/door5.json') }}" aria-pressed="false">Door 5</button>
        <button class="btn" data-src="{{ asset('doors/door6.json') }}" aria-pressed="false">Door 6</button>
        <button class="btn" data-src="{{ asset('doors/NurseryR.json') }}" aria-pressed="false">Door 7</button>
      </header>

      <main class="stage">
        <div class="doors-row">
          <div id="door1" class="door" aria-label="Door 1 animation" title="Door 1"></div>
          <div id="door2" class="door" aria-label="Door 2 animation" title="Door 2"></div>
          <div id="door3" class="door" aria-label="Door 3 animation" title="Door 3"></div>
          <div id="door4" class="door" aria-label="Door 4 animation" title="Door 4"></div>
          <div id="door5" class="door" aria-label="Door 5 animation" title="Door 5"></div>
          <div id="door6" class="door" aria-label="Door 6 animation" title="Door 6"></div>
          <div id="door7" class="door" aria-label="Door 7 animation" title="Door 7"></div>
        </div>
      </main>
    </div>

    <script>
      (function () {
        // Measure header to allocate full remaining height to doors
        function setHeaderHeightVar() {
          const header = document.querySelector('.buttons-bar');
          if (header) {
            const h = header.offsetHeight || 0;
            document.documentElement.style.setProperty('--header-h', h + 'px');
          }
        }
        setHeaderHeightVar();
        window.addEventListener('load', setHeaderHeightVar);
        window.addEventListener('resize', setHeaderHeightVar);

        const buttons = Array.from(document.querySelectorAll('.buttons-bar .btn'));
        const doorIds = ['door1','door2','door3','door4','door5','door6','door7'];
        const containers = doorIds.map(id => document.getElementById(id));
        if (!buttons.length || containers.some(c => !c)) return;

        const LW = (window.lottie && typeof window.lottie.loadAnimation === 'function')
          ? window.lottie
          : (window.bodymovin && typeof window.bodymovin.loadAnimation === 'function')
            ? window.bodymovin
            : (window.lottie && window.lottie.default && typeof window.lottie.default.loadAnimation === 'function')
              ? window.lottie.default
              : null;

        if (!LW) {
          console.error('Lottie Web library not available.');
          return;
        }

        // Map animations by index
        const anims = new Array(7).fill(null);

        function setActive(btn) {
          buttons.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-pressed', 'false'); });
          if (btn) { btn.classList.add('active'); btn.setAttribute('aria-pressed', 'true'); }
        }

        async function initAnimAt(index, url) {
          try {
            const res = await fetch(url, { cache: 'no-cache' });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();

            if (anims[index]) { try { anims[index].destroy(); } catch (_) {} anims[index] = null; }
            containers[index].innerHTML = '';

            const anim = LW.loadAnimation({
              container: containers[index],
              renderer: 'svg',
              loop: false,
              autoplay: false,
              animationData: data,
              rendererSettings: { preserveAspectRatio: 'xMidYMax slice' }
            });
            anim.addEventListener('DOMLoaded', function onReady() {
              try { anim.stop(); } catch (_) {}
              anim.removeEventListener('DOMLoaded', onReady);
            });
            anims[index] = anim;
          } catch (e) {
            console.error('Failed to init animation', index + 1, e);
          }
        }

        // Preload and render all 7, paused
        buttons.forEach((btn, idx) => {
          const src = btn.dataset.src;
          if (src) initAnimAt(idx, src);
        });

        // Buttons: play respective animation from start only
        buttons.forEach((btn, idx) => {
          btn.addEventListener('click', () => {
            setActive(btn);
            const anim = anims[idx];
            if (anim) { try { anim.stop(); anim.play(); } catch (_) {} }
          });
          btn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              setActive(btn);
              const anim = anims[idx];
              if (anim) { try { anim.stop(); anim.play(); } catch (_) {} }
            }
          });
        });
      })();
    </script>
  </body>
  </html>
