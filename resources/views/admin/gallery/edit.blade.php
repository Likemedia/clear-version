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
                <label for="img_alt">Alt изображение</label>
                <input type="text" name="img_alt" id="img_alt" value="{{$item->img_alt or ''}}">
            </li>
            <li>
                <label for="img_title">Title изображение</label>
                <input type="text" name="img_title" id="img_title" value="{{$item->img_title or ''}}">
            </li>
            <li>
                <label for="img">{{trans('variables.img')}}</label>
                <div class='file-div'>
                    <button class='btn btn-sm'>
                        <span class='glyphicon glyphicon-refresh-animate'>{{trans('variables.select_file')}}</span>
                    </button>
                    @if(strlen($item->img) > 0)
                        <input type="hidden" name="file" data-url="{{url($lang, ['back', 'upload'])}}" path="{{$modules_name->src}}" value="upfiles/{{$modules_name->src}}/{{$item->img}}" />
                        <img src="/upfiles/{{$modules_name->src}}/{{$item->img}}">
                    @else
                        <input type="hidden" name="file" data-url="{{url($lang, ['back', 'upload'])}}" path="{{$modules_name->src}}" />
                    @endif
                </div>
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
                <label for="alias">Алиас</label>
                <input type="text" name="alias" id="alias" value={{ $item->itemId->alias }}>
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
