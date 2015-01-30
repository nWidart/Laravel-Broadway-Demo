<?php

Route::get('/', 'WelcomeController@index');

Route::get('/command-handling', 'Acme\Http\Controllers\HomeController@index');
Route::get('/event-dispatcher', 'Acme\Http\Controllers\HomeController@eventDispatcher');
Route::get('/event-handling', 'Acme\Http\Controllers\HomeController@eventHandling');

/*
|--------------------------------------------------------------------------
| Event sourced child entity
|--------------------------------------------------------------------------
*/
Route::get('/parts/manufacture', 'Parts\Http\Controllers\PartsController@manufacture');
Route::get('/parts/manufactured-parts', 'Parts\Http\Controllers\PartsController@manufacturedParts');
Route::get('/parts/rename', 'Parts\Http\Controllers\PartsController@renameManufacturer');
