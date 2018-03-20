@extends('front.index')
@section('content')

<div class="content2-2">
    <div class="container-fluid">
    <div class="col-md-12 breadcrumbs">
        <a href="#"><img src="{{ asset('front-assets/img/home-icon.png') }}"></a>
        <a href="#"> <span></span>  БИЗНЕСС ПО-МОЛДАВСКИ</a>
        <a href="#" class="active"> <span></span>{{ $post->name }}</a>
    </div>
{{-- </div> --}}
<div class="col-md-9 single">
    <h1>{{ $post->name }}</h1>
    <div class="item">
        <div class="datas">
            <span> <img src="{{ asset('front-assets/img/date-icon.png') }}"> {{ $post->created_at->format('d.m.Y') }}</span>
            <span> <img src="{{ asset('front-assets/img/view-icon.png') }}"> 36 232 </span>
        </div>
    </div>
    <div class="content">
        {!! $post->body !!}
        {{-- <img src="{{ asset('front-assets/img/single-img.png') }}">
        <p>Так уж устроен мир, что стоит высунуться, открыть рот, дать о себе знать — и ты тут же получишь комок говна в рожу. Не потому, что ты плохой или делаешь что-то не так — это вообще ни на что не влияет. </p>
        <h3>Страх первый: мы всех заебали своей рекламой, нашей рекламы слишком много!</h3>
        <p>Не раз, и не два сталкивалась со следующей ситуацией. Приходит клиент с нормальным бюджетом, согласовывает план на несколько десятков публикаций, и где-то на середине прибегает со страхом:</p>
        <p>— Там в комментариях пишут ПЛОХОЕ! Пишут, что мы заебали своей рекламой! Давайте пореже показывать?</p>
        <p>Блядь! Вот наводящий на верную мысль вопрос: от Парижа до Находки какие лучше колготки?</p>
        <p>Можете не отвечать. Я верю, что вы знаете ответ — вам его въебошили в голову многочисленным повторением из разных источников на протяжении долгого времени, выжгли на подкорке. Раздражение со временем прошло, а вот бренд запомнился. Вы вот, мужики, назовете навскидку хоть какие-нибудь колготки, кроме Омсы?</p>
        <p>Если начинают писать, что «вы заебали рекламой», значит реклама реально достала до печёнок и таки да, мы движемся в верном направлении. Надо не тормозить, а наоборот усиливаться, работать на закрепление. Чтобы больше писали про то, что заебало.</p>
        <h4>Страх второй, самый обсерический: нам написал Иван Петрович Творожок, что он у нас ничего после такой рекламы не купит</h4>
        <blockquote>
            Кампания в разгаре, размещения выходят, трафик льётся, деревья поют, птички колышутся — короче, ничего не предвещает беды. И вдруг с ноги открывается дверь, и вваливается траурная процессия от клиента. Мужики в черном, бабы в соплях. Руководитель отдела маркетинга садится в гробу и скорбным шёпотом хрипит:
        </blockquote>
        <img src="{{ asset('front-assets/img/single-img2.png') }}">
        <p>Кампания в разгаре, размещения выходят, трафик льётся, деревья поют, птички колышутся — короче, ничего не предвещает беды. И вдруг с ноги открывается дверь, и вваливается траурная процессия от клиента. Мужики в черном, бабы в соплях. Руководитель отдела маркетинга садится в гробу и скорбным шёпотом хрипит:</p> --}}
    </div>
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
<div class="comments">
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.12';
        fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <div class="fb-comments" data-href="https://developers.facebook.com/docs/plugins/comments#configurator" data-numposts="5"></div>
</div>
</div>
<div class="bottom-line row">
    <div class="col-md-6 text-left">
        <img src="{{ asset('front-assets/img/cow.png') }}" alt="">
    </div>
    <div class="col-md-6 text-right">
        <img src="{{ asset('front-assets/img/cactus.png') }}" alt="">
    </div>
</div>
</div>
@stop
