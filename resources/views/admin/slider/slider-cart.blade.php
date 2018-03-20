@extends('app')

@include('nav-bar')

@include('left-menu')

@section('content')

    @include('speedbar')

    @if($groupSubRelations->new == 1)
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                trans('variables.add_element') => urlForFunctionLanguage($lang, 'createBanner/createitem'),
                trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems')
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems')
            ]
        ])
    @endif

    @if(!empty($deleted_banner_list))
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
            @foreach($deleted_banner_list as $deleted_banner)
                <tr>
                    <td>{{$deleted_banner->name}}</td>
                    <td>
                        <a href="{{urlForFunctionLanguage($lang, str_slug($deleted_banner->name).'/restore/'.$deleted_banner->id)}}"><img src="/images/restore.gif" alt=""></a>
                    </td>
                    @if($groupSubRelations->del_from_rec == 1)
                        <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($deleted_banner->name).'/destroybanner/'.$deleted_banner->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
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

