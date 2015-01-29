<?php

Route::get('/', 'HomeController@showWelcome');
Route::get('/command-handling', 'Modules\Acme\Controllers\HomeController@index');
Route::get('/event-dispatcher', 'Modules\Acme\Controllers\HomeController@eventDispatcher');
Route::get('/event-handling', 'Modules\Acme\Controllers\HomeController@eventHandling');


/*
|--------------------------------------------------------------------------
| Event sourced child entity
|--------------------------------------------------------------------------
*/
Route::get('/parts/manufacture', 'Modules\Parts\Controllers\PartsController@manufacture');
Route::get('/parts/manufactured-parts', 'Modules\Parts\Controllers\PartsController@manufacturedParts');

Route::get('/parts/rename', 'Modules\Parts\Controllers\PartsController@renameManufacturer');
