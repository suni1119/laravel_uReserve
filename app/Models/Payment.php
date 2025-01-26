<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_id',
        'amount',
        'status',
        'payment_method',
        'paid_at',
    ];

    protected $dates = [
        'paid_at', // 日付として扱うフィールド
    ];

    // リレーション：支払いは1つの予約に属する
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    
}
