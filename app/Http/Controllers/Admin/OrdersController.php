<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUserActionPermision;
use App\Models\Module;
use App\FrontUser;
use Illuminate\Http\Request;
use App\Models\AdminUserGroup;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Hash;
use App\Models\Order;
use App\Models\Basket;
use App\Models\BasketItem;


class OrdersController  extends Controller
{
    public function index()
    {
    	$orders = Order::get();
    	$data['orders'] = $orders;
    	return view('admin.orders.index', $data);
    }

    public function show($orderId)
    {
    	$order = Order::where('id', $orderId)->first();
    	if (is_null($order)) { return abort('404'); }

    	$basket = Basket::where('id', $order->basket_id)->first();
    	if (is_null($basket)) { return abort('404'); }

    	$basketItems = BasketItem::where('basket_id', $basket->id)
    							->get();

    	$data['basketItems'] = $basketItems;						
    	$data['order'] = $order;

    	return view('admin.orders.show', $data);
    }
}