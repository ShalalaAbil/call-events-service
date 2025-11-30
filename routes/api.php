<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallEventController;



Route::middleware('api.token')->group(function () {
    Route::post('/call-events', [CallEventController::class, 'store']);
});
