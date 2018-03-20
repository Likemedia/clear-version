@extends('app')
@include('nav-bar')
@include('left-menu')
 
@section('content')
    @include('speedbar')
    @if($groupSubRelations->new == 1)
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                trans('variables.add_element') => urlForFunctionLanguage($lang, 'createMenu/createmenu'),
                trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'menuCart/menucart')
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'menuCart/menucart')
            ]
        ])
    @endif

    @if(!empty($menu_elements))
        
        <table class="el-table" id="tablelistsorter">
            <thead>
            <tr>
                <th>ID</th>
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
            @foreach($menu_elements as $key => $one_menu_element)

                <tr id="{{$one_menu_element->menu_id}}">
                    <td>#{{ $key + 1 }}</td>
                    @if(!empty(IfHasChild($one_menu_element->menuId->id, 'menu_id')))
                        <td><a href="{{urlForFunctionLanguage($lang, $one_menu_element->menuId->alias.'/memberslist')}}">{{!empty(IfHasName($one_menu_element->menu_id, $lang_id, 'menu')) ? IfHasName($one_menu_element->menu_id, $lang_id, 'menu') : trans('variables.another_name')}}</a></td>
                    @else
                        <td>{{ !empty(IfHasName($one_menu_element->menu_id, $lang_id, 'menu')) ? IfHasName($one_menu_element->menu_id, $lang_id, 'menu') : trans('variables.another_name')}}</td>
                    @endif
                    <td>
                        @foreach($lang_list as $lang_key => $one_lang)
                            <a href="{{urlForFunctionLanguage($lang, $one_menu_element->menuId->alias.'/editmenu/'.$one_menu_element->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_menu_element->menu_id, $one_lang->id, 'menu')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                        @endforeach
                    </td>
                    @if($groupSubRelations->active == 1)
                        <td><a href="" class="change-active {{$one_menu_element->menuId->active == 1 ? '' : 'negative'}}" active="{{$one_menu_element->menuId->active}}" element-id="{{$one_menu_element->menuId->id}}">{{$one_menu_element->menuId->active == 1 ? '+' : '-'}}</a></td>
                    @endif
                    <td class="dragHandle" nowrap style="cursor: move;">
                        <a class="top-pos" href=""></a>
                        <a class="bottom-pos" href=""></a>
                    </td>
                    <td>{{ $one_menu_element->created_at }}</td>
                    @if($groupSubRelations->del_to_rec == 1)
                        @if(empty(IfHasChild($one_menu_element->menuId->id, 'menu_id')))
                            <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($one_menu_element->name).'/destroymenu/'.$one_menu_element->id)}}"><i class="fa fa-trash"></i></a></td>
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


<!-- <nav class="text-xs-right">
    <ul class="pagination">
        <li class="page-item"> <a class="page-link" href=""> Prev </a> </li>
        <li class="page-item active"> <a class="page-link" href=""> 1 </a> </li>
        <li class="page-item"> <a class="page-link" href="">  2 </a> </li>
        <li class="page-item"> <a class="page-link" href="">  3 </a> </li>
        <li class="page-item"> <a class="page-link" href="">  4 </a> </li>
        <li class="page-item"> <a class="page-link" href="">  5 </a> </li>
        <li class="page-item"> <a class="page-link" href=""> Next </a> </li>
    </ul>
</nav>
 -->
@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop

