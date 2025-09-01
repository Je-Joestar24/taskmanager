<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/test', function (Request $request) {
    return  response()->json(app()->version());
});