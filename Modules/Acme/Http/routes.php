<?php

Route::get('/command-handling', 'Modules\Acme\Http\Controllers\HomeController@index');
Route::get('/event-dispatcher', 'Modules\Acme\Http\Controllers\HomeController@eventDispatcher');
Route::get('/event-handling', 'Modules\Acme\Http\Controllers\HomeController@eventHandling');
