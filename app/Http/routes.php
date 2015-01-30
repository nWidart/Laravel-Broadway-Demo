<?php

Route::get('/', 'WelcomeController@index');

Route::get('/command-handling', 'Acme\Controllers\HomeController@index');
Route::get('/event-dispatcher', 'Acme\Controllers\HomeController@eventDispatcher');
Route::get('/event-handling', 'Acme\Controllers\HomeController@eventHandling');

/*
|--------------------------------------------------------------------------
| Event sourced child entity
|--------------------------------------------------------------------------
*/
Route::get('/parts/manufacture', 'Parts\Controllers\PartsController@manufacture');
Route::get('/parts/manufactured-parts', 'Parts\Controllers\PartsController@manufacturedParts');
Route::get('/parts/rename', 'Parts\Controllers\PartsController@renameManufacturer');
