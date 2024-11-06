<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\EventService;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::today();

        $reservedPeople = DB::table('reservations')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        ->whereNull('canceled_date')
        ->groupBy('event_id');
        // dd($reservedPeople);

        $events = DB::table('events')
        ->leftJoinSub($reservedPeople,'reservedPeople', function($join){
            $join->on('events.id', '=', 'reservedPeople.event_id');
        })
        ->whereDate('start_date', '>=', $today)
        ->orderBy('start_date', 'asc')
        ->select('events.*', 'reservedPeople.number_of_people')
        ->paginate(10);
        // dd($events);

        return view('manager.events.index',
        compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manager.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $check = EventService::checkEventDuplication(
            $request['event_date'], $request['start_time'], $request['end_time']);

        if($check){
            session()->flash('status', 'この時間帯はすでに他の予約が存在します。');
            return view('manager.events.create');
        }

        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        Event::create([
            'name' => $request['event_name'],
            'information' => $request['information'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_people' => $request['max_people'],
            'is_visible' => $request['is_visible'],
            'price' => $request['price'], // 総額の保存
            'unit_price' => $request['unit_price'], // 1人当たりの単価を保存
        ]);

        session()->flash('status', '登録OKです');

        return to_route('events.index');

    }

    public function show(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $users = $event->users;

        $reservations = [];

        foreach($users as $user)
        {
            $reservedInfo = [
                'name' => $user->name,
                'number_of_people' => $user->pivot->number_of_people,
                'canceled_date' => $user->pivot->canceled_date
            ];

            array_push($reservations, $reservedInfo);
        }

        // ログインユーザーの予約情報を取得
            $reservation = Reservation::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereNull('canceled_date')
            ->latest()
            ->first();

        // total_priceを取得（予約がある場合のみ）
        $totalPrice = $reservation ? $reservation->total_price : 0;

        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        // dd($eventDate, $startTime, $endTime);
        return view('manager.events.show',
        compact('event','users','reservations', 
        'eventDate', 'startTime', 'endTime', 'totalPrice'));
    }

    public function edit(Event $event)
    {
        $event = Event::findOrFail($event->id);

        $today = Carbon::today()->format('Y年m月d日');
        if($event->eventDate < $today){
            return abort(404);
        }

        $eventDate = $event->editEventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        return view('manager.events.edit',
        compact('event', 'eventDate', 'startTime', 'endTime'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $check = EventService::countEventDuplication(
            $request['event_date'], $request['start_time'], $request['end_time']);

        if($check > 1){
            $event = Event::findOrFail($event->id);
            $eventDate = $event->editEventDate;
            $startTime = $event->startTime;
            $endTime = $event->endTime;
    
            session()->flash('status', 'この時間帯はすでに他の予約が存在します。');
            return view('manager.events.edit',
            compact('event', 'eventDate', 'startTime', 'endTime'));
        }

        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        $event->update([
            'name' => $request['event_name'],
            'information' => $request['information'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_people' => $request['max_people'],
            'is_visible' => $request['is_visible'],
            'price' => $request['price'],         // 金額を更新
            'unit_price' => $request['unit_price'] // 1人当たりの料金を更新
        ]);

        session()->flash('status', '更新しました。');

        return to_route('events.index');

    }


    public function past()
    {
        $today = Carbon::today();

        $reservedPeople = DB::table('reservations')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        ->whereNull('canceled_date')
        ->groupBy('event_id');

        $events = DB::table('events')
        ->leftJoinSub($reservedPeople,'reservedPeople', function($join){
            $join->on('events.id', '=', 'reservedPeople.event_id');
        })
        ->whereDate('start_date', '<', $today)
        ->orderBy('start_date', 'desc')
        ->paginate(10);
        // dd($events);

        return view('manager.events.past', compact('events'));
    }


    public function destroy(Event $event)
    {
        //
    }
    
}
