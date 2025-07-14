<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--========== SWIPER CSS ==========-->
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">

    <!--========== CUSTOM CSS ==========-->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">

    <title>Survey Welcome Page</title>
</head>
<body>
    <!--========== HEADER ==========-->
    <header class="header">
        <nav class="nav bd-container">
            <a href="#" class="nav__logo">Surveys</a>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <a href="{{ url('/login') }}" class="nav__link">Login</a>
                </ul>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-menu'></i>
            </div>
        </nav>
    </header>

    <!--========== MAIN ==========-->
    <main class='main'>
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">
                <!--========== ISLANDS 1 ==========-->
                <section class="islands swiper-slide">
                    <img src="{{ asset('assets/img/telkom_gedung_3.jpg') }}" alt="" class="islands__bg">

                    <div class="islands__container bd-container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle">Surveys</h2>
                            <h1 class="islands__title">Welcome</h1>
                            <p class="islands__description">Terimakasih telah meluangkan waktu anda untuk melakukan survey ini.</p>
                        </div>

                        <div class="islands__video">
                            <div class="islands__video-content">
                                <i class='bx bx-play-circle islands__video-icon'></i>
                            </div>
                        </div>
                    </div>
                </section>

                <!--========== ISLANDS 2 ==========-->
                <section class="islands swiper-slide">
                    <img src="{{ asset('assets/img/telkom_gedung_4.jpg') }}" alt="" class="islands__bg">

                    <div class="islands__container bd-container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle">Surveys</h2>
                            <h1 class="islands__title">Telkomsel</h1>
                            <p class="islands__description">Terimakasih telah meluangkan waktu anda untuk melakukan survey ini.</p>
                            <a href="{{ url('/telkomsel') }}" class="islands__button">
                                Go to surveys <i class='bx bx-right-arrow-alt islands__button-icon'></i>
                            </a>
                        </div>

                        <div class="islands__video">
                            <div class="islands__video-content">
                                <i class='bx bx-play-circle islands__video-icon'></i>
                            </div>
                        </div>
                    </div>
                </section>

                <!--========== ISLANDS 3 ==========-->
                <section class="islands swiper-slide">
                    <img src="{{ asset('assets/img/telkom_gedung_13.jpg') }}" alt="" class="islands__bg">

                    <div class="islands__container bd-container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle">Surveys</h2>
                            <h1 class="islands__title">Indihome</h1>
                            <p class="islands__description">Terimakasih telah meluangkan waktu anda untuk melakukan survey ini.</p>
                            <a href="{{ url('/indihome') }}" class="islands__button">
                                Go to surveys <i class='bx bx-right-arrow-alt islands__button-icon'></i>
                            </a>
                        </div>

                        <div class="islands__video">
                            <div class="islands__video-content">
                                <i class='bx bx-play-circle islands__video-icon'></i>
                            </div>
                        </div>
                    </div>
                </section>

                <!--========== ISLANDS POPUP ==========-->
                <div class="islands__popup" id="popup">
                    <div>
                        <div class="islands__popup-close" id="popup-close">
                            <i class='bx bx-x'></i>
                        </div>

                        <iframe class="islands__popup-video"
                                src="https://www.youtube.com/embed/JrU6bsuNU7Y"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!--========== CONTROLS ==========-->
        <div class="controls gallery-thumbs">
            <div class="controls__container swiper-wrapper">
                <img src="{{ asset('assets/img/logo_telkom_2.png') }}" alt="" class="controls__img swiper-slide">
                <img src="{{ asset('assets/img/telkomsel_logo.png') }}" alt="" class="controls__img swiper-slide">
                <img src="{{ asset('assets/img/indihome_logo.png') }}" alt="" class="controls__img swiper-slide">
            </div>
        </div>
    </main>

    <!--========== GSAP ==========-->
    <script src="{{ asset('assets/js/gsap.min.js') }}"></script>

    <!--========== SWIPER JS ==========-->
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>

    <!--========== MAIN JS ==========-->
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
