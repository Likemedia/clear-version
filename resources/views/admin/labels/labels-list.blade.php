@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            trans('variables.add_element') => urlForFunctionLanguage($lang, 'createLabel/createitem'),
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
        ]
    ])
@endif

@if(!empty($labels_list))
    <table class="el-table" id="tablelistsorter">
        <thead>
        <tr>
            <th class="first-table-child">ID</th>
            <th>Надпись</th>
            <th>{{trans('variables.edit_table')}}</th>
            @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                <th>{{trans('variables.delete_table')}}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($labels_list as $key => $label)
            <tr>
                <td class="first-table-child">{{$label->labels_id}}</td>
                {{--<td>{{$label->name}}</td>--}}
                <td>{{ !empty(IfHasName($label->labels_id, $lang_id, 'labels')) ? IfHasName($label->labels_id, $lang_id, 'labels') : trans('variables.another_name')}}</td>
                <td>
                    @foreach($lang_list as $lang_key => $one_lang)
                        <a href="{{urlForFunctionLanguage($lang, str_slug($label->name).'/edititem/'.$label->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($label->labels_id, $one_lang->id, 'labels')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                    @endforeach
                </td>
                @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                    <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($label->name).'/destroylabel/'.$label->id)}}"><i class="fa fa-trash"></i></td>
                @endif
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan=5>{!! $labels_list_id->links() !!}</td>
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

