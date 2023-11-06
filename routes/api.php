<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/books', 'BooksController@index');
Route::post('/books', 'BooksController@store');
Route::post('/books/{id}/reviews', 'BooksReviewController@store');
Route::delete('/books/{bookId}/reviews/{reviewId}', 'BooksReviewController@destroy');
