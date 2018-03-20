@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.add_element') => urlForFunctionLanguage($lang, 'createSetting/createitem'),
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
        ]
    ])
@endif

<div class="list-content">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'save') }}" id="add-form">
        
    <div class="part left-part">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <ul>
            <li>
                <label for="set_type">{{trans('variables.parameter_type')}}</label>
                <select name="set_type" id="set_type">
                    <option value="input">{{trans('variables.input')}}</option>
                    <option value="textarea">{{trans('variables.textarea')}}</option>
                    <option value="ckeditor">{{trans('variables.ckeditor')}}</option>
                </select>
            </li>
            <li>
                <label for="name">{{trans('variables.title_table')}} <small class="text-danger"> * оно нигде не используется</small></label>
                <input type="text" name="name" id="name">
            </li>
           
            <li class="ckeditor">
                <label for="body">Виджет <small class="text-primary">(тип - CKEditor)</small></label>
                <textarea name="body" id="body" data-type="ckeditor"></textarea>
                <script>
                    CKEDITOR.replace( 'body', {
                        language: '{{$lang}}',
                    } );
                </script>
            </li>
            <li class="input">
                <label for="input">Виджет <small class="text-primary">(тип - input)</small></label>
                <input type="text" name="input" id="input">
            </li>
            <li class="textarea">
                <label for="textarea">Виджет <small class="text-primary">(тип - textarea)</small></label>
                <textarea name="textarea" id="textarea" cols="92" rows="15"></textarea>
            </li>
        </ul>
        </div>
        <div class="part right-part">
            <ul>
                <li>
                    <label for="alias">Short Cut</label>
                    <input type="text" name="alias" id="alias">
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
