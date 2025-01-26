<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'number_of_people',
        'canceled_date',
        'total_price',
        'is_paid',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_price' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function event()
    {
        return $this->belongsTo(Event::class);
    }


    public function payment()
    {
        return $this->hasOne(Payment::class);
    }


    public function getTotalPriceAttribute($value)
    {
        // total_priceがすでに設定されていればそれを返す
        if ($value) {
            return $value;
        }

        
        // 設定されていなければ計算する（例：eventのunit_price * number_of_people）
        return $this->event ? $this->event->unit_price * $this->number_of_people : null;
    }
}
