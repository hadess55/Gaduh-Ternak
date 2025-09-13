<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisputeController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/disputes/{dispute}/surat', [DisputeController::class,'surat'])->name('disputes.surat');
});
