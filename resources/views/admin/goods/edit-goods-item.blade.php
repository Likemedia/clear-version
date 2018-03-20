@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

@include('speedbar')

@if($groupSubRelations->new == 1)
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.add_item') => urlForLanguage($lang, 'creategoodsitem'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart'),
                trans('variables.edit_element') => urlForLanguage($lang, 'editgoodsitem/'.$goods_without_lang->id.'/'.$edited_lang_id)
            ]
        ])
@else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                trans('variables.elements_basket') => urlForLanguage($lang, 'menucart'),
                trans('variables.edit_element') => urlForLanguage($lang, 'editgoodsitem/'.$goods_without_lang->id.'/'.$edited_lang_id)
            ]
        ])
@endif
<div class="list-content">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'saveitem/'.$goods_without_lang->id.'/'.$edited_lang_id) }}" id="add-form" enctype="multipart/form-data">
        <div class="part left-part">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>
                <li style="margin-top: 10px">
                    <label for="p_id">Категория</label>
                    <select name="p_id" id="p_id" class="chosen-select">
                        {!! SelectGoodsItemTree($lang_id, 0 ,$goods_item_id->goods_subject_id) !!}
                    </select>
                </li>
                <li>
                    <label for="name">{{trans('variables.title_table')}}</label>
                    <input type="text" name="name" id="name" value="{{$goods_elems->name or ''}}">
                </li>

                <li>
                    <label for="descr">{{trans('variables.short_description')}}</label>
                    <textarea id="descr" name="short_descr" cols="92" rows="15">{{$goods_elems->short_descr or ''}}</textarea>
                </li>
                <li>
                    <label for="body">{{trans('variables.body')}}</label>
                    <textarea name="body" id="body" data-type="ckeditor">{{$goods_elems->body or ''}}</textarea>
                    <script>
                        CKEDITOR.replace( 'body', {
                            language: '{{$lang}}',
                        } );
                    </script>
                </li>
                <li>
                    <label for="price">{{trans('variables.price')}}</label>
                    <input type="text" id="price" name="price" value="{{$goods_item_id->price or ''}}">
                </li>
                <li>
                    <label for="old_price">Старая цена</label>
                    <input type="text" name="old_price" id="old_price" value="{{$goods_item_id->old_price or ''}}">
                </li>
                <li>
                    <label for="discount">Скидка</label>
                    <select id="discount" name="discomt">
                        @if(!is_null($discount))
                            @foreach(json_decode($discount->value) as $value)
                                <option value="{{ $value }}" {{ $goods_elems->goodsItemId->discount == $value ? 'selected' : '' }}>{{ $value }} %</option>
                            @endforeach
                        @endif
                    </select>
                </li>
            </ul>

            {{-- <ul>
                @if(!empty($params))
                    <hr><h6>Характеристики товара</h6>
                    @foreach($params as $key => $param)
                        <li>
                            <label for="param-{{ $key }}">{{ $param->name }}</label>
                            <select id="param-{{ $key }}" name="value[{{ getParamId($goods_elems->goodsItemId->id, $lang_id, $param->id) }}]">
                                    <option value="null">Не выбрано</option>
                                @if(!is_null($discount))
                                    @foreach(json_decode($param->value) as $value)
                                        <option value="{{ $value }}" {{ getSelectedParam($goods_elems->goodsItemId->id, $lang_id, $param->id) == $value ? 'selected' : ''}}>{{ $value }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </li>

                    @endforeach
                @endif

            </ul> --}}
        </div>
        <div class="part right-part">
            <ul>
                <li>
                    <label for="lang">{{trans('variables.lang')}}</label>
                    <select name="lang" id="lang">
                        @foreach($lang_list as $lang_key => $one_lang)
                            <option value="{{$one_lang->id}}" {{$one_lang->id == $edited_lang_id ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                        @endforeach
                    </select>
                </li>
                <li>
                    <label for="alias">{{trans('variables.alias_table')}}</label>
                    <input type="text" name="alias" id="alias" value="{{$goods_item_id->alias or ''}}">
                </li>

                <li>
                   <label for="active">
                   <input class="checkbox square" type="checkbox" name="active" id="active" {{$goods_item_id->active == 1 ? 'checked' : ''}}>
                   <span>Истории</span>
                   </label>
               </li>
                @if($groupSubRelations->save == 1)
                    <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
                @endif
            </ul>
            <ul>
                <hr><h6>SEO Текста</h6>

                <li>
                    <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                    <input type="text" name="meta_title" id="meta_title" autocomplete="off" value="{{$goods_without_lang->meta_title or ''}}">
                </li>
                <li>
                    <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" autocomplete="off" value="{{$goods_without_lang->meta_keywords or ''}}">
                </li>
                <li>
                    <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                    <input type="text" name="meta_description" id="meta_description" autocomplete="off" value="{{$goods_without_lang->meta_description or ''}}">
                </li>
            </ul>
        </div>
    </form>
</div>
mvjkcjvbdsfjbvjfd
@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop
