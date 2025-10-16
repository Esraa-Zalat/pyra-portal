<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style/style.css">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        /* ===== MOBILE: 3 left, 3 right, 1 above door ===== */
        @media (max-width:1135px) {

            /* show your single-door background if you use one */
            .mobile-bg {
                display: block;
            }

            /* stretch hotspot overlay to the whole viewport on mobile */
            #hotspots.hotspots {
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                height: 100% !important;
                pointer-events: auto;
            }

            /* button size on mobile */
            #hotspots .hotspot {
                width: clamp(56px, 12vw, 84px);
                transform: translate(-50%, -50%);
            }

            /* tweak these to nudge positions if needed */
            :root {
                --left-x: 14vw;
                /* how far the left column is from the edge */
                --right-x: 80vw;
                /* how far the right column is from the edge */
                --row1: 15vh;
                /* vertical positions for rows */
                --row2: 20vh;
                --row3: 25vh;
                --top-y: -25vh;
                /* the one above the door */
            }

            /* Order mapping of your buttons (no HTML changes required):
     1=School, 2=Teacher, 3=Parent, 4=Student, 5=Company, 6=Shop, 7=Nursery */

            /* Left column (3) */
            #hotspots .hotspot:nth-of-type(1) {
                left: var(--left-x) !important;
                top: var(--row1) !important;
            }

            #hotspots .hotspot:nth-of-type(2) {
                left: var(--left-x) !important;
                top: var(--row2) !important;
            }

            #hotspots .hotspot:nth-of-type(3) {
                left: var(--left-x) !important;
                top: var(--row3) !important;
            }

            /* Above the door (Student) */
            #hotspots .hotspot:nth-of-type(4) {
                left: 50% !important;
                bottom: 0% !important;
            }

            /* Right column (3) */
            #hotspots .hotspot:nth-of-type(5) {
                left: var(--right-x) !important;
                bottom: 0%;
            }

            #hotspots .hotspot:nth-of-type(6) {
                left: var(--right-x) !important;
                bottom: 20% !important;
            }

            #hotspots .hotspot:nth-of-type(7) {
                left: var(--right-x) !important;
                bottom: 10% !important;
            }
        }

        /* Hotspots overlay sits over whichever player is visible */
        .hotspots {
            position: fixed;
            z-index: 2000;
            pointer-events: none;
            /* children stay clickable */
        }

        .hotspot {
            position: absolute;
            transform: translate(-50%, -50%);
            pointer-events: auto;
            border: 0;
            background: transparent;
            padding: 0;
            aspect-ratio: 1;
            display: grid;
            place-items: center;
            transition: transform .18s ease;
            /* width: clamp(44px, 6vw, 80px); */
        }

        .hotspot:hover {
            transform: translate(-50%, -50%) scale(1.05);
        }

        .hotspot:focus-visible {
            outline: 3px solid #fde68a;
            outline-offset: 3px;
            border-radius: 12px;
        }

        .hotspot img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        /* Door iris (zoom transition) */
        .door-iris {
            position: fixed;
            inset: 0;
            z-index: 4000;
            background: #0b0b0c;
            clip-path: circle(0 at var(--x, 50%) var(--y, 50%));
            transition: clip-path 750ms cubic-bezier(.2, .2, .2, 1);
        }

        .door-iris[hidden] {
            display: none;
        }

        .door-iris.active {
            clip-path: circle(160vmax at var(--x, 50%) var(--y, 50%));
        }

        /* Keep players above the mobile background image */
        .stage {
            position: relative;
        }

        .player {
            position: relative;
            z-index: 1;
        }

        /* MOBILE background image (only <1135px). Put your single-door image here. */
        .mobile-bg {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        .mobile-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center bottom;
        }

        /* ===== Mobile layout rules (< 1135px) ===== */
        @media (max-width:1135px) {

            /* show single-door bg; hide any CSS bg from style.css on stage */
            .mobile-bg {
                display: block;
            }

            .stage {
                background: none !important;
            }

            /* Turn hotspots into a responsive bottom dock */
            .hotspots {
                left: 0 !important;
                right: 0 !important;
                top: auto !important;
                bottom: 16px !important;
                width: 100% !important;
                height: auto !important;
                display: grid;
                pointer-events: auto;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: clamp(8px, 3.5vw, 16px);
                padding: 0 clamp(12px, 5vw, 28px);
                justify-items: center;
            }

            .hotspots .hotspot {
                position: relative;
                transform: none;
                width: clamp(56px, 14vw, 84px);
                /* comfy touch size */
            }

            /* really small phones: 3 per row */
            @media (max-width:420px) {
                .hotspots {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }
        }

        @media (prefers-reduced-motion:reduce) {
            .door-iris {
                transition: none;
            }

            .hotspot:hover {
                transform: none;
            }
        }
    </style>

    <!-- Lottie Web -->
    <script src="https://cdn.jsdelivr.net/npm/lottie-web@5.12.2/build/player/lottie.min.js" crossorigin="anonymous">
    </script>
</head>

<body>
    @include('includes.nav')

    <div class="page">
        <div class="buttons-bar" style="display:none"></div>

        <main class="stage">
            <!-- Mobile-only background (single door) -->
            <div class="mobile-bg" aria-hidden="true">
                <img src="{{ asset('bg/one-door-mobile.png') }}" alt="">
                <!-- ^^^ replace with your actual single-door image path/name -->
            </div>

            <div id="intro-player" class="player"></div>
            <div id="player" class="player" style="display:none;" aria-label="Selected animation area"></div>
        </main>
    </div>

    <!-- Hotspots -->
    <div id="hotspots" class="hotspots" aria-label="Door hotspots">
        <button class="hotspot" aria-label="School" data-src="{{ asset('doors/School.json') }}"
            data-target="{{ url('/school') }}" data-x="0.17" data-y="0.21"><img src="{{ asset('button/school.png') }}"
                alt=""></button>
        <button class="hotspot" aria-label="Teacher" data-src="{{ asset('doors/Teacher.json') }}"
            data-target="{{ url('/teachers') }}" data-x="0.29" data-y="0.23"><img
                src="{{ asset('button/teacher.png') }}" alt=""></button>
        <button class="hotspot" aria-label="Parent" data-src="{{ asset('doors/Parent.json') }}"
            data-target="{{ url('/parents') }}" data-x="0.40" data-y="0.24"><img
                src="{{ asset('button/parent.png') }}" alt=""></button>
        <button class="hotspot" aria-label="Student" data-src="{{ asset('doors/Student.json') }}"
            data-target="{{ url('/students') }}" data-x="0.50" data-y="0.24"><img
                src="{{ asset('button/student.png') }}" alt=""></button>
        <button class="hotspot" aria-label="Company" data-src="{{ asset('doors/Company.json') }}"
            data-target="{{ url('/my-company') }}" data-x="0.60" data-y="0.24"><img
                src="{{ asset('button/company.png') }}" alt=""></button>
        <button class="hotspot" aria-label="Shop" data-src="{{ asset('doors/Shop.json') }}"
            data-target="{{ url('/shops') }}" data-x="0.71" data-y="0.23"><img src="{{ asset('button/shop.png') }}"
                alt=""></button>
        <button class="hotspot" aria-label="Nursery" data-src="{{ asset('doors/NurseryR.json') }}"
            data-target="{{ url('/my-nursery') }}" data-x="0.83" data-y="0.22"><img
                src="{{ asset('button/nursery.png') }}" alt=""></button>
    </div>

    <!-- Door iris -->
    <div id="doorIris" class="door-iris" hidden></div>

    <script>
        (function() {
            const introEl = document.getElementById('intro-player');
            const playerEl = document.getElementById('player');
            const hotspots = document.getElementById('hotspots');
            const iris = document.getElementById('doorIris');

            const LW = window.lottie || window.bodymovin || (window.lottie && window.lottie.default);
            if (!LW) {
                console.error('Lottie Web not available');
                return;
            }

            const cache = new Map();
            let currentAnim = null;
            let introData = null; // intro JSON data (doors scene)
            let currentData = null; // selected door JSON data
            let lastClick = null; // {x,y} click for iris origin

            const STUDENT_SRC = "{{ asset('doors/Student.json') }}";
            const isMobile = () => window.matchMedia('(max-width:1135px)').matches;

            async function getAnimationData(url) {
                if (cache.has(url)) return cache.get(url);
                const res = await fetch(url, {
                    cache: 'no-cache'
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                cache.set(url, data);
                return data;
            }

            function getActiveContainer() {
                const introVisible = introEl.style.display !== 'none';
                return introVisible ? {
                    el: introEl,
                    data: introData
                } : {
                    el: playerEl,
                    data: currentData
                };
            }

            function positionHotspots() {
                // Mobile: dock at bottom (CSS handles layout). No per-button XY needed.
                if (isMobile()) {
                    hotspots.classList.add('mobile');
                    hotspots.style.left = '0px';
                    hotspots.style.right = '0px';
                    hotspots.style.top = '';
                    hotspots.style.bottom = '16px';
                    hotspots.style.width = '100%';
                    hotspots.style.height = '';
                    hotspots.querySelectorAll('.hotspot').forEach(btn => {
                        btn.style.left = '';
                        btn.style.top = '';
                    });
                    return;
                }

                // Desktop: overlay precisely over the Lottie (cover/slice fit)
                hotspots.classList.remove('mobile');
                const active = getActiveContainer();
                if (!active.data) return;

                const compW = active.data.w || 1920;
                const compH = active.data.h || 1080;

                const r = active.el.getBoundingClientRect();
                const contW = r.width,
                    contH = r.height;

                const scale = Math.max(contW / compW, contH / compH); // cover ('slice')
                const drawW = compW * scale;
                const drawH = compH * scale;
                const offsetX = r.left + (contW - drawW) / 2;
                const offsetY = r.top + (contH - drawH) / 2;

                hotspots.style.left = r.left + 'px';
                hotspots.style.top = r.top + 'px';
                hotspots.style.width = contW + 'px';
                hotspots.style.height = contH + 'px';

                hotspots.querySelectorAll('.hotspot').forEach(btn => {
                    const xn = parseFloat(btn.dataset.x || '0.5');
                    const yn = parseFloat(btn.dataset.y || '0.5');
                    const px = offsetX + xn * compW * scale;
                    const py = offsetY + yn * compH * scale;
                    btn.style.left = (px - r.left) + 'px';
                    btn.style.top = (py - r.top) + 'px';
                });
            }

            function irisOriginFrom(evtOrCenter) {
                let x, y;
                if (evtOrCenter && evtOrCenter.clientX != null) {
                    x = evtOrCenter.clientX;
                    y = evtOrCenter.clientY;
                } else {
                    const r = getActiveContainer().el.getBoundingClientRect();
                    x = r.left + r.width / 2;
                    y = r.top + r.height / 2;
                }
                iris.style.setProperty('--x', (x / innerWidth * 100) + '%');
                iris.style.setProperty('--y', (y / innerHeight * 100) + '%');
            }

            function resetIris() {
                iris.classList.remove('active');
                iris.hidden = true;
            }

            function goThroughDoor(url) {
                if (!url) return;
                const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
                if (reduce) {
                    window.location.assign(url);
                    return;
                }
                irisOriginFrom(lastClick || null);
                iris.hidden = false;
                void iris.offsetWidth;
                iris.classList.add('active');
                setTimeout(() => {
                    window.location.assign(url);
                }, 680);
            }

            async function playDoor(src, targetUrl) {
                try {
                    currentData = await getAnimationData(src);
                    if (currentAnim) {
                        try {
                            currentAnim.destroy();
                        } catch (_) {}
                        playerEl.innerHTML = '';
                    }
                    introEl.style.display = 'none';
                    playerEl.style.display = 'block';

                    currentAnim = LW.loadAnimation({
                        container: playerEl,
                        renderer: 'svg',
                        loop: false,
                        autoplay: true,
                        animationData: currentData,
                        rendererSettings: {
                            preserveAspectRatio: 'xMidYMid slice'
                        }
                    });

                    currentAnim.addEventListener('complete', () => {
                        goThroughDoor(targetUrl);
                    });

                    positionHotspots();
                } catch (e) {
                    console.error('Failed to play animation:', e);
                }
            }

            // Play intro and show hotspots on it
            (async function playIntro() {
                try {
                    introData = await getAnimationData("{{ asset('doors/intro.json') }}");
                    LW.loadAnimation({
                        container: introEl,
                        renderer: 'svg',
                        loop: false,
                        autoplay: true,
                        animationData: introData,
                        rendererSettings: {
                            preserveAspectRatio: 'xMidYMid slice'
                        }
                    });
                    hotspots.style.display = 'block';
                    positionHotspots();
                } catch (e) {
                    console.error('Intro load failed:', e);
                }
            })();

            // Preload JSONs (speeds up first play)
            document.querySelectorAll('#hotspots .hotspot').forEach(b => {
                const src = b.dataset.src;
                if (src) getAnimationData(src).catch(() => {});
            });
            getAnimationData(STUDENT_SRC).catch(() => {});

            // Wire hotspots (mobile: always play STUDENT_SRC)
            hotspots.querySelectorAll('.hotspot').forEach(btn => {
                const src = btn.dataset.src;
                const target = btn.dataset.target;

                const handler = (evt) => {
                    lastClick = (evt && evt.clientX != null) ? {
                        x: evt.clientX,
                        y: evt.clientY
                    } : null;
                    try {
                        const link = document.createElement('link');
                        link.rel = 'prefetch';
                        link.href = target;
                        document.head.appendChild(link);
                    } catch (_) {}
                    const srcToPlay = isMobile() ? STUDENT_SRC : src;
                    playDoor(srcToPlay, target);
                };

                btn.addEventListener('click', handler);
                btn.addEventListener('keydown', e => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        handler(e);
                    }
                });
            });

            // Keep layout in sync
            addEventListener('resize', positionHotspots);
            addEventListener('scroll', positionHotspots);

            // Back/restore fix (remove iris if coming back)
            window.addEventListener('pageshow', () => resetIris());
            window.addEventListener('popstate', () => resetIris());
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') resetIris();
            });
        })();
    </script>
</body>

</html>
