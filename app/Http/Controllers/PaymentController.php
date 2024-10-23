<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $reservation = Reservation::findOrFail($request->reservation_id);
        $amount = $reservation->event->price; // イベント価格を取得する

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try{
            // stripeで支払いを作成
            $charge = Charge::create([
                'amount' => $amount * 100, // stripeは金額をセント単位で支払う
                'currency' => 'jpy',
                'source' => $request->stripeToken,
                'description' => 'Reservation Payment for Event ID: ' . $reservation->event_id,
            ]);

            // 支払いが成功した場合、Paymentsテーブルに保存
            DB::beginTransaction();

            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'amount' => $amount,
                'status' => 'success',
                'payment_method' => 'stripe',
            ]);
            
            DB::commit();

            return redirect()->route('reservation.success')->with('success', 'Payment Successfull!');

        }catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('reservation.failed')->with('error', 'Payment Failed: ' . $e->getMessage());
            }
        }
}

