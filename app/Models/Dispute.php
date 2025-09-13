<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
        'reporter_id','defendant_id','location','occurred_at',
        'description','legal_route','status','damage_estimate','legal_basis_id'
    ];

    protected $casts = ['occurred_at' => 'datetime'];

    public function reporter(){ return $this->belongsTo(User::class, 'reporter_id'); }
    public function defendant(){ return $this->belongsTo(User::class, 'defendant_id'); }
    public function legalBasis(){ return $this->belongsTo(LegalBasis::class); }
    public function evidences(){ return $this->hasMany(Evidence::class); }
    public function actions(){ return $this->hasMany(Action::class); }
    public function settlement(){ return $this->hasOne(Settlement::class); }
}

