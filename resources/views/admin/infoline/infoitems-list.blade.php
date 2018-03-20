@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
            trans('variables.add_element') => urlForLanguage($lang, 'createinfoitem'),
            trans('variables.elements_basket') => urlForLanguage($lang, 'infoitemscart')
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
            trans('variables.elements_basket') => urlForLanguage($lang, 'infoitemscart')
        ]
    ])
@endif

@if(!empty($info_item))

<a class="black" href="{{ $topSrc }}"><h4 class="title"><small> <i class="fa fa-thumb-tack"></i> {{ $topTitle }}</small></h3></a>

<table class="el-table">
    <thead>
    <tr>
        <th>ID</th>
        <th>{{trans('variables.title_table')}}</th>
        <th>Категория</th>
        <th>{{trans('variables.edit_table')}}</th>
        @if($groupSubRelations->active == 1)
            <th>{{trans('variables.active_table')}}</th>
        @endif
        @if($groupSubRelations->del_to_rec == 1)
            <th>{{trans('variables.delete_table')}}</th>
        @endif
    </tr>
    </thead>
    <tbody>

    @foreach($info_item as $key => $one_info_item)
        <tr id="{{$one_info_item->info_item_id}}">
            <td>#{{ $one_info_item->id }}</td>
            <td>{{ !empty(IfHasName($one_info_item->info_item_id, $lang_id, 'info_item')) ? IfHasName($one_info_item->info_item_id, $lang_id, 'info_item') : trans('variables.another_name')}}</td>
            <td>{{ getInfoLineNameById($one_info_item->infoItemId->info_line_id, $lang_id) }}</td>
            <td>
                @foreach($lang_list as $lang_key => $one_lang)
                    <a href="{{urlForLanguage($lang, 'editinfoitem/'.$one_info_item->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_info_item->info_item_id, $one_lang->id, 'info_item')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                @endforeach
            </td>

            @if($groupSubRelations->active == 1)
                <td><a href="" class="change-active {{$one_info_item->infoItemId->active == 1 ? '' : 'negative'}}" active="{{$one_info_item->infoItemId->active}}" element-id="{{$one_info_item->infoItemId->id}}" action="infoitemslist" url="{{$url_for_active_elem}}">{{$one_info_item->infoItemId->active == 1 ? '+' : '-'}}</a></td>
            @endif
            @if($groupSubRelations->del_to_rec == 1)
                <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($one_info_item->name).'/destroyinfoitem/'.$one_info_item->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
            @endif
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan=6>{!! $info_items_list->links() !!}</td>
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

