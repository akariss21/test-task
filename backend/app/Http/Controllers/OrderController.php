<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function index() {
        $orders = Order::with('product')->get();

        $orders->transform(function ($order) {
            $order->total_price = $order->quantity * $order->product->price;
            return $order;
        });

        return response()->json($orders);
    }
    public function list(Order $order) {
        return response()->json($order->load('product'));
    }
    public function store(Request $request) {
        $order = Order::create($request->all());
        return response()->json($order, 201);
    }
    public function updateStatus($id) {
        $order = Order::findOrFail($id);
        $order->status = 'Completed';
        $order->save();
        return response()->json(['message' => 'Success']);
    }

}
