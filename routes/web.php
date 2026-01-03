<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RekognitionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add-data', function () {
    return view('rekognition');
});
Route::post('/detect-faces', [RekognitionController::class, 'detectFaces'])->name('rekognition.detect');