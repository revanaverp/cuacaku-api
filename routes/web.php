<?php

use Illuminate\Support\Facades\Route;

Route::get('/cuaca', function () {
    return view('weather');
});
