@extends('app')
@include('nav-bar')
@include('left-menu')
 
@section('content')
    @include('speedbar')
    
    @if($groupSubRelations->new == 1)
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, 'options'),
            ]
        ])
    @else
        @include('list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            ]
        ])
    @endif

    @if(!empty($orders))
    
     <table class="el-table" id="tablelistsorter">
            <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>Имя пользователя</th>
                <th>Сумма заказа</th>
                <th>Колличество товаров</th>
                <th>Просмотреть</th>

                @if($groupSubRelations->del_to_rec == 1)
                    <th>{{trans('variables.delete_table')}}</th>
                @endif
            </tr>
            </thead>
            <tbody>

            @foreach($orders as $key => $order)
                <tr id="{{ $order->id }}">
                    <td>#{{ $order->basket_id }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>{{ $order->orderUser->name }}</td>
                    <td>{{ $order->amount }}</td>
                    <td>{{ count($order->orderBasket->basketItems) }}</td>
                    <td>
                       <a href="{{ url($lang.'/back/orders/list/show/'.$order->id) }}"><i class="fa fa-sign-in"></i></a>
                    </td>
                    @if($groupSubRelations->del_to_rec == 1)
                        <td class="destroy-element"><a href=""><i class="fa fa-trash"></i></a></td>
                    @endif
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