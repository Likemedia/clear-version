@extends('app')
@include('nav-bar')
@include('left-menu')

@section('content')

    @include('speedbar')


    @include('list-elements', [
        'actions' => [
            trans('variables.elements_list') => route('modules.index'),
            trans('variables.add_element') => route('modules.create'),
        ]
    ])

    @if(!empty($rows))

        <table class="el-table" id="tablelistsorter">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{trans('variables.title_table')}}</th>
                <th>{{trans('variables.edit_table')}}</th>
                <th>{{trans('variables.position_table')}}</th>
                <th>{{trans('variables.delete_table')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $key => $row)
                <tr id="{{$row->id}}">
                    <td>#{{ $key + 1 }}</td>
                    <td>
                        {{ $row->translations->first()->name ?? trans('variables.another_name')}}
                    </td>
                    <td>
                        <a href="{{ route('modules.edit', $row->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="dragHandle" nowrap style="cursor: move;">
                        <a class="top-pos" href=""></a>
                        <a class="bottom-pos" href=""></a>
                    </td>
                    <td class="destroy-element">


                        <form action="{{ route('modules.destroy', $row->id) }}" method="post">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <button type="submit" class="btn-link">
                                <a>
                                    <i class="fa fa-trash"></i>
                                </a>
                            </button>
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
