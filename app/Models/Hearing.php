<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hearing extends Model
{
    protected $fillable = ['dispute_id','mediator_id','scheduled_at','place','result','minutes'];
    protected $casts = ['scheduled_at'=>'datetime'];
    public function dispute(){ return $this->belongsTo(Dispute::class); }
    public function mediator(){ return $this->belongsTo(User::class,'mediator_id'); }
}
