@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @if(Request::segment(5) == '' || Request::segment(4) == 'createGoodsSubject')
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGoodsSubject/creategoodssubject'),
                trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart'),
                trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($goods_without_lang->name).'/editgoodssubject/'.$goods_without_lang->id.'/'.$edited_lang_id)
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.add_subject') => urlForLanguage($lang, 'creategoodssubject'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'goodssubjectcart'),
                trans('variables.edit_element') => urlForLanguage($lang, 'editgoodssubject/'.$goods_without_lang->id.'/'.$edited_lang_id)
            ]
        ])
    @endif
@else
    @if(Request::segment(5) == '' || Request::segment(4) == 'createGoodsSubject')
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart'),
                trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($goods_without_lang->name).'/editgoodssubject/'.$goods_without_lang->id.'/'.$edited_lang_id)
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'menucart'),
                trans('variables.edit_element') => urlForLanguage($lang, 'editgoodssubject/'.$goods_without_lang->id.'/'.$edited_lang_id)
            ]
        ])
    @endif
@endif

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

    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'savesubject/'.$goods_without_lang->id.'/'.$edited_lang_id) }}" id="add-form" enctype="multipart/form-data">

        @if (!empty($langs))
            @foreach ($langs as $key => $tabLang)

            <div class="tab-content {{ $key == 0 ? ' active-content' : '' }}" id={{ $tabLang->lang }}>

        <div class="part left-part">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                <li>
                    <label for="name-{{ $tabLang->lang }}">{{trans('variables.title_table')}}</label>
                    <input type="text" name="name[{{ $tabLang->lang }}]" class="name" id="name-{{ $tabLang->lang }}" data-lang="{{ $tabLang->lang }}" value="{{!is_null(old('name.'.$tabLang->lang)) ? old('name.'.$tabLang->lang) : $item[$tabLang->lang]->name}}">
                </li>

                <li style="margin-top: 10px">
                    <label for="p_id">{{trans('variables.p_id_name')}}</label>
                    <select name="p_id" id="p_id">
                        <option value="0" {{ !is_null($goods_subject_id) ? (($goods_subject_id->p_id == 0) ? 'selected' : '') : ''}}>{{trans('variables.home')}}</option>
                        {!! SelectGoodsSubjectTree($tabLang->id, 0 ,$goods_subject_id->p_id) !!}
                    </select>
                </li>

                <li>
                    <label for="descr-{{ $tabLang->lang }}">Описание</label>
                    <textarea name="descr[{{ $tabLang->lang }}]" id="descr-{{ $tabLang->lang }}">{{ !is_null(old('descr.'.$tabLang->lang)) ? old('descr.'.$tabLang->lang) : $item[$tabLang->lang]->descr}}</textarea>

                </li>
            </ul>
        </div>
        <div class="part right-part">
            <ul>
                 <li>
                     <input type="hidden" name="lang[{{ $tabLang->lang }}]" value="{{ $tabLang->id }}">
                </li>
                <li>
                    <label for="alias">{{trans('variables.alias_table')}}</label>
                    <input type="text" name="alias" id="alias" value="{{$goods_subject_id->alias or ''}}">
                </li>
                 @if($groupSubRelations->save == 1)
                    <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
                @endif
            </ul>
            <ul>
                <hr><h6>Seo Тексты</h6>
                <li>
                    <label for="h1_title">{{trans('variables.h1_title_page')}}</label>
                    <input type="text" name="h1_title" id="h1_title" autocomplete="off" value="{{$goods_without_lang->h1_title or ''}}">
                </li>
                <li>
                    <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                    <input type="text" name="meta_title" id="meta_title" autocomplete="off" value="{{$goods_without_lang->meta_title or ''}}">
                </li>
                <li>
                    <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" autocomplete="off" value="{{$goods_without_lang->meta_keywords or ''}}">
                </li>
                <li>
                    <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                    <input type="text" name="meta_description" id="meta_description" autocomplete="off" value="{{$goods_without_lang->meta_description or ''}}">
                </li>
            </ul>
        </div>

    </div>
    @endforeach
@endif

    </form>
</div>
@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop
