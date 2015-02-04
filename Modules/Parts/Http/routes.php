<?php

/*
|--------------------------------------------------------------------------
| Event sourced child entity
|--------------------------------------------------------------------------
*/
Route::get('/parts/index', ['as' => 'parts.index', 'uses' => 'Modules\Parts\Http\Controllers\PartsController@index']);
Route::post('/parts/store', ['as' => 'parts.store', 'uses' => 'Modules\Parts\Http\Controllers\PartsController@store']);
Route::post('/parts/update', ['as' => 'parts.update', 'uses' => 'Modules\Parts\Http\Controllers\PartsController@update']);
