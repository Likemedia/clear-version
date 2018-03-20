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

    @if(!is_null($order))
    
    <div class="list-content">
        <form class="form-reg">
            <div class="part col-md-6">    
                <ul>
                    <li class="row">
                        <div class="col-md-4"><label class="text-muted">Дата</label></div>
                        <div class="col-md-8"><label>{{ $order->created_at }}</label></div>
                    </li>
                    <li class="row">
                        <div class="col-md-4"><label class="text-muted">Имя пользователя</label></div>
                        <div class="col-md-8"><label>{{ $order->orderUser->name }}</label></div>
                    </li>
                    <li class="row">
                        <div class="col-md-4"><label class="text-muted">Сумма заказа</label></div>
                        <div class="col-md-8"><label>{{ $order->amount }}</label></div>
                    </li>
                    <li class="row">
                        <div class="col-md-4"><label class="text-muted">Колличество товаров</label></div>
                        <div class="col-md-8"><label>{{ count($order->orderBasket->basketItems) }}</label></div>
                    </li>
                    <li class="row">
                        <div class="col-md-4"><label class="text-muted">Адрес</label></div>
                        <div class="col-md-8"><label>{{ $order->address }}</label></div>
                    </li>
                </ul>
            </div>
            <div class="col-md-1"></div>
            <div class="part col-md-5">    
                <ul>
                    <li class="row">
                        <div class="col-md-12"><label class="text-muted">Комментарий к заказу:</label></div>
                        <div class="col-md-12"><hr><p style="line-height: 1.2;"><small>{{ $order->message }}</small></p></div>
                    </li>
                </ul>
            </div>
        </form>
    </div>

     <table class="el-table" id="tablelistsorter">
        <hr><h4 class="sub-heading"><small>Список товаров</small></h4>
            <thead>
            <tr>
                <th>ID</th>
                <th>Товар</th>
                <th>Колличество</th>
                <th>Цена</th>
                <th>Скидка</th>
                <th>Общая цена</th>
            </tr>
            </thead>
            <tbody>

            @foreach($basketItems as $key => $item)
                <tr id="{{ $order->id }}">
                    <td>#{{ $key + 1 }}</td>
                    <td>{{ getGoodName($item->basketGoodId->id, $lang_id) }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->discount }}%</td>
                    <td>{{ number_format(($item->price*$item->qty) - ($item->price*$item->qty*($item->discount / 100)), 2, '.', '') }}</td>
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