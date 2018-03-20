@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.add_element') => urlForFunctionLanguage($lang, 'createInfoLine/createinfoline'),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'infoLineCart/infolinecart')
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'infoLineCart/infolinecart')
        ]
    ])
@endif

    @if(!empty($info_line))
        <h4 class="title"><small><i class="fa fa-thumb-tack"></i> Категории новостей</small></h4>
        <table class="el-table" id="tablelistsorter">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{trans('variables.title_table')}} категории</th>
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
            @foreach($info_line as $key => $one_info_line)
                <tr id="{{$one_info_line->info_line_id}}">
                    <td>{{ $key+1 }}</td>
                    <td>
                         
                        {{ !empty(IfHasName($one_info_line->info_line_id, $lang_id, 'info_line')) ? IfHasName($one_info_line->info_line_id, $lang_id, 'info_line') : trans('variables.another_name')}}
                    </td>
                    <td>
                        <a href="{{urlForFunctionLanguage($lang, $one_info_line->infoLineId->alias.'/memberslist')}}"><i class="fa fa-sign-in"></i></a>
                    </td>
                    <td>
                        @foreach($lang_list as $lang_key => $one_lang)
                            <a href="{{urlForFunctionLanguage($lang, str_slug($one_info_line->name).'/editinfoline/'.$one_info_line->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_info_line->info_line_id, $one_lang->id, 'info_line')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                        @endforeach
                    </td>
                    @if($groupSubRelations->active == 1)
                        <td><a href="" class="change-active {{$one_info_line->infoLineId->active == 1 ? '' : 'negative'}}" active="{{$one_info_line->infoLineId->active}}" element-id="{{$one_info_line->infoLineId->id}}">{{$one_info_line->infoLineId->active == 1 ? '+' : '-'}}</a></td>
                    @endif
                    <td class="dragHandle" nowrap style="cursor: move;">
                        <a class="top-pos" href=""></a>
                        <a class="bottom-pos" href=""></a>
                    </td>
                    @if($groupSubRelations->del_to_rec == 1)
                        <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($one_info_line->name).'/destroyinfoline/'.$one_info_line->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan=7>{!! $info_line_id->links() !!}</td>
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

