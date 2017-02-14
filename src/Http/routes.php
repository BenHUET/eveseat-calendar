<?php

Route::group([
	'namespace' => 'Kassie\Seat\Calendar\Http\Controllers',
	'middleware' => 'web',
	'prefix' => 'calendar'
], function () {

	Route::resource('operation', 'OperationController');

	Route::post('operation/subscribe', [
		'as' => 'calendar.operation.subscribe',
		'uses' => 'OperationController@subscribe'
	]);
	
	Route::get('operation/close/{id}', 'OperationController@close');
	Route::get('operation/cancel/{id}', 'OperationController@cancel');
	Route::get('operation/activate/{id}', 'OperationController@activate');
	Route::post('operation/delete', [
		'as' => 'calendar.operation.delete',
		'uses' => 'OperationController@delete'
	]);

});