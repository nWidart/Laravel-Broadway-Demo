<?php

/*
|--------------------------------------------------------------------------
| Event sourced child entity
|--------------------------------------------------------------------------
*/
Route::get('/parts/index', ['as' => 'parts.index', 'uses' => 'Modules\Parts\Http\Controllers\PartsController@index']);
Route::post('/parts/store', ['as' => 'parts.store', 'uses' => 'Modules\Parts\Http\Controllers\PartsController@store']);

Route::get('/parts/manufacture', 'Modules\Parts\Http\Controllers\PartsController@manufacture');
Route::get('/parts/rename/{partId}/{name}', 'Modules\Parts\Http\Controllers\PartsController@renameManufacturer');
