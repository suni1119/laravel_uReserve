<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showPaymentForm($reservation)
    {
        $reservation = Reservation::findOrFail($reservation);

    if ($reservation->canceled_date) {
        return redirect()->route('mypage.index')->with('error', 'この予約は無効です');
    }

    return view('payment.payment', compact('reservation'));
    }

    // Stripeの決済ページを作成
    public function checkout(Request $request)
    {
        // イベントと予約人数を取得
        $event = Event::findOrFail($request->id);
        $quantity = $request->reserved_people; // 予約人数

        // Stripe APIキー設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Stripeの決済セッション作成
        $session = Session::create([
            'payment_method_types' => ['card'], // 支払い方法: カード
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy', // 日本円
                    'product_data' => [
                        'name' => $event->name, // イベント名
                    ],
                    'unit_amount' => $event->unit_price * 100, // 最小単位で指定
                ],
                'quantity' => $quantity, // 予約人数
            ]],
            'mode' => 'payment', // 支払いモード
            'success_url' => route('stripe.success'), // 成功時URL
            'cancel_url' => route('stripe.cancel'),   // キャンセル時URL
        ]);

        // Stripeの支払いページにリダイレクト
        return redirect($session->url);
    }

    // 支払い成功時の処理
    public function success()
    {
        return view('payment.payment-success')->with('status', '支払いが完了しました！');
    }

    // 支払いキャンセル時の処理
    public function cancel()
    {
        return view('payment.payment-failed')->with('error', '支払いがキャンセルされました。');
    }
}
