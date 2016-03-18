<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dashboard.index', 'uses' => 'Modules\Dashboard\Http\Controllers\DashboardController@index']);
});

