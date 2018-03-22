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

    @if(!$posts->isEmpty())

        <table class="el-table" id="tablelistsorter">
            <thead>
            <tr>
                <th>{{trans('variables.title_table')}}</th>
                <th>{{trans('variables.body')}}</th>
                <th>Slug</th>
                <th>URL</th>
                <th>Tags</th>
                <th>{{trans('variables.edit_table')}}</th>
                <th>{{trans('variables.delete_table')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)

                <tr>
                    <td>
                        {{ $post->translation()->first()->title ?? trans('variables.another_name')}}
                    </td>
                    <td>
                        {!! str_limit($post->translation()->first()->body, 100) !!}
                    </td>
                    <td>
                        {{ $post->translation()->first()->slug }}
                    </td>
                    <td>
                        {{ $post->translation()->first()->url }}
                    </td>
                    <td>
                        // tags
                    </td>
                    <td>
                        <a href="{{ route('posts.edit', $post->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('posts.destroy', $post->id) }}" method="post">
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
