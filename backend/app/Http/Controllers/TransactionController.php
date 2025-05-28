<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionDepositRequest;
use App\Http\Requests\TransactionWithdrawRequest;
use App\Http\Requests\TransactionPurchaseRequest;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function deposit(TransactionDepositRequest $request)
    {
        $user = auth()->user();
        $amount = $request->amount;

        $user->balance += $amount;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'type'    => 'deposit',
            'amount'  => $amount,
        ]);

        return response()->json([
            'message' => 'Баланс пополнен на ' . $amount,
            'balance' => $user->balance
        ]);
    }

    public function withdraw(TransactionWithdrawRequest $request)
    {
        $user = auth()->user();
        $amount = $request->amount;

        if ($user->balance < $amount) {
            return response()->json(['message' => 'Недостаточно средств'], 400);
        }

        $user->balance -= $amount;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'type'    => 'withdrawal',
            'amount'  => -$amount,
        ]);

        return response()->json([
            'message' => 'Вывод произведен',
            'balance' => $user->balance
        ]);
    }

    public function purchase(TransactionPurchaseRequest $request)
    {
        $user = auth()->user();
        $order = Order::with('products.user')->findOrFail($request->order_id);

        if ($order->isCompleted()) {
            return response()->json(['message' => 'Заказ уже завершен'], 400);
        }
        
        $totalAmount = 0;
        foreach ($order->products as $product) {
            $totalAmount += $product->price * $product->pivot->quantity;
        }

        if ($user->balance < $totalAmount) {
            return response()->json(['message' => 'Недостаточно средств'], 400);
        }

        DB::transaction(function () use ($order, $user, $totalAmount) {
            foreach ($order->products as $product) {
                $quantity = $product->pivot->quantity;

                if ($product->quantity < $quantity) {
                    throw new \Exception("Недостаточно товара {$product->name}");
                }

                $product->quantity -= $quantity;
                $product->save();

                $seller = $product->user;
                $sellerIncome = $product->price * $quantity * 0.9;
                $seller->balance += $sellerIncome;
                $seller->save();

                Transaction::create([
                    'user_id'   => $user->id,
                    'type'      => 'purchase',
                    'amount'    => -$product->price * $quantity,
                    'order_id'  => $order->id,
                    'seller_id' => $seller->id,
                    'meta'      => json_encode([
                        'product_id' => $product->id,
                        'quantity'   => $quantity,
                        'price'      => $product->price,
                    ]),
                ]);
            }

            $user->balance -= $totalAmount;
            $user->save();

            $order->status = 'completed';
            $order->save();
        });

        return response()->json([
            'message'        => 'Покупка успешно завершена',
            'buyer_balance'  => $user->balance,
            'sellers'        => $order->products->map(fn($p) => [
                'seller_id'     => $p->user->id,
                'seller_balance'=> $p->user->balance,
                'product_id'    => $p->id,
            ]),
            'total_price'    => $totalAmount,
        ]);
    }
}
