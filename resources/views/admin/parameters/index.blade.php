@extends('app')
@include('nav-bar')
@include('left-menu')
 
@section('content')

    @if($groupSubRelations->new == 1)
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, 'options'),
                trans('variables.add_element') => url($lang. '/back/goods/options/create'),
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

    @if(!empty($params))
    
     <table class="el-table" id="tablelistsorter">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{trans('variables.title_table')}}</th>
                <th>Тип</th>
                <th>{{trans('variables.edit_table')}}</th>
                <th>Дата</th>
                @if($groupSubRelations->del_to_rec == 1)
                    <th>{{trans('variables.delete_table')}}</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($params as $key => $param)

                <tr id="{{$param->menu_id}}">
                    <td>#{{ $key + 1 }}</td>
                    <td>{{ $param->name }}</td>
                    <td>{{ $param->parameterId->type }}</td>
                    <td>
                        @foreach($lang_list as $lang_key => $one_lang)
                            <a href="{{url($lang.'/back/goods/options/edit/'.$param->parameterId->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($param->parameter_id, $one_lang->id, 'parameter')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                        @endforeach
                    </td>
                    <td>{{ $param->created_at }}</td>
                    @if($groupSubRelations->del_to_rec == 1)
                        <td class="destroy-element"><a href="{{url($lang. '/back/goods/options/delete/'.$param->parameterId->id)}}"><i class="fa fa-trash"></i></a></td>
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