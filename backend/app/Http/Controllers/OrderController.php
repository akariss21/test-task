<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="Get all orders",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of orders",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/OrderResource")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $orders = Order::with('products')->get();
        return OrderResource::collection($orders);
    }

    
    /**
     * @OA\Post(
     *     path="/orders",
     *     summary="Create a new order",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrderRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *     ),
     *     @OA\Response(response=422, description="Validation error or product not found")
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/orders/{id}/complete",
     *     summary="Mark order as completed",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Order marked as completed"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function updateStatus($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'completed';
        $order->save();

        return response()->json(['message' => 'Success']);
    }

    /**
     * @OA\Get(
     *     path="/orders/{order}",
     *     summary="Get order by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(ref="#/components/schemas/OrderResource")
     *     ),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function list(Order $order)
    {
        $order->load('products');
        return new OrderResource($order);
    }
}
