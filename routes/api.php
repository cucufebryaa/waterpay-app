<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pelanggan\PelangganTagihanController;

Route::post('/callback/xendit', [PelangganTagihanController::class, 'callbackXendit']);
