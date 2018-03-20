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

@if(!empty($banner_list))
    <table class="el-table" id="tablelistsorter">
        <thead>
            <tr>
                <th>ID</th>
                <th>Слайд</th>
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
        @foreach($banner_list as $key => $banner)
            <tr id="{{$banner->banner_top_id}}">
                <td>#{{ $banner->banner_top_id }}</td>
                <td>
                    <img class="slider-show" src="/upfiles/{{$modules_name->src}}/{{$banner->img or 'noImage.png'}}">
                </td>
                <td>{{ !empty(IfHasName($banner->banner_top_id, $lang_id, 'banner_top')) ? IfHasName($banner->banner_top_id, $lang_id, 'banner_top') : trans('variables.another_name')}}</td>
                <td>
                    @foreach($lang_list as $lang_key => $one_lang)
                        <a href="{{urlForFunctionLanguage($lang, str_slug($banner->name).'/edititem/'.$banner->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($banner->banner_top_id, $one_lang->id, 'banner_top')) ? '' : 'class=negative'}}>{{trans('variables.edit_'.$one_lang->lang)}}</a>
                    @endforeach
                </td>
                @if($groupSubRelations->active == 1)
                    <td><a href="" class="change-active {{$banner->bannerTopId->active == 1 ? '' : 'negative'}}" active="{{$banner->bannerTopId->active}}" element-id="{{$banner->bannerTopId->id}}">{{$banner->bannerTopId->active == 1 ? '+' : '-'}}</a></td>
                @endif
                <td class="dragHandle" nowrap style="cursor: move;">
                    <a class="top-pos" href=""></a>
                    <a class="bottom-pos" href=""></a>
                </td>
                @if($groupSubRelations->del_to_rec == 1)
                    <td class="destroy-element"><a href="{{urlForFunctionLanguage($lang, str_slug($banner->name).'/destroybanner/'.$banner->id)}}"><i class="fa fa-trash"></i></a></td>
                @endif
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan=7>{!! $slider_list_ids->links() !!}</td>
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

