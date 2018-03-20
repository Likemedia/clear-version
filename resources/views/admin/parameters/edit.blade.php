@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')
@if($groupSubRelations->new == 1)
    @if(Request::segment(5) == '' || Request::segment(4) == 'createMenu')
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, 'options'),
                trans('variables.add_element') => url($lang. '/back/goods/options/create'),
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, 'options'),
                trans('variables.add_element') => url($lang. '/back/goods/options/create'),
            ]
        ])
    @endif

@endif

<div class="list-content">
    
<form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'updateParameter') }}" id="add-form" enctype="multipart/form-data">
    
    <div class="part left-part">    
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="itemId" value="{{ $param->parameter_id }}">
        <ul>
            <li>
                <label for="name">{{trans('variables.title_table')}} параметра</label>
                <input type="text" name="name" id="name" value={{ $param->name }}>
            </li> 
            @if(count($values) > 0)
                @foreach($values as $key => $value)
                     <li>
                        <label for="value-{{ $key + 1 }}">Характеристика {{ $key + 1 }}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="value[]" id="value-{{ $key + 1 }}" value="{{ $value }}">
                            <span class="input-group-addon"><i class="fa fa-trash"></i></span>
                        </div>
                    </li>
                @endforeach
            @endif
            <li>
                <label for="value">Добавить характеристику</label>
                <input type="text" name="value[0]" id="value">
            </li> 

        </ul>
        <p class="text-danger"><small>Чтобы изменеиня вошли в силы необходимо сохранить.</small></p>
        @if($groupSubRelations->save == 1)
            <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
        @endif

    </div>
    <div class="part right-part">
        <ul>
            <li>
                <label for="lang">{{trans('variables.lang')}}</label>
                <select name="lang" id="lang">
                    @foreach($lang_list as $lang_key => $one_lang)
                        <option value="{{$one_lang->id}}" {{$one_lang->id == Request::segment(7) ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                    @endforeach
                </select>
            </li>
            <li>
                <label for="type">Тип</label>
                <select name="type" id="type">
                   <option value="checkbox" {{ $param->parameterId->type == 'chechbox' ? 'checked' : '' }}> Checkbox</option>
                   <option value="radio" {{ $param->parameterId->type == 'radio' ? 'checked' : '' }}> Radio</option>
                </select>
            </li>
        </ul>
    </div>

</form>
</div>

@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop
