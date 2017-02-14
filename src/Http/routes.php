<?php

Route::group([
	'namespace' => 'Kassie\Seat\Calendar\Http\Controllers',
	'middleware' => 'web',
	'prefix' => 'calendar'
], function () {

	Route::resource('operation', 'OperationController');

	Route::get('operation/subscribe/{id}/{status}', 'OperationController@subscribe');
	
	Route::get('operation/close/{id}', 'OperationController@close');
	Route::get('operation/cancel/{id}', 'OperationController@cancel');
	Route::get('operation/activate/{id}', 'OperationController@activate');
	Route::get('operation/delete/{id}', 'OperationController@delete');

});