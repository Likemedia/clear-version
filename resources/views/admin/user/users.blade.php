@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
            trans('variables.add_element') => urlForLanguage($lang, 'createuser'),
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
        ]
    ])
@endif

@if(!$user->isEmpty())
    <h4><small><i class="fa fa-thumb-tack"></i> {{ $topTitle }}</small></h4>
    <table class="el-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Создан</th>
                <th>Логин</th>
                <th>Email</th>
                <th>Группа</th>
                <th>{{trans('variables.edit_table')}}</th>
                @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                    <th>{{trans('variables.delete_table')}}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($user as $key => $usr)
                <tr>
                    <td>#{{ $key + 1 }}</td>
                    <td>{{ $usr->created_at->format('d.m.Y h:i') }}</td>
                    <td>{{ $usr->name }}</td>
                    <td>{{ $usr->email }}</td>
                    <td>{{ getUserGroup($usr->admin_user_group_id) }}</td>
                    <td>
                        <a href="{{urlForLanguage($lang, 'edituser/'.$usr->id)}}"><i class="fa fa-edit"></i></a>
                    </td>
                    @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                        <td class="destroy-element"><a href="{{urlForLanguage($lang, 'destroyuser/'.$usr->id)}}"><i class="fa fa-trash"></i></a></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan=7>{!! $user->links() !!}</td>
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
