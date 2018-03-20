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
            trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart')
        ]
    ])
@else
    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
            trans('variables.elements_basket') => urlForLanguage($lang, 'menucart')
        ]
    ])
@endif

<div class="list-content">
    <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'saveitem') }}" id="add-form" enctype="multipart/form-data">

        <div class="part left-part">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul>

                <li style="margin-top: 10px">
                    <label for="p_id">{{trans('variables.p_id_name')}}</label>
                    <select name="p_id" id="p_id" class="chosen-select">
                        {!! SelectGoodsItemTree($lang_id, 0 ,$curr_page_id) !!}
                    </select>
                </li>
                <li>
                    <label for="name">{{trans('variables.title_table')}}</label>
                    <input type="text" name="name" id="name">
                </li>

                <li>
                    <label for="descr">{{trans('variables.short_description')}}</label>
                    <textarea id="descr" name="short_descr" cols="92" rows="15"></textarea>
                </li>
                <li>
                    <label for="body">{{trans('variables.body')}}</label>
                    <textarea name="body" id="body" data-type="ckeditor"></textarea>
                    <script>
                        CKEDITOR.replace( 'body', {
                            language: '{{$lang}}',
                        } );
                    </script>
                </li>
                <li>
                    <label for="price">Цена</label>
                    <input type="text" id="price" name="price" value="0">
                </li>

                <li>
                    <label for="old_price">Старая цена</label>
                    <input type="text" id="old_price" name="old_price" value="0">
                </li>

                <li>
                    <label for="discount">Скидка</label>
                    <select id="discount" name="discount">
                        @if(!is_null($discount))
                            @foreach(json_decode($discount->value) as $value)
                                <option value="{{ $value }}">{{ $value }} %</option>
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
                            <select id="param-{{ $key }}" name="value[{{ $param->id }}]">
                                    <option value="null">Не выбрано</option>
                                @if(!is_null($discount))
                                    @foreach(json_decode($param->value) as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
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
                            <option value="{{$one_lang->id}}" {{$one_lang->id == $lang_id ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                        @endforeach
                    </select>
                </li>
                <li>
                    <label for="alias">{{trans('variables.alias_table')}}</label>
                    <input type="text" name="alias" id="alias">
                </li>
                <li>
                   <label for="active">
                   <input class="checkbox square" type="checkbox" name="active" id="active" checked>
                   <span>Истории</span>
                   </label>
               </li>
            </ul>
            @if($groupSubRelations->save == 1)
                <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
            @endif

            <ul>
                <hr><h6>SEO Текста</h6>
                <li>
                    <label for="h1_title">{{trans('variables.h1_title_page')}}</label>
                    <input type="text" name="h1_title" id="h1_title" autocomplete="off">
                </li>
                <li>
                    <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                    <input type="text" name="meta_title" id="meta_title" autocomplete="off">
                </li>
                <li>
                    <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" autocomplete="off">
                </li>
                <li>
                    <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                    <input type="text" name="meta_description" id="meta_description" autocomplete="off">
                </li>
            </ul>
        </div>


        <div class="part col-md-12">
            <ul>

            </ul>
        </div>
    </form>
</div>
@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop
