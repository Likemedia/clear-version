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
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$item->itemId->id.'/'.$edited_lang_id) }}" id="add-form" enctype="multipart/form-data">

    {{-- Add new fields here --}}
    <div class="part left-part">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <ul>
            <li>
                <label for="name">{{trans('variables.title_table')}}</label>
                <input type="text" name="title" id="name" value="{{$item->title or ''}}">
            </li>
            <li>
                <label for="video">Видео</label>
                <input type="text" name="video" id="video" value="{{ $item->video }}">
            </li>
            <li>
                {!! $item->video !!}
            </li>
        </ul>
    </div>

    {{-- required staic fields --}}
    <div class="part right-part">
        <ul>
            <li>
                <label for="lang">{{trans('variables.lang')}}</label>
                <select name="lang" id="lang" onChange="window.location.href=this.value">
                    @foreach($lang_list as $lang_key => $one_lang)
                        <option value="{{ url($lang.'/back/'.Request::segment(3).'/'.Request::segment(4).'/'.Request::segment(5).'/'.Request::segment(6).'/'.$one_lang->id) }}" {{$one_lang->id == $edited_lang_id ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                    @endforeach
                </select>
                <input type="hidden" name="lang" value="{{ Request::segment(7) }}">
            </li>
            <li>
                <label for="category">Категория</label>
                <select name="category" id="category">
                    <option value="medical" {{ $item->itemId->category == 'medical' ? 'selected' : '' }}>Medical</option>
                    <option value="non-medical" {{ $item->itemId->category == 'non-medical' ? 'selected' : '' }}>Non-medical</option>
                </select>
            </li>
            <li>
                <label for="alias">{{trans('variables.alias_table')}}</label>
                <input type="text" name="alias" id="alias" value="{{$item_id->alias or ''}}">
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
