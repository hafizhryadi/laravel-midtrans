<?php

use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home.index');});
Route::resource('/donations', DonationController::class, ['only'=> ['index', 'create', 'store']]);