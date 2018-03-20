@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')
@include('speedbar')
@include('list-elements', [
'actions' => [
trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
trans('variables.add_element') => urlForFunctionLanguage($lang, 'item/create'),
]
])
<div class="list-content">
<div class="tab-area">
    @include('alerts')
    <ul class="nav nav-tabs nav-tabs-bordered">
        @if (!empty($langs))
        @foreach ($langs as $key => $tabLang)
        <li class="nav-item">
            <a href="#{{ $tabLang->lang }}" class="nav-link  {{ $key == 0 ? ' open active' : '' }}" data-target="#{{ $tabLang->lang }}">{{ $tabLang->descr }}</a>
        </li>
        @endforeach
        @endif
    </ul>
</div>
<form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$item['ro']->itemId->id.'/'.$edited_lang_id) }}" id="add-form" enctype="multipart/form-data">
    <div class="part part-min">
        <ul>
            <li>
                <label for="short-code">Short Code</label>
                <input type="text" name="short_code" class="slug" id="short_code" value="{{  !is_null(old('short_cut')) ? old('short_cut') : $item['ro']->itemId->short_code}}">
            </li>
        </ul>
    </div>
    @if (!empty($langs))
    @foreach ($langs as $key => $tabLang)
    <div class="tab-content {{ $key == 0 ? ' active-content' : '' }}" id={{ $tabLang->
        lang }}>
        <div class="part left-part">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                <li>
                    <label for="name-{{ $tabLang->lang }}">{{trans('variables.title_table')}}</label>
                    <input type="text" name="title[{{ $tabLang->lang }}]" class="name" id="title-{{ $tabLang->lang }}" data-lang="{{ $tabLang->lang }}" value="{{!is_null(old('title.'.$tabLang->lang)) ? old('title.'.$tabLang->lang) : $item[$tabLang->lang]->title}}">
                </li>
                <li class="ckeditor">
                    <label for="body-{{ $tabLang->lang }}">{{trans('variables.body')}}</label>
                    <textarea name="body[{{ $tabLang->lang }}]" id="body-{{ $tabLang->lang }}" data-type="ckeditor">{{ !is_null(old('body.'.$tabLang->lang)) ? old('body.'.$tabLang->lang) : $item[$tabLang->lang]->content}}</textarea>
                    <script>
                        CKEDITOR.replace( 'body-{{ $tabLang->lang }}', {
                            language: '{{$lang}}',
                        } );
                    </script>
                </li>
                <input type="hidden" name="lang[{{ $tabLang->lang }}]" value="{{ $tabLang->id }}">
                @if($groupSubRelations->save == 1)
                <input type="submit" value="{{trans('variables.save_it')}}" data-form-id="add-form">
                @endif
            </ul>
        </div>
    </div>
    @endforeach
    @endif
</form>
@stop
@section('footer')
<footer>
    @include('footer')
</footer>
@stop
