<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="robots" content="nofollow,noindex" />
        <meta name="googlebot" content="noindex, nofollow" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>4Love</title>
        <link href=" {{  asset('front-assets/css/normalize.css') }}" rel="stylesheet">
        <!-- Bootstrap -->
        <link href=" {{  asset('front-assets/css/bootstrap.css') }}" rel="stylesheet">
        <link href=" {{  asset('front-assets/css/fonts.css') }}" rel="stylesheet">
        <link href=" {{  asset('front-assets/css/images.css') }}" rel="stylesheet">
        <link href=" {{  asset('front-assets/css/slick.css') }}" rel="stylesheet">
        <link href=" {{  asset('front-assets/css/slick-theme.css') }}" rel="stylesheet">
        <link href=" {{  asset('front-assets/css/style.css') }}" rel="stylesheet">
        <link href=" {{  asset('front-assets/css/responsive.css') }}" rel="stylesheet">
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="wrapper">
            <div class="header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 logo">
                            <a href="{{ url('/') }}"><img src="{{ asset('front-assets/img/logo.png') }}"></a>
                        </div>
                        <div class="col-md-6 moto">
                            <p>Тебя ибут или ибёшь себя ты…</p>
                            <p class="red"> Если ты тут- значит ты Готов!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu">
                <div class="container">
                    <ul>
                        {{-- <li><a href="categories.html"><img src="{{ asset('front-assets/img/1.png') }}"><img class="img-hover" src="{{ asset('front-assets/img/menu-hover.png') }}">Ибатория или Live</a></li>
                        <li><a href="categories.html"><img src="{{ asset('front-assets/img/2.png') }}"><img class="img-hover" src="{{ asset('front-assets/img/menu-hover.png') }}">БИЗНЕСС ПО-МОЛДАВСКИ</a></li>
                        <li><a href="categories.html"><img src="{{ asset('front-assets/img/3.png') }}"><img class="img-hover" src="{{ asset('front-assets/img/menu-hover.png') }}">Зоопарк людей</a></li>
                        <li><a href="categories.html"><img src="{{ asset('front-assets/img/4.png') }}"><img class="img-hover" src="{{ asset('front-assets/img/menu-hover.png') }}">Хуета повсюду</a></li>
                        <li><a href="categories.html"><img src="{{ asset('front-assets/img/5.png') }}"><img class="img-hover" src="{{ asset('front-assets/img/menu-hover.png') }}">Говно-проекты</a></li>
                        <li><a href="categories.html"><img src="{{ asset('front-assets/img/6.png') }}"><img class="img-hover" src="{{ asset('front-assets/img/menu-hover.png') }}">Интернетомания</a></li>
                        <li><a href="categories.html"><img src="{{ asset('front-assets/img/7.png') }}"><img class="img-hover" src="{{ asset('front-assets/img/menu-hover.png') }}">есть что сказать?</a></li> --}}
                        @if (!empty($categs))
                            @foreach ($categs as $key => $categ)
                                <li><a href="{{ url($lang.'/category/'.$categ->goodsSubjectId->alias) }}"><img src="{{ '/upfiles/categories/'.$categ->img }}"><img class="img-hover" src="{{ asset('front-assets/img/menu-hover.png') }}">{{ $categ->name }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            {{-- content start  --}}
                @yield('content')
            {{-- end of content --}}
            <div class="footer">
                <ul>
                    <li><a href="">Ибатория или Live</a></li>
                    <li><a href="">Бизнесс по-молдавски</a></li>
                    <li><a href="">Зоопарк людей</a></li>
                    <li><a href="">Хуета повсюду</a></li>
                    <li><a href="">Говно-проекты</a></li>
                    <li><a href="">Интернетомания</a></li>
                    <li><a href="">есть что сказать?</a></li>
                </ul>
                <p>2017 © “4Love-ский сайт”</p>
            </div>
            <script src="{{ asset('front-assets/js/jquery-3.2.0.min.js') }}"></script>
            <script src="{{ asset('front-assets/js/bootstrap.min.js') }}"></script>
            <script src="{{ asset('front-assets/js/slick.js') }}"></script>
            <script src="{{ asset('front-assets/js/scripts.js') }}"></script>
            <script>
                $('.testim-slider').slick({
                    arrows: true,
                    autoplay: true,
                    speed: 500,
                    slidesToShow: 3,
                });
            </script>
        </div>
    </body>
</html>
