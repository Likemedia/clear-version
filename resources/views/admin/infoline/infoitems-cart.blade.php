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

    @if(!empty($deleted_info_item))
        <table class="el-table">
            <thead>
            <tr>
                <th>{{trans('variables.title_table')}}</th>
                <th>{{trans('variables.reestablish_table')}}</th>
                @if($groupSubRelations->del_from_rec == 1)
                    <th>{{trans('variables.delete_table')}}</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($deleted_info_item as $deleted_one_info_item)
                <tr>
                    <td>{{$deleted_one_info_item->name}}</td>
                    <td>
                        <a href="{{urlForFunctionLanguage($lang, str_slug($deleted_one_info_item->name).'/restoreinfoitem/'.$deleted_one_info_item->id)}}"><img src="/images/restore.gif" alt=""></a>
                    </td>
                    @if($groupSubRelations->del_from_rec == 1)
                        <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($deleted_one_info_item->name).'/destroyinfoitem/'.$deleted_one_info_item->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
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

