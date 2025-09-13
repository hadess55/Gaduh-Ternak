<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
     protected $fillable = [
        'nama','nik','telp','alamat','desa','kecamatan',
        'jenis_ternak','jumlah_ternak','status','validated_by','validated_at','catatan'
    ];
    protected $casts = ['validated_at'=>'datetime'];
    public function validator(){ return $this->belongsTo(User::class,'validated_by'); }
}
