@extends('app')
@include('nav-bar')
@include('left-menu')
@section('content')

    @include('speedbar')
    @include('list-elements', [
    'actions' => [
    trans('variables.elements_list') => route('posts.index'),
    trans('variables.add_element') => route('posts.create'),
    ]
    ])

    @include('alerts')




    <div class="list-content">

        <form class="form-reg" method="POST" action="{{ route('posts.store') }}">
            {{ csrf_field() }}

            <div class="part full-part" style="padding: 25px 8px;">

                <label>Category</label>
                <select class="form-control" name="category_id">
                    <option disabled>- - -</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->translation()->first()->name }}</option>
                    @endforeach
                </select>

                <label>Image</label>
                <input type="file">

            </div>


            @if (!empty($langs))

                <div class="tab-area" style="margin-top: 25px;">
                    @include('alerts')
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        @if (!empty($langs))
                            @foreach ($langs as $key => $lang)
                                <li class="nav-item">
                                    <a href="#{{ $lang->lang }}" class="nav-link  {{ $key == 0 ? ' open active' : '' }}"
                                       data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>


                @foreach ($langs as $lang)

                    <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->lang }}>
                        <div class="part left-part">

                            <ul style="padding: 25px 0;">

                                <li>
                                    <label>{{trans('variables.title_table')}}</label>
                                    <input type="text" name="title_{{ $lang->lang }}">
                                </li>

                                <li>
                                    <label for="">{{trans('variables.body')}}</label>
                                    <textarea name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}"
                                              data-type="ckeditor"></textarea>
                                    <script>
                                        CKEDITOR.replace('body-{{ $lang->lang }}', {
                                            language: '{{$lang}}',
                                        });
                                    </script>
                                </li>

                            </ul>
                        </div>


                        <div class="part right-part">
                            <ul>
                                <li>
                                    <label>URL</label>
                                    <input type="text" name="url_{{ $lang->lang }}">
                                </li>

                                <li>
                                    <label>Slug</label>
                                    <input type="text" name="slug_{{ $lang->lang }}">
                                </li>

                                <li>
                                    <label>Tags</label>
                                    <select name="tags[]" id="" multiple style="height: 80px;">
                                        <option value="">- - -</option>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->translation()->first()->name }}</option>
                                        @endforeach
                                    </select>
                                </li>

                                <li>
                                    <label>{{trans('variables.meta_title_page')}}</label>
                                    <input type="text" name="meta_title_{{ $lang->lang }}">
                                </li>

                                <li>
                                    <label>{{trans('variables.meta_keywords_page')}}</label>
                                    <input type="text" name="meta_keywords_{{ $lang->lang }}">
                                </li>

                                <li>
                                    <label>{{trans('variables.meta_description_page')}}</label>
                                    <input type="text" name="meta_description_{{ $lang->lang }}">
                                </li>
                            </ul>
                        </div>


                        <div style="margin-top: 25px;" class="part right-part">
                            <label>Tags</label>
                            <ul class="parent-tag">
                                <li class="tag-clone">
                                    <input class="" type="text" name="tags_{{ $lang->lang }}[]">
                                </li>
                                <li>
                                    <input class="" type="text" name="tags_{{ $lang->lang }}[]">
                                </li>
                            </ul>

                            <button class="btn btn-primary btn-sm add-tag">+</button>
                        </div>


                    </div>
                @endforeach
            @endif

            <input type="submit" value="{{trans('variables.save_it')}}">


        </form>
    </div>

@stop

@section('footer')
    <footer>
        @include('footer')

        <script>
            $('.add-tag').click(function (e) {
                e.preventDefault();

                $.each($('.parent-tag'), function( index, value ) {
                   $('.tag-clone').eq(index).clone().removeClass("tag-clone").appendTo($('.parent-tag').eq(index));
                });
            })
        </script>
    </footer>
@stop
