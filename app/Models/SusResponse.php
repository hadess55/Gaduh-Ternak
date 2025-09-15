<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/SusResponse.php
class SusResponse extends Model
{
    protected $fillable = ['dispute_id','user_id','answers','score','submitted_at'];
    protected $casts = ['answers'=>'array','submitted_at'=>'datetime'];

    public static function calcScore(array $answers): int {
        // SUS: item ganjil (1,3,5,7,9) => x-1; genap (2,4,6,8,10) => 5-x; sum*2.5
        $sum = 0;
        for ($i=0;$i<10;$i++) {
            $x = (int)($answers[$i] ?? 3);
            $sum += ($i % 2 === 0) ? ($x - 1) : (5 - $x);
        }
        return (int) round($sum * 2.5);
    }

    protected static function booted() {
        static::saving(function ($m) {
            $m->score = self::calcScore($m->answers ?? []);
            $m->submitted_at = $m->submitted_at ?? now();
        });
    }

    public function dispute(){ return $this->belongsTo(Dispute::class); }
    public function user(){ return $this->belongsTo(User::class); }
}

