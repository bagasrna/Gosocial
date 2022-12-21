<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\ArtisanController;

# Users
Route::get('/users', [UserController::class, 'index']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/user/create', [UserController::class, 'save']);
Route::put('/user/update', [UserController::class, 'save']);
Route::delete('/user/delete', [UserController::class, 'delete']);

# Jobs
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/job/{id}', [JobController::class, 'show']);
Route::post('/job/create', [JobController::class, 'save']);
Route::put('/job/update', [JobController::class, 'save']);
Route::delete('/job/delete', [JobController::class, 'delete']);

# Artisan Call
Route::get('/optimize', [ArtisanController::class, 'optimize']);
Route::get('/fresh', [ArtisanController::class, 'fresh']);
