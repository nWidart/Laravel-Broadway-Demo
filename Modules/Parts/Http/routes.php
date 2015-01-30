<?php

/*
|--------------------------------------------------------------------------
| Event sourced child entity
|--------------------------------------------------------------------------
*/
Route::get('/parts/manufacture', 'Modules\Parts\Http\Controllers\PartsController@manufacture');
Route::get('/parts/manufactured-parts', 'Modules\Parts\Http\Controllers\PartsController@manufacturedParts');
Route::get('/parts/rename', 'Modules\Parts\Http\Controllers\PartsController@renameManufacturer');
