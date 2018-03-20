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
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems'),
            trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($banner_top_without_lang->name).'/edititem/'.$banner_top_without_lang->id.'/'.$edited_lang_id)
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems'),
            trans('variables.edit_element') => urlForFunctionLanguage($lang, str_slug($banner_top_without_lang->name).'/edititem/'.$banner_top_without_lang->id.'/'.$edited_lang_id)
        ]
    ])
@endif

<div class="list-content">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$banner_top_without_lang->id.'/'.$edited_lang_id) }}" id="add-form" enctype="multipart/form-data">
        <div class="part left-part">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
               
                <li>
                    <label for="name">{{trans('variables.title_table')}}</label>
                    <input type="text" name="name" id="name" value="{{$banner_top->name or ''}}">
                </li>
                <li>
                    <label for="title_h1">Заголовок H1</label>
                    <input name="title_h1" id="title_h1" value="{{$banner_top->title_h1 or ''}}"/>
                </li>
                <li>
                    <label for="title_h2">Заголовок H2</label>
                    <input name="title_h2" id="title_h2" value="{{$banner_top->title_h2 or ''}}"/>
                </li>
                
                <li>
                    <label for="img">{{trans('variables.img')}}</label>
                    <div class='file-div'>
                        <button class='btn btn-sm'>
                            <span class='glyphicon glyphicon-refresh-animate'>{{trans('variables.select_file')}}</span>
                        </button>
                        @if(!empty($banner_top->img))
                            <input type="hidden" name="file" data-url="{{url($lang, ['back', 'upload'])}}" path="{{$modules_name->src}}" value="upfiles/{{$modules_name->src}}/{{$banner_top->img}}" />
                            <img src="/upfiles/{{$modules_name->src}}/{{$banner_top->img}}">
                        @elseif(!empty($banner_top_without_lang->img))
                            <input type="hidden" name="file" data-url="{{url($lang, ['back', 'upload'])}}" path="{{$modules_name->src}}" value="upfiles/{{$modules_name->src}}/{{$banner_top_without_lang->img}}" />
                            <img src="/upfiles/{{$modules_name->src}}/{{$banner_top_without_lang->img}}">
                        @else
                            <input type="hidden" name="file" data-url="{{url($lang, ['back', 'upload'])}}" path="{{$modules_name->src}}" />
                        @endif
                    </div>

                </li>
               
            </ul>
        </div>

        <div class="part  right-part">
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
                    <label for="link">{{trans('variables.link')}}</label>
                    <input type="text" name="link" id="link" value="{{$banner_top->link or ''}}">
                </li>
                 <li>
                    <label for="active">
                    <input class="checkbox square" type="checkbox" name="active" id="active" {{$banner_top_id->active == 1 ? 'checked' : ''}}>
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
                    <input type="text" name="alt" id="alt" value="{{$banner_top->alt or ''}}">
                </li>
                <li>
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" value="{{$banner_top->title or ''}}">
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
