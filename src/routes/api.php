<?php

use App\Http\Controllers\FlyerController;
use Illuminate\Support\Facades\Route;

Route::post('/hugeApiPayload',
    [FlyerController::class, 'store']
);
