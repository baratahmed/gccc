<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function printOrder($id){
        $order = Order::find($id);
        $c_notes = ClientNote::where('order_id',$order->id)->where('customer_id',$order->customer_id)->select('id','note')->get();
        $m_notes = MasterNote::where('order_id',$order->id)->where('master_id',$order->master_id)->select('id','note')->get();
        return view('print.order', compact('order','c_notes','m_notes'));
    }
}
