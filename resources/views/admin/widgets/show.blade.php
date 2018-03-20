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
@include('alerts')
@if(!empty($rows))
<table class="el-table" id="tablelistsorter">
    <thead>
        <tr>
            <th>ID</th>
            <th>{{trans('variables.title_table')}}</th>
            <th>Создано</th>
            <th>{{trans('variables.edit_table')}}</th>
            @if($groupSubRelations->del_to_rec == 1)
            <th>{{trans('variables.delete_table')}}</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $key => $row)
        <tr id="{{$row->id}}">
            <td>#{{ $key + 1 }}</td>
            <td>
                {{ !empty($row->title) ? $row->title : trans('variables.another_name')}}
            </td>
            <td>{{ $row->created_at->format('d/m/Y') }}</td>
            <td>
                <a href="{{urlForFunctionLanguage($lang, 'item/edit/'.$row->itemId->id.'/1')}}" ><i class="fa fa-edit"></i></a>
            </td>
            @if($groupSubRelations->del_to_rec == 1)
            <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, 'item/delete/'.$row->itemId->id)}}"><i class="fa fa-trash"></i></a></td>
            @endif
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan=7></td>
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
