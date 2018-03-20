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

        <form class="form-reg" method="POST" action="{{ route('modules.store') }}">
            {{ csrf_field() }}

            <div class="part left-part">

                <ul>
                    @foreach($lang_list as $lang_key => $one_lang)
                        <li>
                            <label for="name">{{trans('variables.title_table')}} {{ $one_lang->lang }}</label>
                            <input type="text" name="name_{{ $one_lang->lang }}" id="name_{{ $one_lang->lang }}">
                        </li>
                        <li>
                            <label>{{trans('variables.description')}} {{ $one_lang->lang }}</label>
                            <textarea name="description_{{ $one_lang->lang }}"></textarea>
                        </li>
                    @endforeach
                </ul>

            </div>

            <div class="part right-part">
                <ul>
                    <li>
                        <label for="src">Link</label>
                        <input type="text" name="src" id="src">
                    </li>
                    <li>
                        <label for="src">Controller</label>
                        <input type="controller" name="controller" id="controller">
                    </li>
                    <li>
                        <label for="table_name">Table name</label>
                        <input type="text" name="table_name" id="table_name">
                    </li>
                    <li>
                        <label for="icon">Icon</label>
                        <input type="text" name="icon" id="icon">
                    </li>
                    <input type="submit" value="{{trans('variables.save_it')}}">
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
