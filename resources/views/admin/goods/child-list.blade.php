@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if(empty($child_goods_list) && empty($child_goods_item_list))
    @if($groupSubRelations->new == 1)
        @if(Request::segment(5) == '' || Request::segment(4) == 'createGoodsSubject' || Request::segment(4) == 'createGoodsItem')
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGoodsSubject/creategoodssubject'),
                    trans('variables.add_item') => urlForFunctionLanguage($lang, 'createGoodsItem/creategoodsitem'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart'),
                ]
            ])
        @else
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_subject') => urlForLanguage($lang, 'creategoodssubject'),
                    trans('variables.add_item') => urlForLanguage($lang, 'creategoodsitem'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodssubjectcart'),
                ]
            ])
        @endif
    @else
        @if(Request::segment(5) == '' || Request::segment(4) == 'createGoodsSubject')
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart'),
                ]
            ])
        @else
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodssubjectcart'),
                ]
            ])
        @endif

    @endif
@elseif(empty(CheckIfSubjectHasItems('goods', $goods_list_id->id)))
    @if($groupSubRelations->new == 1)
        @if(Request::segment(5) == '' || Request::segment(4) == 'createGoodsSubject')
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGoodsSubject/creategoodssubject'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart'),
                ]
            ])
        @else
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_subject') => urlForLanguage($lang, 'creategoodssubject'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodssubjectcart'),
                ]
            ])
        @endif
    @else
        @if(Request::segment(5) == '' || Request::segment(4) == 'createGoodsSubject')
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart'),
                ]
            ])
        @else
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodssubjectcart'),
                ]
            ])
        @endif
    @endif
@else
    @if($groupSubRelations->new == 1)
        @if(Request::segment(5) == '' || Request::segment(4) == 'createGoodsItem')
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_item') => urlForFunctionLanguage($lang, 'createGoodsItem/creategoodsitem'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsItemCart/goodsitemcart'),
                ]
            ])
        @else
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_item') => urlForLanguage($lang, 'creategoodsitem'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart'),
                ]
            ])
        @endif
    @else
        @if(Request::segment(5) == '' || Request::segment(4) == 'createGoodsItem')
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsItemCart/goodsitemcart'),
                ]
            ])
        @else
            @include('list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart'),
                ]
            ])
        @endif
    @endif
@endif

@if(empty(CheckIfSubjectHasItems('goods', $goods_list_id->id)))
    @if(!empty($child_goods_list))
        <table class="el-table" id="tablelistsorter" action="subject" url="{{$url_for_active_elem}}">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{trans('variables.title_table')}}</th>
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
            @foreach($child_goods_list as $key => $one_goods_subject_list)
                <tr id="{{$one_goods_subject_list->goods_subject_id}}">
                    <td>#{{ $key + 1 }}</td>
                    <td><a href="{{urlForFunctionLanguage($lang, $one_goods_subject_list->goodsSubjectId->alias.'/memberslist')}}">{{!empty(IfHasName($one_goods_subject_list->goods_subject_id, $lang_id, 'goods_subject')) ? IfHasName($one_goods_subject_list->goods_subject_id, $lang_id, 'goods_subject') : trans('variables.another_name')}}</a></td>
                    <td>
                        @foreach($lang_list as $lang_key => $one_lang)
                            <a href="{{urlForFunctionLanguage($lang, GetParentAlias($one_goods_subject_list->goods_subject_id, 'goods_subject_id').'/editgoodssubject/'.$one_goods_subject_list->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_goods_subject_list->goods_subject_id, $one_lang->id, 'goods_subject')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
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
                            <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($one_goods_subject_list->name).'/destroygoodssubject/'.$one_goods_subject_list->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
                        @else
                            <td>{{trans('variables.delete_inner_modules')}}</td>
                        @endif
                    @endif
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan=6></td>
            </tr>
            </tfoot>
        </table>
    @else
        <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
    @endif
@elseif(!empty(CheckIfSubjectHasItems('goods', $goods_list_id->id)))
    @if(!empty($child_goods_item_list))
        <table class="el-table" id="tablelistsorter" action="item" url="{{$url_for_active_elem}}">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{trans('variables.title_table')}}</th>
                <th>Категория</th>
                <th>Изображения</th>
                <th>{{trans('variables.edit_table')}}</th>
                @if($groupSubRelations->active == 1)
                    <th>Истории</th>
                @endif
                <th>{{trans('variables.position_table')}}</th>
                @if($groupSubRelations->del_to_rec == 1)
                    <th>{{trans('variables.delete_table')}}</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($child_goods_item_list as $key => $one_goods_item_list)
                <tr id="{{$one_goods_item_list->goods_item_id}}">
                    <td>#{{ $key+1 }}</td>
                    <td>{{!empty(IfHasName($one_goods_item_list->goods_item_id, $lang_id, 'goods_item')) ? IfHasName($one_goods_item_list->goods_item_id, $lang_id, 'goods_item') : trans('variables.another_name')}}</td>

                    <td>{{ getGoodCategory($one_goods_item_list->goodsItemId->goods_subject_id, $lang_id) }}</td>

                    <td class="photos"><a href="{{urlForLanguage($lang, 'itemsphoto/'.$one_goods_item_list->id)}}"><small>Смотреть {{trans('variables.photo')}}</small></a></td>
                    <td>
                        @foreach($lang_list as $lang_key => $one_lang)
                            <a href="{{urlForFunctionLanguage($lang, getSubjectByItem($one_goods_item_list->goodsItemId->goods_subject_id)->alias.'/editgoodsitem/'.$one_goods_item_list->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_goods_item_list->goods_item_id, $one_lang->id, 'goods_item')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                        @endforeach
                    </td>
                    @if($groupSubRelations->active == 1)
                        <td><a href="" class="change-active {{$one_goods_item_list->goodsItemId->active == 1 ? '' : 'negative'}}" active="{{$one_goods_item_list->goodsItemId->active}}" element-id="{{$one_goods_item_list->goodsItemId->id}}" action="item" url="{{$url_for_active_elem}}">{{$one_goods_item_list->goodsItemId->active == 1 ? '+' : '-'}}</a></td>
                    @endif
                    <td class="dragHandle" nowrap style="cursor: move;">
                        <a class="top-pos" href=""></a>
                        <a class="bottom-pos" href=""></a>
                    </td>
                    @if($groupSubRelations->del_to_rec == 1)
                        <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($one_goods_item_list->name).'/destroygoodsitem/'.$one_goods_item_list->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan=8></td>
            </tr>
            </tfoot>
        </table>
    @else
        <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
    @endif
@else
    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop
