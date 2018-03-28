<li class="{{ request()->segment(2) == $m->src  ? 'active' : ''}}" >
    <a class="drop-down" href="{{ url('/back/'.$m->src) }}">
      <i class="fa {{ $m->icon }}"></i> {{ $m->translation()->first()->name ?? '' }} <i class="fa arrow"></i>
    </a>
    <ul class="drop-hd">
        @if (!empty($categories = SelectCatsTree(1, 0)))
          @foreach ($categories as $key => $category)
            @if (count(SelectCatsTree(1, $category->id)) > 0)
            <li>
                <a class="drop-down" href="{{ route('posts.category', [$category->category_id]) }}">> {{ $category->name }} <i class="fa {{ !empty($categories) ? 'arrow' : '' }}"></i></a>
                <ul class="drop-hd">
                    @foreach (SelectCatsTree(1, $category->category_id) as $key => $category)
                      @if (count(SelectCatsTree(1, $category->id)) > 0)
                      <li>
                          <a class="drop-down" href="{{ route('posts.category', [$category->category_id]) }}">>> {{ $category->name }}<i class="fa arrow"></i></a>
                          <ul class="drop-hd">
                              @foreach (SelectCatsTree(1, $category->category_id) as $key => $category)
                              <li><a href="{{ route('posts.category', [$category->category_id]) }}">>>> {{ $category->name }} </a> </li>
                              @endforeach
                          </ul>
                      </li>
                      @else
                      <li><a  href="{{ route('posts.category', [$category->category_id]) }}">>> {{ $category->name }}</a></li>
                      @endif
                    @endforeach
                </ul>
            </li>
            @endif
          @endforeach
        @endif
    </ul>
</li>
<script>
    $().ready(function(){
        $('.arrow').each(function(index, value){
          $('.arrow').eq(index).parent().addClass('drop-down');
        });
    });
</script>
