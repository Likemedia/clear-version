@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
            trans('variables.add_element') => urlForLanguage($lang, 'createinfoitem'),
            trans('variables.elements_basket') => urlForLanguage($lang, 'infoitemscart')
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
            trans('variables.elements_basket') => urlForLanguage($lang, 'infoitemscart')
        ]
    ])
@endif

<a class="black" href="{{ url($lang.'/back/info_line/'.Request::segment(4).'/editinfoline/'.getInfoLineId(Request::segment(4), $lang_id).'/'.$lang_id) }}">
    <h4 class="title"><small> <i class="fa fa-thumb-tack"></i> {{ getInfoLineName(Request::segment(4), $lang_id) }}</small></h4>
</a>


<div class="list-content">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'saveinfoitem') }}" id="add-form" enctype="multipart/form-data">

        <div class="part left-part">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                <li>
                    <label for="name">{{trans('variables.title_table')}}</label>
                    <input type="text" name="name" id="name">
                </li>
                <li>
                    <label for="descr">{{trans('variables.short_description')}}</label>
                    <textarea id="descr" name="descr" cols="92" rows="15"></textarea>
                </li>
                <li>
                    <label for="body">{{trans('variables.description')}}</label>
                    <textarea name="body" id="body" data-type="ckeditor"></textarea>
                    <script>
                        CKEDITOR.replace( 'body', {
                            language: '{{$lang}}',
                        } );
                    </script>
                </li>
                <li>
                    <label for="author">{{trans('variables.author')}}</label>
                    <input type="text" name="author" id="author">
                </li>
                <li>
                    <label for="tag1">Tag 1</label>
                    <input type="text" name="tag1" id="tag1">
                </li>
                <li>
                    <label for="tag2">Tag 2</label>
                    <input type="text" name="tag2" id="tag2">
                </li>
                <li>
                    <label for="tag3">Tag 3</label>
                    <input type="text" name="tag3" id="tag3">
                </li>
                <li>
                    <label for="tag4">Tag 4</label>
                    <input type="text" name="tag4" id="tag4">
                </li>
                <li>
                    <label for="tag5">Tag 5</label>
                    <input type="text" name="tag5" id="tag5">
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
                <li>
                    <label for="datepicker">{{trans('variables.date_table')}}</label>
                    <input type="text" id="datepicker" name="add_date" lang="{{$lang}}">
                </li>
                <li>
                    <label for="is_public">
                    <input type="checkbox" class="radio squared" id="is_public" name="is_public">
                    <span>{{trans('variables.published_table')}}</span>
                    </label>
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
                <hr><h6>Допольнительно</h6>
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
