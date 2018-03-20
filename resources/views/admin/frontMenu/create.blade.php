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
    
<form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'save') }}" id="add-form" enctype="multipart/form-data">
    
    <div class="part left-part">    
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="p_id" value="0">

        <ul>
           
            <li>
                <label for="name">{{trans('variables.title_table')}}</label>
                <input type="text" name="name" id="name">
            </li> 
            <li>
                <label for="link">Ссылка</label>
                <input type="text" name="link" id="link">
            </li>
            <li>
                <label for="target">Открывать</label>
                <select name="target" id="target">
                    <option value="_self">В текущем окне</option>
                    <option value="_blank">В новом окне</option>
                </select>
            </li>          
        </ul>

    </div>

    <div class="part right-part">
        <ul>
             <li>
                <label for="lang">{{trans('variables.lang')}}</label>
                <select name="lang" id="lang">
                    @foreach($lang_list as $lang_key => $one_lang)
                        <option value="{{$one_lang->id}}" {{$one_lang->id == $lang_id ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                    @endforeach
                </select>
            </li>
             @if($groupSubRelations->save == 1)
                <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
            @endif
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
