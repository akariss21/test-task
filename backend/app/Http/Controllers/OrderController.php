<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('products')->get();
        return OrderResource::collection($orders);
    }

    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'order_date' => $validated['order_date'],
            'status' => $validated['status'] ?? 'pending',
            'comment' => $validated['comment'] ?? null,
        ]);

        foreach ($validated['products'] as $product) {
            $order->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
            ]);
        }

        $order->load('products');

        return new OrderResource($order);
    }

    public function updateStatus($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'completed';
        $order->save();

        return response()->json(['message' => 'Success']);
    }

    public function list(Order $order)
    {
        $order->load('products');
        return new OrderResource($order);
    }
}
