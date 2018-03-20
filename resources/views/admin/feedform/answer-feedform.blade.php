@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@include('list-elements', [
    'actions' => [
        trans('variables.elements_list') => urlForFunctionLanguage($lang, '')
    ]
])

<div class="list-content">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$feedform->id) }}" id="add-form">
        
        <div class="part left-part">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                <li>
                    <label>{{trans('variables.name_text')}}</label>
                    <div class="content-feedform">{{$feedform->name}}</div>
                </li>
                <li>
                    <label>Сообщение</label>
                    <div class="content-feedform">{{$feedform->question}}</div>
                </li>
                <li>
                    <label>{{trans('variables.date_table')}}</label>
                    <div class="content-feedform">{{$feedform->created_at}}</div>
                </li>
                <li>
                    <label>{{trans('variables.phone')}}</label>
                    <div class="content-feedform">{{$feedform->phone}}</div>
                </li>
                <li>
                    <label>{{trans('variables.email_text')}}</label>
                    <div class="content-feedform">{{$feedform->email}}</div>
                </li>
                <li>
                    <label>{{trans('variables.user_ip')}}</label>
                    <div class="content-feedform">{{$feedform->ip}}</div>
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
