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
                trans('variables.edit_element') => urlForFunctionLanguage($lang, $goods_item_id->alias.'/editgoodsitem/'.$goods_item_id->id.'/'.$lang_id),
                trans('variables.photo') => urlForLanguage($lang, 'itemsphoto/'.$goods_item_id->id),
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
    <form action="{{url($lang, ['back','upload'])}}" method="post" class="dropzone needsclick dz-clickable" id="image-upload" enctype="multipart/form-data" element-id="{{$goods_item_id->id}}" msg="{{trans('variables.img')}}">
        <div class="dz-message needsclick">
            <span>{{trans('variables.img')}}</span>
        </div>
        <div class="fallback">
            <input name="file" type="file" />
        </div>
    </form>
    
    @if(!$goods_photo->isEmpty())
        <table class="el-table" id="tablelistsorter" action="gallery" url="{{$url_for_active_elem}}">
            <thead>
            <tr>
                <th>{{trans('variables.photo')}}</th>
                @if($groupSubRelations->active == 1)
                    <th>{{trans('variables.active_table')}}</th>
                @endif
                <th>{{trans('variables.position_table')}}</th>
                @if($groupSubRelations->del_from_rec == 1)
                    <th>{{trans('variables.delete_table')}}</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($goods_photo as $one_goods_photo)
                <tr id="{{$one_goods_photo->id}}">
                    <td>
                        <a class="fancybox-thumbs" data-fancybox-group="thumb" rel="fancybox-thumb" href="/upfiles/gallery/{{$one_goods_photo->img}}"><img src="/upfiles/gallery/s/{{$one_goods_photo->img}}"></a>
                    </td>
                    @if($groupSubRelations->active == 1)
                        <td><a href="" class="change-active {{$one_goods_photo->active == 1 ? '' : 'negative'}}" active="{{$one_goods_photo->active}}" element-id="{{$one_goods_photo->id}}" action="gallery" url="{{$url_for_active_elem}}">{{$one_goods_photo->active == 1 ? '+' : '-'}}</a></td>
                    @endif
                    <td class="dragHandle" nowrap style="cursor: move;">
                        <a class="top-pos" href=""></a>
                        <a class="bottom-pos" href=""></a>
                    </td>
                    @if($groupSubRelations->del_from_rec == 1)
                        <td class="destroy-element"><a href="{{urlForLanguage($lang, 'destroygoodsphoto/'.$one_goods_photo->id)}}"><img src="/images/trash_ico.png" height="15" width="12" alt=""></a></td>
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
