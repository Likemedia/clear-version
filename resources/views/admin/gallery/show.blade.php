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


@if(!empty($rows))

    <table class="el-table" id="tablelistsorter">
        <thead>
        <tr>
            <th>ID</th>
            <th>Изображение</th>
            <th>{{trans('variables.title_table')}}</th>
            <th>{{trans('variables.edit_table')}}</th>
            @if($groupSubRelations->active == 1)
                <th>{{trans('variables.active_table')}}</th>
            @endif
            <th>{{trans('variables.position_table')}}</th>
            <th>Дата</th>
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
                    <img src="/upfiles/gallery/{{ $row->img }}" alt="">
                </td>
                <td>
                    {{ !empty($row->title) ? $row->title : trans('variables.another_name')}}
                </td>

                <td>
                    @foreach($lang_list as $lang_key => $one_lang)
                        <a href="{{urlForFunctionLanguage($lang, 'item/edit/'.$row->itemId->id.'/'.$one_lang->id)}}" {{ strlen($row->title) > 0 ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                    @endforeach
                </td>
                <td>
                    <a href="" class="change-active {{$row->itemId->active == 1 ? '' : 'negative'}}" active="{{$row->itemId->active}}" element-id="{{$row->itemId->id}}">{{$row->itemId->active == 1 ? '+' : '-'}}</a>
                </td>
                <td class="dragHandle" nowrap style="cursor: move;">
                    <a class="top-pos" href=""></a>
                    <a class="bottom-pos" href=""></a>
                </td>
                <td>{{ $row->created_at }}</td>
                @if($groupSubRelations->del_to_rec == 1)
                    @if(empty(IfHasChild($row->itemId->id, 'menu_id')))
                        <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($row->title).'/delete/'.$row->id)}}"><i class="fa fa-trash"></i></a></td>
                    @else
                        <td>{{trans('variables.delete_inner_modules')}}</td>
                    @endif
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
