<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionController extends Controller
{
    public function deposit(Request $request)
    {
        $user = auth()->user();
        $amount = $request['amount'];

        $user->balance += $amount;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $amount,
        ]);

        return response()->json(['message' => 'Баланс пополнен на ' . $amount, 'balance' => $user->balance]);
    }

    public function withdraw(Request $request)
    {
        $user = auth()->user();
        $amount =  $request['amount'];

        if ($user->balance < $amount) {
            return response()->json(['message' => 'Недостаточно средств'], 400);
        }

        $user->balance -= $amount;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => -$amount,
        ]);

        return response()->json(['message' => 'Вывод произведен', 'balance' => $user->balance]);
    }

    public function purchase(Request $request)
    {
        $user = auth()->user();
        $orderId = $request['order_id'];
        $order = Order::with('product.user')->findOrFail($orderId);

        $product = $order->product;
        $quantity = $order->quantity;
        $amount = $product->price * $quantity;

        if ($user->balance < $amount) {
            return response()->json(['message' => 'Недостаточно средств'], 400);
        }

        DB::transaction(function () use ($order, $product, $user, $quantity, $amount) {
            if ($product->quantity < $quantity) {
                throw new \Exception("Недостаточно товара {$product->name}");
            }

            // Обновляем остаток товара
            $product->quantity -= $quantity;
            \Log::info('Обновление продукта', ['quantity' => $product->quantity]);
            $product->save();

            // Обновляем покупателя
            $user->balance -= $amount;
            \Log::info('Обновление пользователя', ['balance' => $user->balance]);
            $user->save();

            // Обновляем продавца
            $seller = $product->user;
            $seller->balance += $amount * 0.9;
            \Log::info('Обновление продавца', ['balance' => $seller->balance]);
            $seller->save();

            // Завершаем заказ
            $order->status = 'completed';
            $order->save();

            // Создаём транзакцию
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'purchase',
                'amount' => -$amount,
                'order_id' => $order->id,
                'seller_id' => $seller->id,
                'meta' => json_encode([
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]),
            ]);
        });

        return response()->json(['message' => 'Покупка совершена', 'balance' => $user->balance]);
    }
}