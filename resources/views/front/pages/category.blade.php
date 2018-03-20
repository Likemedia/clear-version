@extends('front.index')

@section('content')

    <div class="content1">
    <div class="container-fluid">
        <div class="col-md-12 breadcrumbs">
            <a href="#"><img src="{{ asset('front-assets/img/home-icon.png') }}"></a>
            <a href="#"> <span></span> {{ $categ->name }}</a>
        </div>
        <div class="col-md-9">
            <h1>{{ $categ->name }}</h1>
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

            {{-- <div class="col-md-4 item">
                <div class="inside">
                    <a href="single.html">
                        <img src="{{ asset('front-assets/img/blog1.png') }}">
                        <div class="datas">
                            <span> <img src="{{ asset('front-assets/img/date-icon.png') }}"> 21.10.2017 </span>
                            <span> <img src="{{ asset('front-assets/img/view-icon.png') }}"> 36 232 </span>
                        </div>
                        <p>В зону комфорта вошел - нахуй пошел. Три поучительных истории трех крахов крупных бизнесов</p>
                    </a>
                </div>
            </div>
            <div class="col-md-4 item">
                <div class="inside">
                    <a href="#">
                        <img src="{{ asset('front-assets/img/blog2.png') }}">
                        <div class="datas">
                            <span> <img src="{{ asset('front-assets/img/date-icon.png') }}"> 21.10.2017 </span>
                            <span> <img src="{{ asset('front-assets/img/view-icon.png') }}"> 36 232 </span>
                        </div>
                        <p>Пользователи срали, плевали и хуй клали на ваши красивые сайты</p>
                    </a>
                </div>
            </div>
            <div class="col-md-4 item item-3">
                <div class="inside">
                    <a href="#">
                        <img src="{{ asset('front-assets/img/blog2.png') }}">
                        <div class="datas">
                            <span> <img src="{{ asset('front-assets/img/date-icon.png') }}"> 21.10.2017 </span>
                            <span> <img src="{{ asset('front-assets/img/view-icon.png') }}"> 36 232 </span>
                        </div>
                        <p>В зону комфорта вошел - нахуй пошел. Три поучительных истории трех крахов крупных бизнесов</p>
                    </a>
                </div>
            </div>
            <div class="col-md-4 item">
                <div class="inside">
                    <a href="#">
                        <img src="{{ asset('front-assets/img/blog1.png') }}">
                        <div class="datas">
                            <span> <img src="{{ asset('front-assets/img/date-icon.png') }}"> 21.10.2017 </span>
                            <span> <img src="{{ asset('front-assets/img/view-icon.png') }}"> 36 232 </span>
                        </div>
                        <p>В зону комфорта вошел - нахуй пошел. Три поучительных истории трех крахов крупных бизнесов</p>
                    </a>
                </div>
            </div>
            <div class="col-md-4 item item-5">
                <div class="inside">
                    <a href="#">
                        <img src="{{ asset('front-assets/img/blog2.png') }}">
                        <div class="datas">
                            <span> <img src="{{ asset('front-assets/img/date-icon.png') }}"> 21.10.2017 </span>
                            <span> <img src="{{ asset('front-assets/img/view-icon.png') }}"> 36 232 </span>
                        </div>
                        <p>В зону комфорта вошел - нахуй пошел. Три поучительных истории трех крахов крупных бизнесов</p>
                    </a>
                </div>
            </div>
            <div class="col-md-4 item item-6">
                <div class="inside">
                    <a href="#">
                        <img src="{{ asset('front-assets/img/blog3.png') }}">
                        <p>Кто сказал, что здесь должен быть ваш пост</p>
                    </a>
                </div>
            </div> --}}
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
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    {{-- <li>
                        <a href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li><a href="#" class="active">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li>
                        <a href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li> --}}
                </ul>
            </nav>
        </div>
    </div>
</div>

@stop
