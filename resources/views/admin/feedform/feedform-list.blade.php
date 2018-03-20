@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@include('list-elements', [
    'actions' => [
        trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
    ]
])

@if(!$feedform->isEmpty())
    <table class="el-table" id="tablelistsorter">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>{{trans('variables.date_table')}}</th>
            <th>Смотреть</th>
            @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                <th>{{trans('variables.delete_table')}}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($feedform as $one_feedform)
            
            <tr>
                <td>#{{ $one_feedform->id }}</td>
                <td>{{$one_feedform->name}}</td>
                <td>{{$one_feedform->email}}</td>
                <td>{{$one_feedform->phone}}</td>
                <td>{{$one_feedform->date}}</td>
                <td><a href="{{urlForFunctionLanguage($lang, str_slug($one_feedform->name).'/addfeedform/'.$one_feedform->id)}}"><i class="fa fa-sign-in"></i></a></td>
                @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                    <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($one_feedform->name).'/destroyfeedform/'.$one_feedform->id)}}"><i class="fa fa-trash"></i></a></td>
                @endif
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                <td colspan="7">{!! $feedform->links() !!}</td>
            @else
                <td colspan="6">{!! $feedform->links() !!}</td>
            @endif
        </tr>
        </tfoot>
    </table>
@else
    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop

