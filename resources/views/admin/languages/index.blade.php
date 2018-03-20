@extends('app')
@include('nav-bar')
@include('left-menu')

@section('content')
@include('speedbar')

@include('list-elements', [
    'actions' => [
        trans('variables.add_element') => route('languages.create'),
    ]
])
@include('alerts')

@if(!empty($languages))
    <table class="el-table" id="tablelistsorter">
        <thead>
        <tr>
            <th>{{trans('variables.title_table')}}</th>
            <th>{{trans('variables.description')}}</th>
            <th>{{trans('variables.lang')}}</th>
            <th>{{trans('variables.delete_table')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($languages as $language)

            <tr>
              <td>{{ $language->lang }}</td>
              <td>{{ $language->descr }}</td>

              @if($language->default_lang == 1)
                <td>Default Language</td>
              @else
                <td>
                  <form action="{{ route('languages.default', $language->id) }}" method="post">
                    {{ csrf_field() }} {{ method_field('PATCH') }}
                    <button type="submit" class="btn btn-link">
                      <a><i class="fa fa-plus"></i></a></td>
                    </button>

                  </form>


              @endif

              <td class="destroy-element">
                <form action="{{ route('languages.destroy', $language->id) }}" method="post">
                  {{ csrf_field() }} {{ method_field('DELETE') }}

                  <button type="submit" class="btn btn-link"><a><i class="fa fa-trash"></i></a></button>

                </form>
              </td>


            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan=7></td>
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
