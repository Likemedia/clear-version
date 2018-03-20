@extends('front.index')
@section('content')

<div class="content1">
    <div class="container-fluid">

    <div class="col-md-12 breadcrumbs">
    <a href="#"><img src="{{ asset('front-assets/img/home-icon.png') }}"></a>
    <a href="#" class="active"> <span></span>   Есть что сказать?</a>
    </div>

    <div class="col-md-9 single">
      <h1>Отправь мне что-нибудь о тебе <span>и я расскажу какое ты говно</span></h1>


      <div class="contact-form">
        <h3>Бесплатный аудит!</h3>

        <form>
          <div class="row">
            <div class="col-md-6">
              <input type="text" name="name" placeholder="Как тебя зовут?">
              <input type="text" name="name" placeholder="Твоя почта">
              <input type="text" name="name" placeholder="О чем желаешь поговорить?">
              <input type="submit" value="">
            </div>
            <div class="col-md-6">
                <label>Сообщение...</label>
                <textarea></textarea>
            </div>
          </div>
        </form>
      </div>
      <h5>или свяжись со мной через: <a href="" class="facebook"></a></h5>

    </div>
    <div class="col-md-3 sidebar">
        <div class="item">
            <img src="{{ asset('front-assets/img/eblan.png') }}">
            <a href="daiSebeShans.html"></a>
        </div>
        <div class="item item-2">
            <img src="{{ asset('front-assets/img/cric.png') }}">
            <a href="/contact.html"></a>
        </div>
    </div>

    </div>

</div>

@stop
