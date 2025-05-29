<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;
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
            'user_id' => Auth::id(),
            'order_date' => $validated['order_date'],
            'status' => $validated['status'] ?? 'new',
            'comment' => $validated['comment'] ?? null,
        ]);

        foreach ($validated['products'] as $productData) {
            $product = Product::find($productData['id']);
            if (!$product) {
                // Можно выбросить исключение или пропустить
                return response()->json(['error' => "Product with id {$productData['id']} not found."], 422);
            }
            $order->products()->attach($product->id, [
                'quantity' => $productData['quantity'],
            ]);
        }

        $order->load('products');

        return (new OrderResource($order))->response()->setStatusCode(201);
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
