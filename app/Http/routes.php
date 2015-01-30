<?php

Route::get('/', 'WelcomeController@index');

Route::get('/command-handling', 'Acme\Http\Controllers\HomeController@index');
Route::get('/event-dispatcher', 'Acme\Http\Controllers\HomeController@eventDispatcher');
Route::get('/event-handling', 'Acme\Http\Controllers\HomeController@eventHandling');
