@extends('app')

@include('nav-bar')

@include('left-menu')

@section('content')

    @include('speedbar')

    @if($groupSubRelations->new == 1)
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.add_subject') => urlForLanguage($lang, 'creategoodsitem'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart')
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart')
            ]
        ])
    @endif

    @if(!empty($deleted_item_elems))
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
            @foreach($deleted_item_elems as $deleted_one_item_elem)
                <tr>
                    <td>{{$deleted_one_item_elem->name}}</td>
                    <td>
                        <a href="{{urlForFunctionLanguage($lang, str_slug($deleted_one_item_elem->name).'/restoregoodsitem/'.$deleted_one_item_elem->id)}}"><img src="/images/restore.gif" alt=""></a>
                    </td>
                    @if($groupSubRelations->del_from_rec == 1)
                        <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($deleted_one_item_elem->name).'/destroygoodsitem/'.$deleted_one_item_elem->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
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

