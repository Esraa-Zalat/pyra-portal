<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kalam:wght@300;400;700&display=swap" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
    <title>School Category | Pyramakerz</title>







</head>

<body>

    @include('includes.nav')

    <!-- HERO -->
    <header class="hero">
        <img src="{{ asset('teacher/teacher-hero.png') }}" alt="Pyramakerz — About hero">
    </header>

    <!-- INTRO -->
    <section class="container intro">
        <div class="intro-grid reveal">
            <div>
                <div class="eyebrow">Welcome to teacher Category</div>
                <h1 class="h1">Teacher Development & <br />Innovation Hub</h1>
            </div>
            <img class="mascot" src="about/alefbot.png" alt="Pyramakerz mascot">
        </div>
    </section>

    <!-- PROGRAM CARDS -->
    <section class="container cards">
        <div class="grid">
            <!-- 1 -->
            <article class="card">
                <div class="card-head">
                    <span class="icon"><img src="icons/steam.png" alt="" width="20"></span>

                </div>
                <h3>STEAM</h3>
                <p>Training in science, tech, engineering, arts & math</p>
                <div class="col-6">
                    <button class="btn">Explore Now <svg class="arrow" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M5 12h14" stroke-linecap="round" />
                            <path d="M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg></button>
                </div>
            </article>

            <!-- 2 -->
            <article class="card">
                <div class="card-head">
                    <span class="icon"><img src="icons/mon.png" alt="" width="20"></span>

                </div>
                <h3>Career Development</h3>
                <p>Advancing professional skills and growth opportunities</p>
                <div class="col-6">
                    <button class="btn">Explore Now <svg class="arrow" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M5 12h14" stroke-linecap="round" />
                            <path d="M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg></button>
                </div>
            </article>

            <!-- 3 -->
            <article class="card">
                <div class="card-head">
                    <span class="icon"><img src="icons/shop.png" alt="" width="20"></span>

                </div>
                <h3>Shop</h3>
                <p> Ready-to-use educational kits for projects</p>
                <div class="col-6">
                    <button class="btn">Explore Now <svg class="arrow" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M5 12h14" stroke-linecap="round" />
                            <path d="M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg></button>
                </div>
            </article>


        </div>
    </section>

    @include('includes.footer')
    <!-- Animations JS -->
    <script>
        // Staggered reveal for cards + any .reveal elements
        (function() {
            const items = [...document.querySelectorAll('.card'), ...document.querySelectorAll('.reveal')];

            // Add increasing delays to cards only
            document.querySelectorAll('.grid .card').forEach((el, i) => {
                el.style.transitionDelay = (i * 90) + 'ms';
            });

            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('is-visible');
                        obs.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.18
            });

            items.forEach(el => obs.observe(el));
        })();

        // Button ripple highlight follows cursor
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('pointermove', e => {
                const r = btn.getBoundingClientRect();
                btn.style.setProperty('--x', (e.clientX - r.left) + 'px');
                btn.style.setProperty('--y', (e.clientY - r.top) + 'px');
            });
        });
    </script>
</body>

</html>
