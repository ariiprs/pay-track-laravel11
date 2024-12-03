<?php

use App\Http\Controllers\DebtController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

Route::get('/browse/{category:slug}', [FrontController::class, 'category'])->name('front.category');

Route::get('/details/{debt:customer_id}', [FrontController::class, 'details'])->name('front.details');

Route::get('/details/{debt:customer_id}', [FrontController::class, 'chooseOrder'])->name('front.choose_order');
Route::post('/order/begin/{customer:slug}', [DebtController::class, 'saveDebtCust'])->name('front.save_debtcust');

Route::get('/order/booking', [DebtController::class, 'booking'])->name('front.booking');
Route::post('/order/booking/data-debt/save', [DebtController::class, 'saveDataDebt'])->name('front.datadebt');

Route::get('/order/finished/{debt:id}', [DebtController::class, 'orderFinished'])->name('front.confirmation');
