@extends('app')
@include('nav-bar')
@include('left-menu')

@section('content')
    @include('speedbar')

    @if($groupSubRelations->new == 1)
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, 'options'),
                'Добавить' => url($lang.'/back/front_menu/new/create')
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                'Добавить' => url($lang.'/back/front_menu/new/create')
            ]
        ])
    @endif

    <style type="text/css">
        .placeholder {
            outline: 1px dashed #4183C4;
        }
        .mjs-nestedSortable-error {
            background: #fbe3e4;
            border-color: transparent;
        }
        #tree {
            width: 550px;
            margin: 0;
        }
        ol {
            max-width: 450px;
            padding-left: 25px;
        }
        ol.sortable,ol.sortable ol {
            list-style-type: none;
        }
        .sortable li div {
            border: 1px solid #d4d4d4;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            cursor: move;
            border-color: #D4D4D4 #D4D4D4 #BCBCBC;
            margin: 0;
            padding: 3px;
        }
        li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
            border-color: #999;
        }
        .disclose, .expandEditor {
            cursor: pointer;
            width: 20px;
            display: none;
        }
        .sortable li.mjs-nestedSortable-collapsed > ol {
            display: none;
        }
        .sortable li.mjs-nestedSortable-branch > div > .disclose {
            display: inline-block;
        }
        .sortable span.ui-icon {
            display: inline-block;
            margin: 0;
            padding: 0;
        }
        .menuDiv {
            background: #EBEBEB;
        }
        .menuEdit {
            background: #FFF;
        }
        .itemTitle {
            vertical-align: middle;
            cursor: pointer;
        }
        .deleteMenu {
            float: right;
            cursor: pointer;
        }

    </style>

  <div class="content-list">
      <section id="demo">

    @if (!empty($frontMenus))
        @foreach ($frontMenus as $key => $frontMenu)

      <ol class="sortable ui-sortable mjs-nestedSortable-branch mjs-nestedSortable-expanded">
         <li style="display: list-item;" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" id="menuItem_{{ $frontMenu->front_menu_id }}">
         <div class="menuDiv">
             <span data-id="{{ $frontMenu->front_menu_id }}" class="itemTitle">a</span>
             <div id="menuEdit{{ $frontMenu->front_menu_id }}" class="menuEdit hidden">
                 <p>  {{ $frontMenu->name }}</p>
             </div>
         </div>

         {{-- first level --}}
         @if (frontMenuHasChild($frontMenu->front_menu_id, $lang_id))
             @foreach (frontMenuHasChild($frontMenu->front_menu_id, $lang_id) as $key => $firstChildMenu)
                 <ol>
                     <li style="display: list-item;" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" id="menuItem_{{ $firstChildMenu->front_menu_id }}">
                     <div class="menuDiv">
                         <span data-id="{{ $firstChildMenu->front_menu_id }}" class="itemTitle">a</span>
                         <div id="menuEdit{{ $firstChildMenu->front_menu_id }}" class="menuEdit hidden">
                             <p>  {{ $firstChildMenu->name }}</p>
                         </div>
                     </div>

                     {{-- second level --}}
                     @if (frontMenuHasChild($firstChildMenu->front_menu_id, $lang_id))
                         @foreach (frontMenuHasChild($firstChildMenu->front_menu_id, $lang_id) as $key => $secondChildMenu)
                             <ol>
                                 <li style="display: list-item;" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" id="menuItem_{{ $secondChildMenu->front_menu_id }}">
                                 <div class="menuDiv">
                                     <span data-id="{{ $secondChildMenu->front_menu_id }}" class="itemTitle">a</span>
                                     <div id="menuEdit{{ $secondChildMenu->front_menu_id }}" class="menuEdit hidden">
                                         <p>  {{ $secondChildMenu->name }}</p>
                                     </div>
                                 </div>

                                 {{-- thrid level --}}
                                 @if (frontMenuHasChild($secondChildMenu->front_menu_id, $lang_id))
                                     @foreach (frontMenuHasChild($secondChildMenu->front_menu_id, $lang_id) as $key => $thridChildMenu)
                                         <ol>
                                             <li style="display: list-item;" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" id="menuItem_{{ $thridChildMenu->front_menu_id }}">
                                             <div class="menuDiv">
                                                 <span data-id="{{ $thridChildMenu->front_menu_id }}" class="itemTitle">a</span>
                                                 <div id="menuEdit{{ $thridChildMenu->front_menu_id }}" class="menuEdit hidden">
                                                     <p>  {{ $thridChildMenu->name }}</p>
                                                 </div>
                                             </div>
                                            </li>
                                         </ol>
                                     @endforeach
                                 @endif

                                </li>
                             </ol>
                         @endforeach
                     @endif

                    </li>
                 </ol>
             @endforeach
         @endif
        </li>

     </ol>

     @endforeach
    @endif

      <h3>Try the custom methods:</h3>

      <p><br>
      <input id="serialize" name="serialize" type="submit" value=
      "Serialize"></p>
      <pre id="serializeOutput">
      </pre>

      <p><input id="toArray" name="toArray" type="submit" value=
      "To array"></p>
      <pre id="toArrayOutput">
      </pre>

      <p><input id="toHierarchy" name="toHierarchy" type="submit" value=
      "To hierarchy"></p>
      <pre id="toHierarchyOutput">
      </pre>

      <p><em>Note: This demo has the <code>maxLevels</code> option set to '4'.</em></p>
  </section><!-- END #demo -->
  </div>

  <script>

      $().ready(function(){
         

          $('.expandEditor').attr('title','Click to show/hide item editor');
          $('.disclose').attr('title','Click to show/hide children');
          $('.deleteMenu').attr('title', 'Click to delete item.');

          $('.disclose').on('click', function() {
              $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
              $(this).toggleClass('ui-icon-plusthick').toggleClass('ui-icon-minusthick');
          });

          $('.expandEditor, .itemTitle').click(function(){
              var id = $(this).attr('data-id');
              $('#menuEdit'+id).toggle();
              $(this).toggleClass('ui-icon-triangle-1-n').toggleClass('ui-icon-triangle-1-s');
          });

          $('.deleteMenu').click(function(){
              var id = $(this).attr('data-id');
              $('#menuItem_'+id).remove();
          });

          $('#serialize').click(function(){
              serialized = $('ol.sortable').nestedSortable('serialize');
              $('#serializeOutput').text(serialized+'\n\n');
          })

          $('#toHierarchy').click(function(e){
              hiered = $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});
              hiered = dump(hiered);
              (typeof($('#toHierarchyOutput')[0].textContent) != 'undefined') ?
              $('#toHierarchyOutput')[0].textContent = hiered : $('#toHierarchyOutput')[0].innerText = hiered;
          })

          $('#toArray').click(function(e){
              arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
              arraied = dump(arraied);
              (typeof($('#toArrayOutput')[0].textContent) != 'undefined') ?
              $('#toArrayOutput')[0].textContent = arraied : $('#toArrayOutput')[0].innerText = arraied;
          });
      });

      function dump(arr,level) {
          var dumped_text = "";
          if(!level) level = 0;

          //The padding given at the beginning of the line.
          var level_padding = "";
          for(var j=0;j<level+1;j++) level_padding += "    ";

          if(typeof(arr) == 'object') { //Array/Hashes/Objects
              for(var item in arr) {
                  var value = arr[item];

                  if(typeof(value) == 'object') { //If it is an array,
                      dumped_text += level_padding + "'" + item + "' ...\n";
                      dumped_text += dump(value,level+1);
                  } else {
                      dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
                  }
              }
          } else { //Strings/Chars/Numbers etc.
              dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
          }
          return dumped_text;
      }

  </script>
@stop
