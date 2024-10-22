<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['reservation_id', 'amount', 'status', 'payment_method'];

    // リレーション：支払いは1つの予約に属する
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
