<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// Ubah bagian ini agar responnya sesuai keinginanmu
Route::post('/publish', function (Request $request) {
    return response()->json([
        'code' => 200,
        'message' => 'Message published successfully'
    ]);
});