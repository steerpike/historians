<?php

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
Route::view('/', 'questions');
Route::get('/reddit/questions', 'RedditQuestionController@index');
Route::get('/reddit/questions/latest', 'RedditQuestionController@latest');
Route::get('/reddit/show/{question}', 'RedditQuestionController@show');
Route::get('/categorise', 'RedditQuestionController@categorise');
Route::get('/reddit/thread/{question}', 'RedditThreadController@index');
Route::get('/reddit/threads', 'RedditThreadController@threads');
Route::get('/questions/{id?}', function ($id = null) {
    return  view('question', ['id'=> $id]);
});
Route::get('/php', function () {
    echo phpinfo();
    return '';
});