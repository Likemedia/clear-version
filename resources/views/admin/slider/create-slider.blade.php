@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.add_element') => urlForFunctionLanguage($lang, 'createBanner/createitem'),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems')
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems')
        ]
    ])
@endif

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ urlForLanguage($lang, 'save') }}" id="add-form" enctype="multipart/form-data">
        
    <div class="part left-part">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <ul>
            <li>
                <label for="name">{{trans('variables.title_table')}}</label>
                <input type="text" name="name" id="name">
            </li>
            <li>
                <label for="title_h1">Заголовок H1</label>
                <input type="text" name="title_h1" id="title_h1">
            </li>
            <li>
                <label for="title_h2">Заголовок H2</label>
                <input type="text" name="title_h2" id="title_h2">
            </li>
            <li>
                <label for="img">{{trans('variables.img')}}</label>
                <div class='file-div'>
                    <button class='btn btn-sm'>
                        <span class='glyphicon glyphicon-refresh-animate'>{{trans('variables.select_file')}}</span>
                    </button>
                    <input type="hidden" name="file" data-url="{{url($lang, ['back', 'upload'])}}" path="{{$modules_name->src}}" />
                </div>
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
                <li>
                    <label for="link">{{trans('variables.link')}}</label>
                    <input type="text" name="link" id="link" autocomplete="off">
                </li>
                 <li>
                    <label for="active">
                        <input class="checkbox square" type="checkbox" name="active" id="active">
                        <span>{{trans('variables.active_table')}}</span>
                    </label>
                </li>
                @if($groupSubRelations->save == 1)
                    <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
                @endif
            </ul>

            <ul>
                <hr><h6>Дополнительно</h6>
                <li>
                    <label for="alt">Alt</label>
                    <input type="text" name="alt" id="alt">
                </li>
                <li>
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title">
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
