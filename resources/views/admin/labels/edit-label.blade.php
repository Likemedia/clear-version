@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.add_element') => urlForFunctionLanguage($lang, 'createLabel/createitem'),
            trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($labels_without_lang->name).'/edititem/'.$labels_without_lang->id.'/'.$edited_lang_id)
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($labels_without_lang->name).'/edititem/'.$labels_without_lang->id.'/'.$edited_lang_id)
        ]
    ])
@endif

<div class="list-content">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$labels_without_lang->id.'/'.$edited_lang_id) }}" id="add-form">
        <div class="part left-part">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                <li>
                    <label for="name">{{trans('variables.title_table')}}</label>
                    <input type="text" name="name" id="name" value="{{$labels->name or ''}}">
                </li>
            </ul>
            @if($groupSubRelations->new == 1)
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
