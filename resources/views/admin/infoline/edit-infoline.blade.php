@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.add_element') => urlForFunctionLanguage($lang, 'createInfoLine/createinfoline'),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'infoLineCart/infolinecart'),
            trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($info_line_without_lang->name).'/editinfoline/'.$info_line_without_lang->id.'/'.$edited_lang_id)
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'infoLineCart/infolinecart'),
            trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($info_line_without_lang->name).'/editinfoline/'.$info_line_without_lang->id.'/'.$edited_lang_id)
        ]
    ])
@endif

<div class="list-content">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'saveinfoline/'.$info_line_without_lang->id.'/'.$edited_lang_id) }}" id="add-form" enctype="multipart/form-data">

        <div class="part left-part">
             <h4 class="title-module"><i class="fa fa-arrows-h"></i> Новостная лента</h4>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                
                <li>
                    <label for="name">{{trans('variables.title_table')}}</label>
                    <input type="text" name="name" id="name" value="{{$info_line->name or ''}}">
                </li>
                <li>
                    <label for="descr">{{trans('variables.description')}}</label>
                    <textarea name="descr" id="descr">{{$info_line->descr or ''}}</textarea>
                </li>
                <li>
                    <label for="img">{{trans('variables.img')}}</label>
                    <div class='file-div'>
                        <button class='btn btn-sm'>
                            <span class='glyphicon glyphicon-refresh-animate'>{{trans('variables.select_file')}}</span>
                        </button>
                        @if(!is_null($info_line_id->image))
                            <input type="hidden" name="file" data-url="{{url($lang, ['back', 'upload'])}}" path="{{$modules_name->src}}" value="{{$info_line_id->image}}" />
                            <img src="{{$info_line_id->image}}">
                        @else
                            <input type="hidden" name="file" data-url="{{url($lang, ['back', 'upload'])}}" path="{{$modules_name->src}}" />
                        @endif
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
                            <option value="{{$one_lang->id}}" {{$one_lang->id == $edited_lang_id ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                        @endforeach
                    </select>
                </li>
                <li>
                    <label for="alias">{{trans('variables.alias_table')}}</label>
                    <input type="text" name="alias" id="alias" value="{{$info_line_id->alias or ''}}">
                </li>
                @if($groupSubRelations->save == 1)
                    <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
                @endif
            </ul>

            <ul>
                <hr><h6>Seo тексты</h6>
                <li>
                    <label for="h1_title">{{trans('variables.h1_title_page')}}</label>
                    <input type="text" name="h1_title" id="h1_title" autocomplete="off" value="{{$info_line_without_lang->h1_title or ''}}">
                </li>
                <li>
                    <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                    <input type="text" name="meta_title" id="meta_title" autocomplete="off" value="{{$info_line_without_lang->meta_title or ''}}">
                </li>
                <li>
                    <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" autocomplete="off" value="{{$info_line_without_lang->meta_keywords or ''}}">
                </li>
                <li>
                    <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                    <input type="text" name="meta_description" id="meta_description" autocomplete="off" value="{{$info_line_without_lang->meta_description or ''}}">
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
