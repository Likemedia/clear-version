@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')
@if($groupSubRelations->new == 1)
    @if(Request::segment(5) == '' || Request::segment(4) == 'createMenu')
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                trans('variables.add_element') => urlForFunctionLanguage($lang, 'createMenu/createmenu'),
                trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'menuCart/menucart')
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.add_element') => urlForLanguage($lang, 'createmenu'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'menucart')
            ]
        ])
    @endif
@else
    @if(Request::segment(5) == '' || Request::segment(4) == 'createMenu')
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'menuCart/menucart')
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'menucart')
            ]
        ])
    @endif
@endif

<div class="list-content">
    
<form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'savemenu') }}" id="add-form" enctype="multipart/form-data">
    
    <div class="part left-part">    
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="p_id" value="0">

        <ul>
            <li>
                <label for="name">{{trans('variables.title_table')}}</label>
                <input type="text" name="name" id="name">
            </li>                    
            <li class="ckeditor">
               <label for="body">{{trans('variables.description')}}</label>
                <textarea name="body" id="body" data-type="ckeditor"></textarea>
                <script>
                    CKEDITOR.replace( 'body', {
                        language: '{{$lang}}',
                    } );
                </script>
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
                <label for="alias">{{trans('variables.alias_table')}}</label>
                <input type="text" name="alias" id="alias">
            </li>
            @if($groupSubRelations->save == 1)
                <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
            @endif
        </ul>

        <ul>
            <hr><h6>Seo тексты</h6>
            <li>
                <label for="h1_title">{{trans('variables.h1_title_page')}}</label>
                <input type="text" name="h1_title" id="h1_title" autocomplete="off">
            </li>
            <li>
                <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                <input type="text" name="meta_title" id="meta_title" autocomplete="off">
            </li>
            <li>
                <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                <input type="text" name="meta_keywords" id="meta_keywords" autocomplete="off">
            </li>
            <li>
                <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                <input type="text" name="meta_description" id="meta_description" autocomplete="off">
            </li>
        </ul>
            
        <ul>
            <hr><h6>Дополнительно</h6>
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
   
</form>
</div>

@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop
