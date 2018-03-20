@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')
@if($groupSubRelations->new == 1)
    @if(Request::segment(5) == '' || Request::segment(4) == 'createMenu')
        @include('list-elements', [
            'actions' => [
                'Категории товоров' => url($lang.'/back/goods'),
                'Все товары' => url($lang. '/back/goods/googs/all'),
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                'Категории товоров' => url($lang.'/back/goods'),
                'Все товары' => url($lang. '/back/goods/googs/all'),
            ]
        ])
    @endif

@endif

<h4 class="title"><i class="fa fa-thumb-tack"></i> {{ $param->name }}</h4>

<div class="list-content">
<form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'updateDiscount') }}" id="add-form" enctype="multipart/form-data">
    
    <div class="part left-part">    
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="itemId" value="{{ $param->parameter_id }}">
        <ul>
            @if(is_array($values))
                @foreach($values as $key => $value)
                     <li>
                        <label for="value-{{ $key + 1 }}">Скидка - {{ $key + 1 }} (в процентах)</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="value[]" id="value-{{ $key + 1 }}" value="{{ $value }}">
                            <span class="input-group-addon"><i class="fa fa-trash"></i></span>
                        </div>
                    </li>
                @endforeach
            @endif
            <li>
                <label for="value">Добавить Скидку (%)</label>
                <input type="number" name="value[]" id="value">
            </li>                   
        </ul>
        <p class="text-danger"><small>Чтобы изменеиня вошли в силы необходимо сохранить.</small></p>
        @if($groupSubRelations->save == 1)
            <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
        @endif

    </div>
</form>
</div>

@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop
