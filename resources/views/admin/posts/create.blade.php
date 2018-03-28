@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')

    @include('admin.speedbar')
    @include('admin.list-elements', [
    'actions' => [
    trans('variables.elements_list') => route('posts.index'),
    trans('variables.add_element') => route('posts.create'),
    ]
    ])

    @include('admin.alerts')




    <div class="list-content">

        <form class="form-reg" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
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
                <input type="file" name="image">

            </div>


            @if (!empty($langs))

                <div class="tab-area" style="margin-top: 25px;">
                    @include('admin.alerts')
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
                                     <input type="text" name="title_{{ $lang->lang }}"
                                    class="name"
                                    data-lang="{{ $lang->lang }}">
                                </li>

                                <li>
                                    <label for="">{{trans('variables.body')}}</label>
                                    <textarea name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}"
                                              data-type="ckeditor"></textarea>
                                    <script>
                                        CKEDITOR.replace('body-{{ $lang->lang }}', {
                                            language: '{{$lang->lang}}',
                                        });
                                    </script>
                                </li>

                            </ul>
                        </div>


                        <div class="part right-part">
                            <ul>
                                <li>
                                    <label>URL</label>
                                    <input type="text" name="url_{{ $lang->lang }}"
                                           class="slug form-control"
                                           id="slug-{{ $lang->lang }}">
                                </li>

                                <li>
                                    <label>Slug</label>
                                    <input class="slug"  type="text" name="slug_{{ $lang->lang }}">

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

                            <li>
                                @foreach($tags as $tag)
                                    @if($tag->lang_id == $lang->id)
                                        <input type="checkbox" name="tags_{{ $lang->lang }}[]" value="{{ $tag->name }}">{{ $tag->name }}
                                    @endif
                                @endforeach
                            </li>


                            <ul>
                                <button class="btn btn-primary btn-sm tag">+</button>

                                <input type="text" name="tag_{{ $lang->lang }}[]" class="tag_{{ $lang->lang }}" />

                            </ul>
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
        @include('admin.footer')

        <script>

            $('button.tag').click(function(e) {
                e.preventDefault();

                $input = $(this).siblings().last().clone().val('');
                $(this).parent().append($input);
            });

        </script>
    </footer>
@stop
