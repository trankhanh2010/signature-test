<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// routes/web.php
// Login
Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.authenticate');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/upload', [DocumentController::class, 'uploadForm'])->name('upload.form');
Route::post('/upload', [DocumentController::class, 'upload'])->name('upload');
Route::get('/upload_sig', [DocumentController::class, 'uploadForm_sig'])->name('upload_sig.form');
Route::post('/upload_sig', [DocumentController::class, 'upload_sig'])->name('upload_sig');
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/documents/{id}/sign', [DocumentController::class, 'sign'])->name('documents.sign');
Route::get('/documents/{id}/view/{path0}', [DocumentController::class, 'view'])->name('documents.view');

