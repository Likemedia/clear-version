@extends('app')

@include('nav-bar')

@include('left-menu')

@section('content')

    @include('speedbar')

    @if($groupSubRelations->new == 1)
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.add_element') => urlForLanguage($lang, 'createmenu'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'menucart')
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'menucart')
            ]
        ])
    @endif

    @if(!empty($child_menu_list))
        <table class="el-table" id="tablelistsorter" action="membersmenu" url="{{$url_for_active_elem}}">
            <thead>
            <tr>
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
            @foreach($child_menu_list as $key => $one_child_menu_element)
                <tr id="{{$one_child_menu_element->menu_id}}">
                    @if(!empty(IfHasChild($one_child_menu_element->menuId->id, 'menu_id')))
                        <td><a href="{{urlForFunctionLanguage($lang, $one_child_menu_element->menuId->alias.'/memberslist')}}">{{!empty(IfHasName($one_child_menu_element->menu_id, $lang_id, 'menu')) ? IfHasName($one_child_menu_element->menu_id, $lang_id, 'menu') : trans('variables.another_name')}}</a></td>
                    @else
                        <td>{{ !empty(IfHasName($one_child_menu_element->menu_id, $lang_id, 'menu')) ? IfHasName($one_child_menu_element->menu_id, $lang_id, 'menu') : trans('variables.another_name')}}</td>
                    @endif
                    <td>
                        @foreach($lang_list as $lang_key => $one_lang)
                            <a href="{{urlForFunctionLanguage($lang, GetParentAlias($one_child_menu_element->menu_id, 'menu_id').'/editmenu/'.$one_child_menu_element->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_child_menu_element->menu_id, $one_lang->id, 'menu')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                        @endforeach
                    </td>
                    @if($groupSubRelations->active == 1)
                        <td><a href="" class="change-active {{$one_child_menu_element->menuId->active == 1 ? '' : 'negative'}}" active="{{$one_child_menu_element->menuId->active}}" element-id="{{$one_child_menu_element->menuId->id}}" action="membersmenu" url="{{$url_for_active_elem}}">{{$one_child_menu_element->menuId->active == 1 ? '+' : '-'}}</a></td>
                    @endif
                    <td class="dragHandle" nowrap style="cursor: move;">
                        <a class="top-pos" href=""></a>
                        <a class="bottom-pos" href=""></a>
                    </td>
                    @if($groupSubRelations->del_to_rec == 1)
                        @if(empty(IfHasChild($one_child_menu_element->menuId->id, 'menu_id')))
                            <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($one_child_menu_element->name).'/destroymenu/'.$one_child_menu_element->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
                        @else
                            <td>{{trans('variables.delete_inner_modules')}}</td>
                        @endif
                    @endif
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan=5></td>
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

