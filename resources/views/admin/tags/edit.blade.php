@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

    @include('speedbar')

    <div class="list-content">
        <div class="tab-area">
            @include('alerts')
        </div>

        <form class="form-reg" method="POST" action="{{ route('tags.update', $tag->id) }}">
            {{ csrf_field() }} {{ method_field('PATCH') }}

                <div class="part full-part" style="padding: 15px;">

                    <ul>
                        <li>
                            <label>{{trans('variables.title_table')}}</label>
                            <input type="text" name="name" value="{{ $tag->name }}" />
                        </li>

                        <li>
                            <input type="submit" value="{{trans('variables.save_it')}}">
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
