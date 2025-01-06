<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // 支払い画面を表示
    public function showPaymentForm(Reservation $reservation)
    {

        // キャンセル済みや無効な予約の処理を防ぐ
        if ($reservation->canceled_date) {
            return redirect()->route('mypage.index')->with('error', 'この予約は無効です');
        }

        return view('payment.payment', compact('reservation'));
    }

    // 支払い処理
    public function processPayment(Request $request)
    {
        $reservation = Reservation::findOrFail($request->reservation_id);
        $amount = $reservation->total_price;  // 合計金額を取得

        try {
            // ダミーの支払い処理（ここで実際の決済APIを呼び出す）
            DB::beginTransaction();

            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $amount,
                'status' => 'success', // 成功に設定
                'payment_method' => 'credit_card', // 仮の支払い方法
            ]);

            DB::commit();

            // 支払い成功ページへリダイレクト
            return redirect()->route('payment.success')->with('success', '支払いが成功しました！');
        } catch (\Exception $e) {
            DB::rollBack();

            // 支払い失敗ページへリダイレクト
            return redirect()->route('payment.failed')->with('error', '支払いに失敗しました: ' . $e->getMessage());
        }
    }

    // 支払い成功画面
    public function paymentSuccess()
    {
        return view('payment.payment-success');
    }

    // 支払い失敗画面
    public function paymentFailed()
    {
        return view('payment.payment-failed');
    }
}

