<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;

Route::get('/', [TicketController::class, 'index'])->name('dashboard');
Route::resource('tickets', TicketController::class);
Route::post('tickets/{ticket}/comments', [CommentController::class, 'store'])->name('tickets.comments.store');
