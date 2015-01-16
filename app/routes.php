<?php

Route::get('/', 'Modules\Acme\Controllers\HomeController@index');
Route::get('/event-dispatcher', 'Modules\Acme\Controllers\HomeController@eventDispatcher');
Route::get('/event-handling', 'Modules\Acme\Controllers\HomeController@eventHandling');
