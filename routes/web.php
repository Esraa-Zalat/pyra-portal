<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/about-us', function () {
    return view('about');

})->name('about');

Route::get('/school', function () {
    return view('school');
})->name('school');

Route::get('/teachers', function () {
    return view('teacher');
})->name('teacher');

Route::get('/parents', function () {
    return view('parent');
})->name('parent
');

Route::get('/students', function () {
    return view('student');
})->name('student
');

Route::get('/my-company', function () {
    return view('company');
})->name('company
');

Route::get('/my-nursery', function () {
    return view('nursery');
})->name('nursery
');

Route::get('/shops', function () {
    return view('shop');
})->name('shop
');