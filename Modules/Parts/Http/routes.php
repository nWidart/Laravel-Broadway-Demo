<?php

/*
|--------------------------------------------------------------------------
| Event sourced child entity
|--------------------------------------------------------------------------
*/
Route::get('/parts/index', 'Modules\Parts\Http\Controllers\PartsController@index');
Route::get('/parts/manufacture', 'Modules\Parts\Http\Controllers\PartsController@manufacture');
Route::get('/parts/rename/{partId}/{name}', 'Modules\Parts\Http\Controllers\PartsController@renameManufacturer');
