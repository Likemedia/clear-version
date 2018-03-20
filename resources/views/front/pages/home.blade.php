@extends('front.index')

@section('content')
<div class="content1">
    <div class="container-fluid">
        <div class="col-md-9">
            <h1>свежее</h1>

            <?php $counter = 0; ?>
            @if (!empty($posts))
                @foreach ($posts as $key => $post)

                    @if (++$counter % 3 === 0)
                        <div class="col-md-4 item item-3">
                            <div class="inside">
                                <a href="{{ url($lang.'/single/'.$post->goodsItemId->alias) }}">
                                    <img src="{{ asset('front-assets/img/blog2.png') }}">
                                    <div class="datas">
                                        <span> <img src="{{ asset('front-assets/img/date-icon.png') }}">&nbsp; &nbsp;  21.10.2017 </span>
                                        <span> <img src="{{ asset('front-assets/img/view-icon.png') }}">&nbsp; &nbsp; &nbsp; &nbsp; 36 232 </span>
                                    </div>
                                    <p>{{ $post->name }}</p>
                                </a>
                            </div>
                        </div>
                    {{-- @elseif (++$counter  % 6 === 0) --}}
                        {{-- <div class="col-md-4 item item-6">
                            <div class="inside">
                                <a href="#">
                                    <img src="{{ asset('front-assets/img/blog3.png') }}">
                                    <p>Кто сказал, что здесь должен быть ваш пост</p>
                                </a>
                            </div>
                        </div> --}}
                    @else
                        <div class="col-md-4 item">
                            <div class="inside">
                                <a href="{{ url($lang.'/single/'.$post->goodsItemId->alias) }}">
                                    <img src="{{ asset('front-assets/img/blog1.png') }}">
                                    <div class="datas">
                                        <span> <img src="{{ asset('front-assets/img/date-icon.png') }}">&nbsp; &nbsp;  21.10.2017 </span>
                                        <span> <img src="{{ asset('front-assets/img/view-icon.png') }}">&nbsp; &nbsp; &nbsp; &nbsp; 36 232 </span>
                                    </div>
                                    <p>{{ $post->name }}</p>
                                </a>
                            </div>
                        </div>
                    @endif

                @endforeach
            @endif

        </div>
        <div class="col-md-3 sidebar">
            <div class="item">
                <img src="{{ asset('front-assets/img/eblan.png') }}">
                <a href="daiSebeShans.html"></a>
            </div>
            <div class="item item-2">
                <img src="{{ asset('front-assets/img/cric.png') }}">
                <a href="/ru/contact"></a>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button class="more">Загрузить еще</button>
        </div>
    </div>
</div>
<div class="content-2">
    <div class="container">
        <h2>самое обсуждаемое</h2>
        <div class="testim-slider">
            <div class="item item-1">
                <div class="inside">
                    <a href="#">
                        <h3>Страхи крупных брендов, что их обосрут в интернете, что они заебут своей рекламой и пр. туфтатеперь!</h3>
                        <p><span>Интернетомания</span></p>
                        <p>Почему в России два мира и какие ценности способствуют бедности — новая книга </p>
                        <img src="{{ asset('front-assets/img/testim1.png') }}">
                    </a>
                </div>
            </div>
            <div class="item item-2">
                <div class="inside">
                    <a href="#">
                        <h3>Страхи крупных брендов, что их обосрут в интернете, что они заебут своей рекламой и пр. туфтатеперь!</h3>
                        <p><span>Интернетомания</span></p>
                        <p>Почему в России два мира и какие ценности способствуют бедности — новая книга </p>
                        <img src="{{ asset('front-assets/img/testim2.png') }}">
                    </a>
                </div>
            </div>
            <div class="item item-1">
                <div class="inside">
                    <a href="#">
                        <img src="{{ asset('front-assets/img/testim3.png') }}">
                        <h3>Страхи крупных брендов, что их обосрут в интернете, что они заебут своей рекламой и пр. туфтатеперь!</h3>
                        <p><span>Интернетомания</span></p>
                        <p>Почему в России два мира и какие ценности способствуют бедности — новая книга </p>
                    </a>
                </div>
            </div>
            <div class="item item-1">
                <div class="inside">
                    <a href="#">
                        <h3>Страхи крупных брендов, что их обосрут в интернете, что они заебут своей рекламой и пр. туфтатеперь!</h3>
                        <p><span>Интернетомания</span></p>
                        <p>Почему в России два мира и какие ценности способствуют бедности — новая книга </p>
                        <img src="{{ asset('front-assets/img/testim1.png') }}">
                    </a>
                </div>
            </div>
            <div class="item item-2">
                <div class="inside">
                    <a href="#">
                        <h3>Страхи крупных брендов, что их обосрут в интернете, что они заебут своей рекламой и пр. туфтатеперь!</h3>
                        <p><span>Интернетомания</span></p>
                        <p>Почему в России два мира и какие ценности способствуют бедности — новая книга </p>
                        <img src="{{ asset('front-assets/img/testim2.png') }}">
                    </a>
                </div>
            </div>
            <div class="item item-1">
                <div class="inside">
                    <a href="#">
                        <img src="{{ asset('front-assets/img/testim3.png') }}">
                        <h3>Страхи крупных брендов, что их обосрут в интернете, что они заебут своей рекламой и пр. туфтатеперь!</h3>
                        <p><span>Интернетомания</span></p>
                        <p>Почему в России два мира и какие ценности способствуют бедности — новая книга </p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2>Истории</h2>
            </div>
            <div class="col-md-3 item">
                <div class="inside">
                    <a href="#">
                        <h3>Как я 1 год 6 месяцев, 27 дней и 5 часов работаю на себя! Ну пиздец теперь!</h3>
                        <p><span>хуета повсюду</span></p>
                        <p> Почему в России два мира и какие ценности способствуют бедности — новая книга финансиста Елены Котовой «Откуда берутся деньги, Карл?»</p>
                    </a>
                </div>
            </div>
            <div class="col-md-3 item item-2">
                <div class="inside">
                    <a href="#">
                        <h3>Как я 1 год 6 месяцев, 27 дней и 5 часов работаю на себя! Ну пиздец теперь!</h3>
                        <p><span>хуета повсюду</span></p>
                        <p> Почему в России два мира и какие ценности способствуют бедности — новая книга финансиста Елены Котовой «Откуда берутся деньги, Карл?»</p>
                    </a>
                </div>
            </div>
            <div class="col-md-3 item item-3">
                <div class="inside">
                    <a href="#">
                        <h3>Как я 1 год 6 месяцев, 27 дней и 5 часов работаю на себя! Ну пиздец теперь!</h3>
                        <p><span>хуета повсюду</span></p>
                        <p> Почему в России два мира и какие ценности способствуют бедности — новая книга финансиста Елены Котовой «Откуда берутся деньги, Карл?»</p>
                    </a>
                </div>
            </div>
            <div class="col-md-3 item item-4">
                <div class="inside">
                    <a href="#">
                        <h3>Как я 1 год 6 месяцев, 27 дней и 5 часов работаю на себя! Ну пиздец теперь!</h3>
                        <p><span>хуета повсюду</span></p>
                        <p> Почему в России два мира и какие ценности способствуют бедности — новая книга финансиста Елены Котовой «Откуда берутся деньги, Карл?»</p>
                    </a>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button class="more">Загрузить еще</button>
            </div>
        </div>
    </div>
</div>
@stop
