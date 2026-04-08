<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::prefix('admin')->middleware([Authenticate::class, AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    Route::get('/books', [AdminController::class, 'booksIndex']);
    Route::get('/books/create', [AdminController::class, 'booksCreate']);
    Route::post('/books/store', [AdminController::class, 'booksStore']);
    Route::get('/books/{id}/edit', [AdminController::class, 'booksEdit']);
    Route::put('/books/{id}/update', [AdminController::class, 'booksUpdate']);
    Route::delete('/books/{id}/delete', [AdminController::class, 'booksDestroy']);

    Route::get('/users', [AdminController::class, 'usersIndex']);
    Route::get('/users/create', [AdminController::class, 'usersCreate']);
    Route::post('/users/store', [AdminController::class, 'usersStore']);
    Route::get('/users/{id}/edit', [AdminController::class, 'usersEdit']);
    Route::put('/users/{id}/update', [AdminController::class, 'usersUpdate']);
    Route::delete('/users/{id}/delete', [AdminController::class, 'usersDestroy']);

    Route::get('/transactions', [AdminController::class, 'transactionsIndex']);
    Route::get('/transactions/create', [AdminController::class, 'transactionsCreate']);
    Route::post('/transactions/store', [AdminController::class, 'transactionsStore']);
    Route::get('/transactions/{id}/edit', [AdminController::class, 'transactionsEdit']);
    Route::put('/transactions/{id}/update', [AdminController::class, 'transactionsUpdate']);
    Route::delete('/transactions/{id}/delete', [AdminController::class, 'transactionsDestroy']);
});

Route::prefix('user')->middleware([Authenticate::class, UserMiddleware::class])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/books', [UserController::class, 'books']);
    Route::post('/borrow', [UserController::class, 'borrow']);
    Route::post('/return', [UserController::class, 'returnBook']);
});
