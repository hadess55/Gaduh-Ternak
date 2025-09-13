<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Evidence extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['dispute_id','type','path','sha256','uploaded_by','uploaded_at'];

    public function dispute(){ return $this->belongsTo(Dispute::class); }
    public function uploader(){ return $this->belongsTo(User::class,'uploaded_by'); }

    protected static function booted(){
        static::creating(function($e){
            if(Storage::disk('public')->exists($e->path)){
                $e->sha256 = hash('sha256', Storage::disk('public')->get($e->path));
            }
        });
    }
}

