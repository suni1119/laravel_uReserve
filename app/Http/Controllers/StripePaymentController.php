<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Reservation;
use App\Models\Payment;

class StripePaymentController extends Controller
{
    // Stripe決済画面の作成
    public function checkout(Request $request, $id)
    {
        // 予約データを取得
        $reservation = Reservation::findOrFail($id);

        // Stripeキー設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Stripeの決済セッション作成
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $reservation->event->name,
                    ],
                    'unit_amount' => (int) $reservation->total_price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success', ['id' => $reservation->id]),
            'cancel_url' => route('stripe.cancel', ['id' => $reservation->id]),
        ]);

        // セッションURLにリダイレクト
        return redirect($session->url);
    }

    // 支払い成功時
    public function success(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['is_paid' => true]);

        // 決済履歴を保存
    Payment::create([
        'user_id' => $reservation->user_id,
        'reservation_id' => $reservation->id,
        'amount' => $reservation->total_price,
        'status' => 'paid',
        'payment_method' => 'stripe', // 必要に応じて変更
        'paid_at' => now(),
    ]);

        return view('payment.payment-success')->with('status', '支払いが完了しました！');
    }

    // 支払いキャンセル時
    public function cancel(Request $request, $id)
    {
        return view('payment.payment-failed')->with('error', '支払いがキャンセルされました。');
    }
}