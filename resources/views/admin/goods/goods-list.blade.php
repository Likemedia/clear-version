@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => url($lang.'/back/goods'),
            trans('variables.add_item') => url($lang.'/back/goods/create/creategoodsitem')
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => url($lang.'/back/goods'),
            trans('variables.add_item') => url($lang.'/back/goods/create/creategoodsitem')
        ]
    ])
@endif

@if(!empty($goods_subject_list))
    <table class="el-table" id="tablelistsorter" action="subject" url="{{$url_for_active_elem}}">
        <thead>
        <tr>
            <th>ID</th>
            <th>{{trans('variables.title_table')}}</th>
            <th>Зайти в категорию</th>
            <th>{{trans('variables.edit_table')}}</th>
            @if($groupSubRelations->active == 1)
                <th>{{trans('variables.active_table')}}</th>
            @endif
            <th>{{trans('variables.position_table')}}</th>
            @if($groupSubRelations->del_to_rec == 1)
                <th>{{trans('variables.delete_table')}}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($goods_subject_list as $key => $one_goods_subject_list)
            <tr id="{{$one_goods_subject_list->goods_subject_id}}">
                <td>{{ $key + 1 }}</td>
                <td>
                    {{!empty(IfHasName($one_goods_subject_list->goods_subject_id, $lang_id, 'goods_subject')) ? IfHasName($one_goods_subject_list->goods_subject_id, $lang_id, 'goods_subject') : trans('variables.another_name')}}
                </td>
                <td>
                    <a href="{{urlForFunctionLanguage($lang, $one_goods_subject_list->goodsSubjectId->alias.'/memberslist')}}"><i class="fa fa-sign-in"></i></a>
                    </td>
                <td>
                    @foreach($lang_list as $lang_key => $one_lang)
                        <a href="{{urlForFunctionLanguage($lang, $one_goods_subject_list->goodsSubjectId->alias.'/editgoodssubject/'.$one_goods_subject_list->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_goods_subject_list->goods_subject_id, $one_lang->id, 'goods_subject')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                    @endforeach
                </td>
                @if($groupSubRelations->active == 1)
                    <td><a href="" class="change-active {{$one_goods_subject_list->goodsSubjectId->active == 1 ? '' : 'negative'}}" active="{{$one_goods_subject_list->goodsSubjectId->active}}" element-id="{{$one_goods_subject_list->goodsSubjectId->id}}" action="subject" url="{{$url_for_active_elem}}">{{$one_goods_subject_list->goodsSubjectId->active == 1 ? '+' : '-'}}</a></td>
                @endif
                <td class="dragHandle" nowrap style="cursor: move;">
                    <a class="top-pos" href=""></a>
                    <a class="bottom-pos" href=""></a>
                </td>
                @if($groupSubRelations->del_to_rec == 1)
                    @if(empty(IfHasChildUniv($one_goods_subject_list->goods_subject_id, 'goods_subject')) && empty(CheckIfSubjectHasItems('goods', $one_goods_subject_list->goods_subject_id)))
                        <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($one_goods_subject_list->name).'/destroygoodssubject/'.$one_goods_subject_list->id)}}"><i class="fa fa-trash"></i></a></td>
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
