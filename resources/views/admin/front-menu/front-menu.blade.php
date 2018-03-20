@extends('app')

@include('nav-bar')

@include('left-menu')

@section('content')

<h1>Меню</h1>

<div class="dd" id="nestable3">

    <div class="list-content">
        <div class="part">

    <div class="add_element">
      <h3>Добвить елемент</h3>

      <form class="form-reg"  role="form" method="POST" action="{{ urlForLanguage($lang, 'saveitem') }}" id="add-form" enctype="multipart/form-data">
          <ul>
        @foreach($lang_list as $lang_key => $one_lang)
            <li>
                <label for="">{{$one_lang->descr }}</label>
                <input type="text" name="name_{{ $one_lang->id }}" >
            </li>
        @endforeach

            <li>
            <select name="p_id" id="p_id">
                @foreach($pages as $page)
                    <option value="{{ $page->id }}">{{ $page->name }}</option>
                @endforeach
            </select>
            </li>

            <script type="text/javascript">
                // $(".js-example-basic-multiple").select2();
            </script>

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          @if($groupSubRelations->save == 1)
            <input type="submit" value="{{trans('variables.save_it')}}" onclick="saveForm(this)" data-form-id="add-form">
          @endif

        </ul>
      </form>

    </div>

    </div>
    </div>
    <ol class="dd-list">

        {{-- first level --}}
        @foreach($frMenu as  $key => $value)
            <li class="dd-item dd3-item" data-id="{{ $value->front_menu_id }}">
                <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content"><b contenteditable="true" class="menu_name"> {{ $value->name }} </b>
                    <span class="delete-menu" data-id="{{ $value->front_menu_id }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                    <span class="edit-menu" data-id="{{ $value->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                </div>

                  {{-- second level --}}
                  @if(IfMenuHasChild($value->front_menu_id))
                   <ol class="dd-list">
                        @foreach(getMenuChilds($value->front_menu_id) as $key => $value1)
                            <li class="dd-item dd3-item" data-id="{{ $value1->front_menu_id }}">
                                <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content"><b contenteditable="true" class="menu_name"> {{ $value1->name }} </b>
                                    <span class="delete-menu" data-id="{{ $value1->front_menu_id }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                    <span class="edit-menu" data-id="{{ $value1->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                </div>

                                 {{-- thrid level --}}
                                 @if(IfMenuHasChild($value1->front_menu_id))
                                 <ol class="dd-list">
                                    @foreach(getMenuChilds($value1->front_menu_id) as $key => $value2)
                                    <li class="dd-item dd3-item" data-id="{{ $value2->front_menu_id }}">
                                        <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content"><b contenteditable="true" class="menu_name"> {{ $value2->name }} </b>
                                            <span class="delete-menu" data-id="{{ $value2->front_menu_id }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                            <span class="edit-menu" data-id="{{ $value2->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                        </div>

                                        {{-- fourth level --}}
                                        @if(IfMenuHasChild($value2->front_menu_id))
                                        <ol class="dd-list">
                                           @foreach(getMenuChilds($value2->front_menu_id) as $key => $value3)
                                           <li class="dd-item dd3-item" data-id="{{ $value3->front_menu_id }}">
                                               <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content"><b contenteditable="true" class="menu_name">{{ $value3->name }} </b>
                                                   <span class="delete-menu" data-id="{{ $value3->front_menu_id }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                   <span class="edit-menu" data-id="{{ $value3->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                               </div>

                                               {{-- fifth level --}}
                                               @if(IfMenuHasChild($value3->front_menu_id))
                                               <ol class="dd-list">
                                                  @foreach(getMenuChilds($value3->front_menu_id) as $key => $value4)
                                                  <li class="dd-item dd3-item" data-id="{{ $value4->front_menu_id }}">
                                                      <div class="dd-handle dd3-handle">Drag</div><div class="dd3-content"><b contenteditable="true" class="menu_name">{{ $value4->name }}</b>
                                                          <span class="delete-menu" data-id="{{ $value4->front_menu_id }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                          <span class="edit-menu" data-id="{{ $value4->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                                      </div>

                                                      {{-- sixth level --}}
                                                      @if(IfMenuHasChild($value4->front_menu_id))
                                                      <ol class="dd-list">
                                                         @foreach(getMenuChilds($value4->front_menu_id) as $key => $value5)
                                                         <li class="dd-item dd3-item" data-id="{{ $value5->front_menu_id }}">
                                                             <div class="dd-handle dd3-handle">Drag</div>
                                                             <div class="dd3-content"><b contenteditable="true" class="menu_name">{{ $value5->name }}</b>
                                                                 <span class="delete-menu" data-id="{{ $value5->front_menu_id }}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                                 <span class="edit-menu" data-id="{{ $value5->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                                             </div>
                                                         </li>
                                                         @endforeach
                                                      </ol>
                                                      @endif
                                                      {{-- end of sixth level --}}
                                                  </li>
                                                  @endforeach
                                               </ol>
                                               @endif
                                               {{-- end of fifth level --}}
                                           </li>
                                           @endforeach
                                        </ol>
                                        @endif
                                        {{-- end of fourth level --}}
                                    </li>
                                    @endforeach
                                 </ol>
                                 @endif
                                 {{-- end of thrid level --}}
                            </li>
                        @endforeach
                    </ol>
                    @endif
                    {{-- end of second level --}}
                </li>
            @endforeach
            {{-- end of first level --}}

        </ol>
</div>

<input type="hidden" name="json_data"  id="nestable3-output">

@stop

@section('footer')
    <footer>
        @include('footer')
    </footer>
@stop
