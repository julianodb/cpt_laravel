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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oc_list_queries', 'OcListQueryController@index')->name('oc_list_queries');
Route::post('/oc_list_queries','OcListQueryController@store');
Route::get('/oc_item_queries', 'OcItemQueryController@index')->name('oc_item_queries');
Route::post('/oc_item_queries','OcItemQueryController@store');

Route::resource('ocs', 'OrdenCompraController');

