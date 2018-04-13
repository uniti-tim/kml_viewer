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

Route::get('/',"PagesController@home");

Route::post('upload/kml',"KMLManager@upload");
Route::get('generator/excel',"GeneratorController@excel");
Route::get('editor',"PagesController@editor");

Route::post('editor/submit',"EditorController@submitSingle");
Route::post('editor/bulk/submit',"EditorController@submitBulk");
