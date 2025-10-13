<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style/style.css">

    <title>About Us | Pyramakerz</title>

    <style>

    </style>
</head>

<body>
    @include('includes.nav')
    <!-- HERO -->
    <header class="hero">
        <img src="{{ asset('about/about-hero.png') }}" alt="Pyramakerz — About hero">
    </header>

    <main>
        <!-- VIDEO + EXECUTIVE SUMMARY -->
        <section class="container section">
            <div class="row bg">
                <div class="grid-2">
                    <figure class="video-card">
                        <div class="video-thumb">
                            <img src="{{ asset('about-hero.png') }}" alt="Video cover">
                        </div>
                        <button class="play-btn" id="playBtn" aria-label="Play about video">
                            <span class="play-dot">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </span>
                        </button>
                        <video id="aboutVideo" preload="metadata" playsinline controls>
                            <source src="{{ asset('about.mp4') }}" type="video/mp4">
                        </video>
                    </figure>

                    <article class="summary">
                        <h2>Executive <span class="pill">Summary</span></h2>
                        <ul class="summary-list">
                            <li class="sum-item">
                                <img src="icons/star.png" alt="" />
                                <p>Pyramakerz Holding advances education & technology with immersive solutions for
                                    learners and organizations.</p>
                            </li>
                            <li class="sum-item"><img src="icons/star.png">
                                <p>The group includes subsidiaries such as Edsimz, Lablab, Labian, Finanalytics, MAL&EM,
                                    and ROBOT4, unifying cutting-edge tech with operational excellence across the MENA
                                    region.</p>
                            </li>
                            <li class="sum-item"><img src="icons/star.png">
                                <p>We lead the sector into a new era of innovation and impact through a holistic,
                                    integrated ecosystem.</p>
                            </li>
                            <li class="sum-item"><img src="icons/star.png">
                                <p>We build tools and experiences that deliver measurable outcomes and shape the future
                                    of learning.</p>
                            </li>
                        </ul>
                    </article>
                </div>
            </div>
        </section>

        <!-- WHO WE ARE + VISION -->
        <section class="container who-section">
            <div class="who-row">
                <!-- 4 cols: WHO WE ARE / tabs -->
                <aside class="who-card">
                    <h3 class="who-title">Who <span class="pill">We Are ?</span></h3>
                    <p class="who-lead">Katalyst Studio offers a range of design services tailored to each client.</p>

                    <nav class="tabs" aria-label="Who we are">
                        <button class="tab active" data-tab="vision"><span class="i">01</span><strong>
                                Vision</strong> <span class="arrow">⟶</span></button>
                        <button class="tab" data-tab="mission"><span class="i">02</span><strong>
                                Mission</strong> <span class="arrow">⟶</span></button>
                        <button class="tab" data-tab="core"><span class="i">03</span><strong> Core
                                Value</strong> <span class="arrow">⟶</span></button>
                    </nav>
                </aside>

                <!-- 6 cols: VISION card -->
                <article class="vision-card" id="panel-vision" role="tabpanel">
                    <h4>Vision</h4>
                    <p>To be the leading education technology and service provider in the MENA region—fostering
                        creativity, critical thinking, and future-readiness through innovative solutions.</p>
                    <div class="media">
                        <img src="about/vision.png" alt="Our vision">
                    </div>
                </article>

                <!-- 2 cols: side CTAs -->
                <aside class="side-ctas">
                    <a class="cta" href="#how-we-work">
                        <div class="cta-title">Ever wondered how design magic happens?</div>
                        <div class="cta-arrow">See how we work →</div>
                    </a>
                    <a class="cta orange" href="#meet-expert">
                        <div class="cta-title">Looking for design experts who can bring your vision to life?</div>
                        <div class="cta-arrow">Meet our expert →</div>
                    </a>
                </aside>
            </div>
        </section>

    </main>

    <!-- FOOTER (simple version from mock) -->
    <footer>
        <div class="container f-grid">
            <div class="f-brand">
                <img src="{{ asset('logo.png') }}" alt="Pyramakerz" style="height:28px">
                <p>Pyramakerz Holding is uniquely positioned to lead the education sector into a new era of innovation
                    and impact.</p>
                <div class="social">
                    <a href="#" aria-label="Facebook">f</a>
                    <a href="#" aria-label="YouTube">▶</a>
                    <a href="#" aria-label="Instagram">◎</a>
                    <a href="#" aria-label="X">x</a>
                </div>
            </div>
            <div class="f-col">
                <h5>Quick Links</h5>
                <ul class="f-list">
                    <li><a href="/">Home</a></li>
                    <li><a href="/school">School</a></li>
                    <li><a href="/teacher">Teacher</a></li>
                    <li><a href="/parent">Parent</a></li>
                </ul>
            </div>
            <div class="f-col">
                <h5>Contact Us</h5>
                <ul class="f-list">
                    <li>012554888545</li>
                    <li>12 kafria tours, Yehia Mosque, Alexandria Egypt</li>
                    <li>Cairo Address</li>
                </ul>
            </div>
            <div class="f-col">
                <h5>Subscribe Now</h5>
                <div class="subscribe">
                    <input type="email" placeholder="Email">
                    <button type="button">Subscribe</button>
                </div>
            </div>
        </div>
        <div class="container legal">
            <div>Privacy Policy · Terms of Conditions · Cookie Policy</div>
            <div>© {{ date('Y') }} Pyramakerz. All rights reserved.</div>
        </div>
    </footer>

    <script>
        // Play video inline
        const playBtn = document.getElementById('playBtn');
        const vid = document.getElementById('aboutVideo');
        if (playBtn && vid) {
            playBtn.addEventListener('click', () => {
                playBtn.style.display = 'none';
                vid.style.display = 'block';
                vid.play().catch(() => {});
            });
        }

        // Tabs
        const tabs = document.querySelectorAll('.tab');
        const panels = {
            vision: document.getElementById('panel-vision'),
            mission: document.getElementById('panel-mission'),
            core: document.getElementById('panel-core')
        };
        tabs.forEach(btn => {
            btn.addEventListener('click', () => {
                tabs.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const key = btn.dataset.tab;
                Object.entries(panels).forEach(([k, el]) => el.hidden = (k !== key));
            });
        });
    </script>


    <script>
        document.querySelectorAll('.tab').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                // If you later add #panel-mission / #panel-core, toggle them here
            });
        });
    </script>


</body>

</html>
