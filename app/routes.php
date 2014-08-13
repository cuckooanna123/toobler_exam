<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});





Route::controller('admin', 'AdminController');
Route::controller('adminbase', 'AdminbaseController');

//Route::resource('languages', 'LanguagesController');


Route::get('admin','AdminController@getLogin'); //base route of admin

// routes for languages controller
Route::get('languages/list','LanguagesController@showIndex'); //lanuage list
Route::get('languages/create','LanguagesController@getCreate'); // add language form
Route::post('languages/add','LanguagesController@addLanguage'); // save lanuage
Route::get('languages/edit/{id}','LanguagesController@getEdit'); // load edit form
Route::post('languages/update','LanguagesController@updateLanguage'); // save lanuage
Route::post('languages/delete','LanguagesController@deleteLanguage'); // save lanuage

// routes for questions controller
Route::get('questions/add','QuestionsController@addQuestion');
Route::post('questions/fetch','QuestionsController@fetchLanguages');
Route::post('questions/save','QuestionsController@saveQuestion');
Route::get('questions/edit/{id}','QuestionsController@editQuestion');
Route::get('questions/list/{id}','QuestionsController@getList');
Route::post('questions/delete','QuestionsController@deleteQuestion');
Route::post('questions/update','QuestionsController@questionUpdate');

//routes for user controller
Route::get('users/login','UserController@getLogin');
Route::post('users/signin','UserController@postSignin');
Route::get('users/start','UserController@getStartpage');
Route::get('users/exam/{id}','UserController@getExamPage');
Route::get('users/nextQues','UserController@postNextques');
