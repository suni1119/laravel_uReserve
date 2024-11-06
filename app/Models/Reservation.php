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
        'start_date',
        'end_date',
        'total_price'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_price' => 'float',
    ];

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
