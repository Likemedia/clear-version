@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')

@section('content')
    @include('admin.speedbar')

    @include('admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => route('categories.index'),
            trans('variables.add_element') => route('categories.create'),
        ]
    ])
    @include('admin.alerts')


    <div class="list-content">
        <div class="part full-part min-height">
            <h6>Список категории</h6>
            <hr>
            <div id="container">


                <a class="btn-link modal-id" data-toggle="modal" data-target="#addCategory" data-id="0"><i class="fa fa-plus"></i></a>


            </div>

            <div class="dd" id="nestable-output">

                {!! SelectGoodsCatsTree(1, 0, $curr_id=null) !!}

            </div>

            <script>

                $('#nestable-output').nestable();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    }
                });

                $(document).ready(function () {
                    var updateOutput = function (e) {
                        console.log('called');
                        var list = e.length ? e : $(e.target), output = list.data('output');

                        $.ajax({
                            method: "POST",
                            url: "{{ route('categories.change') }}",
                            data: {
                                list: list.nestable('serialize')
                            }
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            alert("Unable to save new list order: " + errorThrown);
                        });
                    };

                    $('#nestable-output').nestable({
                        group: 1,
                        maxDepth: 3,
                    }).on('change', updateOutput);
                });

                $('#container').on("changed.jstree", function (e, data) {
                    console.log("The selected nodes are:");
                    console.log(data.selected);
                });

            </script>
        </div>
    </div>

@stop

@section('footer')
    <footer>
        @include('admin.footer')
    </footer>

    <!-- Modal -->
    <div class="modal fade" id="warning" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">

        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title" id="exampleModalLabel">Warning</h5>
          </div>
          <div class="modal-body">
            Last level or has posts 
          </div>
        </div>

      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCategory" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categories.partial.save') }}" method="post">
                        {{ csrf_field() }}

                        <input type="hidden" name="parent_id" id="parent_id" value="0"/>

                        <div class="list-content">
                            <div class="tab-area">
                                @include('admin.alerts')
                                <ul class="nav nav-tabs nav-tabs-bordered">
                                    @if (!empty($langs))
                                        @foreach ($langs as $key => $lang)
                                            <li class="nav-item">
                                                <a href="#{{ $lang->lang }}"
                                                   class="nav-link  {{ $key == 0 ? ' open active' : '' }}"
                                                   data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>


                            @foreach ($langs as $lang)

                                <div class="tab-content {{ $loop->first ? ' active-content' : '' }}"
                                     id={{ $lang->lang }}>
                                    <div class="part full-part">

                                        <ul>
                                            <li>
                                                <label>{{trans('variables.title_table')}}</label>
                                                <input type="text" name="name_{{ $lang->lang }}"
                                                       class="name form-control"
                                                       data-lang="{{ $lang->lang }}">
                                            </li>
                                            <li>
                                                <label>Slug</label>
                                                <input type="text" name="slug_{{ $lang->lang }}"
                                                       class="slug form-control"
                                                       id="slug-{{ $lang->lang }}">
                                            </li>
                                            <li>
                                                <input style="margin-top: 10px;" type="submit" class="btn btn-primary" value="{{trans('variables.save_it')}}">
                                            </li>

                                        </ul>
                                    </div>

                                </div>
                        @endforeach

                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        $('.modal-id').click(function () {
            console.log($(this).data('id'));
            $('#parent_id').val($(this).data('id'));
        })
    </script>


@stop
