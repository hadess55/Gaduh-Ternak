<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\PublicFarmerController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/pendaftaran-peternak', [PublicFarmerController::class,'create'])->name('farmer.public.create');
Route::post('/pendaftaran-peternak', [PublicFarmerController::class,'store'])->name('farmer.public.store');

Route::middleware(['auth'])->group(function(){
    Route::get('/disputes/{dispute}/surat', [DisputeController::class,'surat'])->name('disputes.surat');
});
