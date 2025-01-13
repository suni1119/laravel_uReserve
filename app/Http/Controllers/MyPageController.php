<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Services\MyPageService;
use App\Models\Payment;

class MyPageController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $events = $user->events()->whereNull('canceled_date')->get();  // userに紐づくイベント情報を取得できる
        $fromTodayEvents = MyPageService::reservedEvent($events, 'fromToday');
        $pastEvents = MyPageService::reservedEvent($events, 'past');
        $paymentHistories = Payment::whereHas('reservation', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('reservation.event')->latest()->get();        
        
        return view('mypage/index', 
        compact('fromTodayEvents', 'pastEvents', 'events', 'paymentHistories'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id); // event情報取得
        $reservation = Reservation::where('user_id', '=', Auth::id())
        ->where('event_id', '=', $id)
        ->latest() // 引数なしだとcreated_atが新しい順
        ->first();

        return view('mypage/show', compact('event', 'reservation'));
    }

    public function cancel($id)
    {
        $reservation = Reservation::where('user_id', '=', Auth::id())
        ->where('event_id', '=', $id)
        ->latest()
        ->first();

        $reservation->canceled_date = Carbon::now()->format('Y-m-d H:i:s');
        $reservation->save();

        session()->flash('status', 'キャンセルできました');

        return to_route('dashboard');
    }
}
